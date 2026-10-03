<!doctype html>
<html lang="en"
      data-theme="{{ request()->cookie('theme', 'dark') }}"
      x-data="{ theme: '{{ request()->cookie('theme', 'dark') }}' }"
      :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Green Dot' }}</title>

    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col" style="background: var(--gd-page)">

    {{-- ─── Header ──────────────────────────────────────────────────────── --}}
    <header class="flex-shrink-0 border-b border-line">

        {{-- Desktop --}}
        <div class="hidden lg:flex h-[72px] items-center justify-between px-16">

            <div class="flex items-center gap-12">
                <x-wordmark size="lg" />

                <nav aria-label="Main">
                    <ul class="flex gap-2">
                        <li>
                            <a href="{{ route('feed') }}"
                               @class([
                                   'h-11 px-[14px] flex items-center gap-2 rounded-[10px] no-underline font-semibold text-[15px]',
                                   'bg-surface-2 text-text'  => request()->routeIs('feed'),
                                   'text-muted hover:text-text transition-colors' => !request()->routeIs('feed'),
                               ])>
                                Feed
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('discover') }}"
                               @class([
                                   'h-11 px-[14px] flex items-center rounded-[10px] no-underline font-semibold text-[15px]',
                                   'bg-surface-2 text-text'  => request()->routeIs('discover'),
                                   'text-muted hover:text-text transition-colors' => !request()->routeIs('discover'),
                               ])>
                                Discover
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('games.index') }}"
                               @class([
                                   'h-11 px-[14px] flex items-center rounded-[10px] no-underline font-semibold text-[15px]',
                                   'bg-surface-2 text-text'  => request()->routeIs('games.*'),
                                   'text-muted hover:text-text transition-colors' => !request()->routeIs('games.*'),
                               ])>
                                Games
                            </a>
                        </li>
                        @auth
                        <li>
                            <a href="{{ route('profile.show', auth()->user()->psn_id) }}"
                               @class([
                                   'h-11 px-[14px] flex items-center rounded-[10px] no-underline font-semibold text-[15px]',
                                   'bg-surface-2 text-text'  => request()->routeIs('profile.show'),
                                   'text-muted hover:text-text transition-colors' => !request()->routeIs('profile.show'),
                               ])>
                                My profile
                            </a>
                        </li>
                        @endauth
                    </ul>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                {{-- Theme toggle --}}
                <button type="button"
                        aria-label="Toggle theme"
                        @click="
                            theme = theme === 'dark' ? 'light' : 'dark';
                            document.cookie = 'theme=' + theme + '; path=/; max-age=31536000; SameSite=Lax';
                        "
                        class="w-11 h-11 flex items-center justify-center rounded-[10px] text-muted hover:text-text hover:bg-surface-2 transition-colors">
                    <svg x-show="theme === 'dark'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                    </svg>
                    <svg x-show="theme === 'light'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                </button>

                @auth
                <a href="{{ route('profile.edit') }}"
                   class="h-11 px-4 flex items-center gap-2 rounded-[10px] border border-line text-text font-semibold text-sm hover:bg-surface-2 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 20h4L19 9l-4-4L4 16z"/><path d="M13 7l4 4"/>
                    </svg>
                    Edit profile
                </a>

                <a href="{{ route('profile.show', auth()->user()->psn_id) }}"
                   aria-label="My profile"
                   class="relative w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm text-white no-underline"
                   style="background: #2F6F5E">
                    {{ strtoupper(substr(auth()->user()->psn_id, 0, 2)) }}
                    <span class="absolute right-[-1px] bottom-[-1px] w-3 h-3 rounded-full bg-dot border-2"
                          style="border-color: var(--gd-bg); box-shadow: var(--gd-glow)"
                          aria-hidden="true"></span>
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="h-11 px-4 flex items-center rounded-[10px] border border-line text-text font-semibold text-sm hover:bg-surface-2 transition-colors">
                    Sign in
                </a>
                @endauth
            </div>
        </div>

        {{-- Mobile --}}
        <div class="flex lg:hidden h-[60px] items-center justify-between px-4">
            <x-wordmark size="sm" />

            <div class="flex items-center gap-1">
                {{-- Theme toggle --}}
                <button type="button"
                        aria-label="Toggle theme"
                        @click="
                            theme = theme === 'dark' ? 'light' : 'dark';
                            document.cookie = 'theme=' + theme + '; path=/; max-age=31536000; SameSite=Lax';
                        "
                        class="w-11 h-11 flex items-center justify-center rounded-xl text-muted">
                    <svg x-show="theme === 'dark'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                    </svg>
                    <svg x-show="theme === 'light'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                </button>

                @auth
                <a href="{{ route('profile.edit') }}"
                   aria-label="Edit profile"
                   class="w-11 h-11 flex items-center justify-center rounded-xl text-text">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 20h4L19 9l-4-4L4 16z"/><path d="M13 7l4 4"/>
                    </svg>
                </a>

                <a href="{{ route('profile.show', auth()->user()->psn_id) }}"
                   aria-label="My profile"
                   class="relative w-[34px] h-[34px] rounded-full flex items-center justify-center font-bold text-xs text-white no-underline"
                   style="background: #2F6F5E">
                    {{ strtoupper(substr(auth()->user()->psn_id, 0, 2)) }}
                    <span class="absolute right-[-1px] bottom-[-1px] w-[11px] h-[11px] rounded-full bg-dot border-2"
                          style="border-color: var(--gd-bg)"
                          aria-hidden="true"></span>
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="w-11 h-11 flex items-center justify-center rounded-xl text-text font-semibold text-sm">
                    Sign in
                </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ─── Page content ────────────────────────────────────────────────── --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- ─── Footer ──────────────────────────────────────────────────────── --}}
    <footer class="mt-auto py-10 lg:px-16 px-4 flex flex-col items-center gap-7">
        {{ $footerActions ?? '' }}
        <p class="text-xs text-muted text-center">
            Green Dot is an independent fan project and is not affiliated with Sony Interactive Entertainment. Friend requests are sent on PSN.
        </p>
    </footer>

</body>
</html>
