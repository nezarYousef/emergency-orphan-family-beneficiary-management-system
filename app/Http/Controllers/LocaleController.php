<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', Rule::in(['en', 'ar'])],
        ]);
        $request->session()->put('locale', $validated['locale']);

        $previous = url()->previous();
        $origin = parse_url($request->root());
        $target = parse_url($previous);
        $sameOrigin = is_array($target)
            && ($target['scheme'] ?? null) === ($origin['scheme'] ?? null)
            && ($target['host'] ?? null) === ($origin['host'] ?? null)
            && ($target['port'] ?? null) === ($origin['port'] ?? null);

        return redirect()->to($sameOrigin ? $previous : route('home'));
    }
}
