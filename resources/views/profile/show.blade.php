<x-layouts.app title="{{ $user->psn_id }} — Green Dot">
<div x-data="{ copied: false }">

    {{-- ─── Hero ───────────────────────────────────────────────────────────── --}}
    <section class="px-4 lg:px-16 pt-7 pb-6 lg:pt-14 lg:pb-10">

        <div class="flex flex-col lg:flex-row lg:items-start gap-5 lg:gap-10">

            {{-- Avatar + info --}}
            <div class="flex items-start gap-4 lg:gap-5 flex-grow min-w-0">

                {{-- Avatar --}}
                <div class="relative flex-shrink-0 w-[72px] lg:w-[88px] h-[72px] lg:h-[88px] rounded-full flex items-center justify-center font-bold text-[24px] lg:text-[28px] text-white"
                     style="background: {{ $user->avatarColor() }}">
                    {{ $user->initials() }}
                    @if($user->isRecentlyActive())
                        <span class="absolute right-0 bottom-0 w-4 h-4 lg:w-[18px] lg:h-[18px] rounded-full bg-dot border-[3px] shadow-glow"
                              style="border-color: var(--gd-bg)"
                              aria-hidden="true"></span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex flex-col gap-[6px] lg:gap-2 min-w-0 pt-1">
                    <h1 class="m-0 font-mono font-bold text-[22px] lg:text-[30px] leading-none tracking-[-0.01em]">
                        {{ $user->psn_id }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-x-[6px] gap-y-1 text-[13px] lg:text-[14px]">
                        @if($user->last_active_at)
                            <span class="{{ $user->isRecentlyActive() ? 'text-green-text font-semibold' : 'text-muted' }}">
                                {{ $user->activityLabel() }}
                            </span>
                        @endif
                        @if($user->languages->isNotEmpty())
                            @if($user->last_active_at)
                                <span class="text-line" aria-hidden="true">·</span>
                            @endif
                            <span class="text-muted">{{ $user->languages->pluck('name')->join(', ') }}</span>
                        @endif
                        @if($user->country)
                            <span class="text-line" aria-hidden="true">·</span>
                            <span class="text-muted">{{ $user->country }}</span>
                        @endif
                    </div>

                    {{-- Badges --}}
                    @if($user->accepts_all_requests || $user->verified)
                        <div class="flex flex-wrap gap-2 mt-[2px]">
                            @if($user->accepts_all_requests)
                                <span class="h-7 px-[10px] flex items-center gap-[6px] rounded-full bg-green-soft text-green-text text-[12px] font-semibold">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="4"/><path d="M2 21c0-3.9 3.1-7 7-7s7 3.1 7 7"/><path d="M19 8v6M16 11h6"/></svg>
                                    Accepts all requests
                                </span>
                            @endif
                            @if($user->verified)
                                <span class="h-7 px-[10px] flex items-center gap-[6px] rounded-full bg-surface-2 text-text text-[12px] font-semibold">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l2.4 1.8 3-.2.9 2.9 2.4 1.8-1 2.8 1 2.8-2.4 1.8-.9 2.9-3-.2L12 21l-2.4-1.8-3 .2-.9-2.9-2.4-1.8 1-2.8-1-2.8 2.4-1.8.9-2.9 3 .2z"/><path d="M8.5 12l2.5 2.5 4.5-5"/></svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 lg:flex-col lg:flex-shrink-0 lg:pt-1">
                @if(auth()->id() !== $user->id)
                    <button type="button"
                            @click="navigator.clipboard.writeText(@json($user->psn_id)).catch(() => {}); copied = true; setTimeout(() => copied = false, 3000)"
                            class="flex-grow lg:flex-grow-0 lg:w-[200px] h-11 flex items-center justify-center gap-2 rounded-[12px] bg-green text-green-ink font-semibold text-[14px] cursor-pointer border-0"
                            style="box-shadow: 0 8px 22px rgba(61,226,127,.22)">
                        <svg x-show="!copied" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"/></svg>
                        <svg x-show="copied" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        <span x-text="copied ? 'Copied!' : 'Copy PSN ID'"></span>
                    </button>

                    <a href="#"
                       class="lg:w-[200px] h-11 flex items-center justify-center gap-2 rounded-[12px] border border-line text-muted font-semibold text-[14px] no-underline hover:text-text hover:bg-surface-2 transition-colors">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                        Report
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}"
                       class="lg:w-[200px] h-11 flex items-center justify-center gap-2 rounded-[12px] border border-line text-text font-semibold text-[14px] no-underline hover:bg-surface-2 transition-colors">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20h4L19 9l-4-4L4 16z"/><path d="M13 7l4 4"/></svg>
                        Edit profile
                    </a>
                @endif
            </div>
        </div>

        {{-- Bio --}}
        @if($user->bio)
            <p class="mt-5 lg:mt-6 mb-0 max-w-[600px] text-[15px] lg:text-[16px] leading-relaxed text-muted">{{ $user->bio }}</p>
        @endif

    </section>

    {{-- ─── Currently playing ──────────────────────────────────────────────── --}}
    @if($user->currentGames->isNotEmpty())
        <section class="px-4 lg:px-16 py-5 lg:py-6 border-t border-line" aria-label="Currently playing">
            <h2 class="m-0 mb-3 text-[11px] font-bold tracking-[.08em] uppercase text-green-text flex items-center gap-[6px]">
                <span class="w-[6px] h-[6px] rounded-full bg-dot" aria-hidden="true"></span>
                Currently playing
            </h2>
            <div class="flex flex-col gap-2 max-w-sm">
                @foreach($user->currentGames as $game)
                    <a href="{{ route('games.show', $game->slug) }}"
                       class="flex items-center gap-4 p-4 rounded-[18px] bg-surface border border-line no-underline hover:bg-surface-2 transition-colors">
                        <div class="flex-shrink-0 w-[52px] h-[52px] rounded-[10px]"
                             style="{{ $game->coverStyle() }}"></div>
                        <span class="text-[15px] font-semibold text-text truncate">{{ $game->title }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ─── Favorite games ─────────────────────────────────────────────────── --}}
    @if($user->favoriteGames->isNotEmpty())
        <section class="px-4 lg:px-16 py-5 lg:py-6 border-t border-line" aria-label="Favorite games">
            <h2 class="m-0 mb-3 lg:mb-4 text-[11px] font-bold tracking-[.08em] uppercase text-muted">Favorites</h2>
            <div class="flex gap-3 lg:gap-4">
                @foreach($user->favoriteGames->take(5) as $game)
                    <div class="w-[90px] lg:w-[110px] aspect-[3/4] rounded-[12px] lg:rounded-[14px] flex items-end p-[10px] flex-shrink-0 [box-shadow:inset_0_0_0_1px_rgba(255,255,255,0.08)]"
                         style="{{ $game->coverStyle() }}"
                         title="{{ $game->title }}">
                        <span class="font-display font-extrabold text-[11px] lg:text-[12px] leading-[1.05] tracking-[.02em] text-white [text-shadow:0_1px_6px_rgba(0,0,0,.55)]">
                            {{ strtoupper($game->short_title ?? $game->title) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ─── Completed games ───────────────────────────────────────────────── --}}
    @if($user->completedGames->isNotEmpty())
        <section class="px-4 lg:px-16 py-5 lg:py-6 border-t border-line" aria-label="Completed games">
            <h2 class="m-0 mb-3 lg:mb-4 text-[11px] font-bold tracking-[.08em] uppercase text-muted">
                Completed
                <span class="opacity-50 font-semibold normal-case tracking-normal text-[13px] ml-1">({{ $user->completedGames->count() }})</span>
            </h2>
            <div class="flex gap-3 lg:gap-4 flex-wrap">
                @foreach($user->completedGames as $game)
                    <a href="{{ route('games.show', $game->slug) }}"
                       class="relative w-[90px] lg:w-[110px] aspect-[3/4] rounded-[12px] lg:rounded-[14px] flex items-end p-[10px] flex-shrink-0 [box-shadow:inset_0_0_0_1px_rgba(255,255,255,0.08)] no-underline"
                       style="{{ $game->coverStyle() }}"
                       title="{{ $game->title }}">
                        <span class="absolute top-[6px] right-[6px] w-5 h-5 rounded-full bg-green flex items-center justify-center" aria-hidden="true">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        </span>
                        <span class="font-display font-extrabold text-[11px] lg:text-[12px] leading-[1.05] tracking-[.02em] text-white [text-shadow:0_1px_6px_rgba(0,0,0,.55)]">
                            {{ strtoupper($game->short_title ?? $game->title) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ─── All games ───────────────────────────────────────────────────────── --}}
    @if($user->games->isNotEmpty())
        <section class="px-4 lg:px-16 py-5 lg:py-6 border-t border-line" aria-label="Games">
            <h2 class="m-0 mb-3 lg:mb-4 text-[11px] font-bold tracking-[.08em] uppercase text-muted">
                Games
                <span class="opacity-50 font-semibold normal-case tracking-normal text-[13px] ml-1">({{ $user->games->count() }})</span>
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 lg:gap-3 max-w-[800px]">
                @foreach($user->games as $game)
                    <a href="{{ route('games.show', $game->slug) }}"
                       class="flex items-center gap-3 p-3 lg:p-[14px] rounded-[14px] bg-surface border border-line no-underline hover:bg-surface-2 transition-colors">
                        <div class="flex-shrink-0 w-10 h-10 lg:w-11 lg:h-11 rounded-[8px]"
                             style="{{ $game->coverStyle() }}"></div>
                        <div class="min-w-0 flex-grow">
                            <span class="block text-[14px] lg:text-[15px] font-semibold text-text truncate">{{ $game->title }}</span>
                            <div class="flex gap-[6px] mt-[3px]">
                                @if($game->pivot->is_playing)
                                    <span class="text-[11px] font-semibold text-green-text">Playing</span>
                                @endif
                                @if($game->pivot->is_completed)
                                    <span class="text-[11px] font-semibold text-muted">Completed</span>
                                @elseif($game->pivot->is_played)
                                    <span class="text-[11px] font-semibold text-muted">Played</span>
                                @endif
                            </div>
                        </div>
                        @if($game->pivot->hours)
                            <span class="flex-shrink-0 text-[13px] text-muted font-semibold tabular-nums">{{ $game->pivot->hours }}h</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ─── Empty state ─────────────────────────────────────────────────────── --}}
    @if($user->games->isEmpty() && $user->currentGames->isEmpty() && !$user->bio)
        <div class="px-4 lg:px-16 py-8 lg:py-12 border-t border-line">
            <p class="text-[15px] text-muted">This player hasn't filled in their profile yet.</p>
        </div>
    @endif

</div>
</x-layouts.app>
