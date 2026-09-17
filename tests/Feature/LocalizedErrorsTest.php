<?php

namespace Tests\Feature;

use App\Http\Middleware\SetLocale;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LocalizedErrorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_locale_runs_after_session_and_before_csrf_and_binding(): void
    {
        $this->get('/login')->assertOk();
        $router = $this->app['router'];
        $route = $router->getRoutes()->match(Request::create('/families/999999'));
        $middleware = $router->gatherRouteMiddleware($route);
        $locale = array_search(SetLocale::class, $middleware, true);

        $this->assertIsInt($locale);
        $this->assertLessThan($locale, array_search(StartSession::class, $middleware, true));
        $this->assertGreaterThan($locale, array_search(PreventRequestForgery::class, $middleware, true));
        $this->assertGreaterThan($locale, array_search(SubstituteBindings::class, $middleware, true));
    }

    #[DataProvider('locales')]
    public function test_real_csrf_rejection_uses_session_locale_without_changing_it(string $locale): void
    {
        $this->app->bind(PreventRequestForgery::class, fn ($app) => new class($app, $app['encrypter']) extends PreventRequestForgery
        {
            protected function runningUnitTests()
            {
                return false;
            }
        });

        $page = $this->get('/login')->assertOk();
        $this->useSessionCookie($page);
        preg_match('/name="_token" value="([^"]+)"/', $page->getContent(), $matches);
        $this->assertNotEmpty($matches[1] ?? null);

        $switched = $this->post('/locale', ['locale' => $locale, '_token' => $matches[1]])
            ->assertRedirect()
            ->assertSessionHas('locale', $locale);
        $this->useSessionCookie($switched);

        foreach ([[], ['_token' => 'invalid-token']] as $token) {
            $this->app->setLocale($locale === 'ar' ? 'en' : 'ar');
            $response = $this->post('/locale', ['locale' => $locale === 'ar' ? 'en' : 'ar', ...$token]);
            $this->assertLocalizedError($response, 419, $locale);
            $response->assertSessionHas('locale', $locale);
        }
    }

    #[DataProvider('locales')]
    public function test_missing_bound_record_uses_session_locale(string $locale): void
    {
        $this->actingAs(User::factory()->create(['role' => 'viewer']))->withSession(['locale' => $locale]);
        $this->app->setLocale($locale === 'ar' ? 'en' : 'ar');

        $this->assertLocalizedError($this->get('/families/999999'), 404, $locale);
    }

    #[DataProvider('locales')]
    public function test_unmatched_route_loads_locale_from_encrypted_session_cookie(string $locale): void
    {
        $response = $this->post('/locale', ['locale' => $locale])->assertRedirect();
        $this->useSessionCookie($response);
        $this->app['session.store']->flush();
        $this->app->setLocale($locale === 'ar' ? 'en' : 'ar');

        $response = $this->get('/definitely-not-a-route');
        $this->assertLocalizedError($response, 404, $locale);
        $response->assertSessionHas('locale', $locale)->assertCookie(config('session.cookie'));
    }

    public function test_unmatched_route_without_cookie_does_not_inherit_arabic(): void
    {
        $this->app->setLocale('ar');

        $this->assertLocalizedError($this->get('/definitely-not-a-route'), 404, 'en');
    }

    #[DataProvider('invalidLocales')]
    public function test_unmatched_route_with_invalid_session_locale_defaults_to_english(mixed $locale): void
    {
        $response = $this->withSession(['locale' => $locale])->get('/login')->assertOk();
        $this->useSessionCookie($response);
        $this->app->setLocale('ar');

        $this->assertLocalizedError($this->get('/definitely-not-a-route'), 404, 'en');
    }

    public function test_unmatched_route_with_tampered_cookie_defaults_to_english(): void
    {
        $this->withUnencryptedCookie(config('session.cookie'), 'invalid-encrypted-cookie');
        $this->app->setLocale('ar');

        $this->assertLocalizedError($this->get('/definitely-not-a-route'), 404, 'en');
    }

    public function test_unmatched_json_and_api_routes_remain_stateless_json_errors(): void
    {
        foreach (['/definitely-not-a-route', '/api/definitely-not-a-route'] as $path) {
            $this->getJson($path)->assertNotFound()->assertJsonStructure(['message'])
                ->assertCookieMissing(config('session.cookie'));
            $this->assertFalse($this->app['request']->hasSession());
        }

        $this->get('/api/definitely-not-a-route')->assertNotFound()->assertJsonStructure(['message'])
            ->assertCookieMissing(config('session.cookie'));
    }

    #[DataProvider('locales')]
    public function test_localization_keeps_authentication_and_role_restrictions(string $locale): void
    {
        $this->withSession(['locale' => $locale])->get('/families')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'viewer']));

        $this->assertLocalizedError($this->get('/users'), 403, $locale);
    }

    #[DataProvider('locales')]
    public function test_bootstrap_pagination_is_translated_directional_and_preserves_search(string $locale): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Pagination User']);
        User::factory()->count(30)->create(['name' => 'Pagination User']);
        $this->actingAs($admin)->withSession(['locale' => $locale]);

        foreach ([1, 2, 3] as $page) {
            $response = $this->get('/users?'.http_build_query(['search' => 'Pagination User', 'page' => $page]))->assertOk();
            $paginator = $response->viewData('users');
            $html = $paginator->links()->toHtml();
            $text = preg_replace('/\s+/u', ' ', strip_tags($html));
            $summary = $locale === 'ar'
                ? 'عرض '.$paginator->firstItem().' إلى '.$paginator->lastItem().' من 31 نتيجة'
                : 'Showing '.$paginator->firstItem().' to '.$paginator->lastItem().' of 31 results';

            $this->assertStringContainsString($summary, $text);
            $this->assertStringContainsString('dir="'.($locale === 'ar' ? 'rtl' : 'ltr').'"', $html);
            $this->assertStringContainsString('aria-current="page"', $html);
            $this->assertStringNotContainsString('<svg', $html);
            $this->assertStringNotContainsString('pagination.', $html);

            if ($locale === 'ar') {
                foreach (['Showing', 'results', 'Previous', 'Next'] as $english) {
                    $this->assertStringNotContainsString($english, $html);
                }
            }

            foreach (['prev' => $paginator->previousPageUrl(), 'next' => $paginator->nextPageUrl()] as $rel => $url) {
                $label = __('pagination.'.($rel === 'prev' ? 'previous' : 'next'));
                $icon = ($rel === 'prev') === ($locale === 'ar') ? '›' : '‹';
                if ($url) {
                    $this->assertStringContainsString('search=Pagination%20User', $url);
                    $this->assertStringContainsString('href="'.e($url).'" rel="'.$rel.'" aria-label="'.$label.'"><span aria-hidden="true" dir="ltr">'.$icon.'</span>', $html);
                } else {
                    $this->assertStringContainsString('aria-disabled="true" aria-label="'.$label.'"', $html);
                    $this->assertStringNotContainsString('rel="'.$rel.'"', $html);
                }
            }
        }
    }

    public function test_bootstrap_pagination_handles_empty_single_page_and_ellipses(): void
    {
        foreach ([0, 10] as $total) {
            $paginator = new LengthAwarePaginator(range(1, 10), $total, 15);
            $this->assertSame('', trim($paginator->links()->toHtml()));
        }

        $paginator = new LengthAwarePaginator(range(136, 150), 450, 15, 10, ['path' => '/users']);
        $this->assertStringContainsString('...', $paginator->links()->toHtml());
    }

    public static function locales(): array
    {
        return ['English' => ['en'], 'Arabic' => ['ar']];
    }

    public static function invalidLocales(): array
    {
        return ['unsupported' => ['fr'], 'missing' => [null], 'array' => [['ar']]];
    }

    private function useSessionCookie(TestResponse $response): void
    {
        $cookie = $response->getCookie(config('session.cookie'), false);
        $this->assertNotNull($cookie);
        $this->withUnencryptedCookie($cookie->getName(), $cookie->getValue());
    }

    private function assertLocalizedError(TestResponse $response, int $status, string $locale): void
    {
        $response->assertStatus($status)
            ->assertSee('<html lang="'.$locale.'" dir="'.($locale === 'ar' ? 'rtl' : 'ltr').'">', false)
            ->assertSeeText(__('errors.'.$status.'.heading', [], $locale))
            ->assertSeeText(__('errors.'.$status.'.message', [], $locale));
    }
}
