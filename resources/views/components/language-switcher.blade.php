@php($targetLocale = app()->getLocale() === 'ar' ? 'en' : 'ar')
<form method="post" action="{{ route('locale.update') }}" {{ $attributes->class(['language-switcher']) }} data-no-loading>
    @csrf
    <input type="hidden" name="locale" value="{{ $targetLocale }}">
    <button class="btn btn-sm btn-outline-secondary" type="submit"><span class="visually-hidden">{{ __('common.switch_language') }}: </span><span lang="{{ $targetLocale }}" dir="{{ $targetLocale === 'ar' ? 'rtl' : 'ltr' }}">{{ $targetLocale === 'ar' ? 'العربية' : 'English' }}</span></button>
</form>
