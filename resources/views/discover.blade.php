<x-layouts.app title="Discover — Green Dot">

<script>window.__discoverData = @json($players);</script>

<div x-data="discoverPage">

    {{-- ─── Hero ───────────────────────────────────────────────────────────── --}}
    <section class="px-4 lg:px-16 pt-7 pb-5 lg:pt-14 lg:pb-7 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 lg:gap-12">

        <div class="flex flex-col gap-3 lg:gap-[14px] lg:max-w-[720px]">
            <div class="flex items-center gap-[10px] text-[12px] lg:text-[13px] font-semibold tracking-[.08em] uppercase text-green-text">
                <span class="w-2 h-2 rounded-full bg-dot shadow-glow" aria-hidden="true"></span>
                Sorted by recently active
            </div>
            <h1 class="m-0 font-display font-extrabold text-[40px] lg:text-[64px] leading-[1.02] lg:leading-none tracking-[-0.035em]">
                Who's on tonight?
            </h1>
            <p class="m-0 text-[15px] lg:text-[18px] leading-relaxed text-muted">
                <span class="hidden lg:inline">Browse players, see what they play and what they've sunk hundreds of hours into. Found someone? Copy their PSN ID and add them on PlayStation.</span>
                <span class="lg:hidden">See what people play and what they've sunk hours into. Copy their PSN ID and add them on PlayStation.</span>
            </p>
        </div>

        {{-- Random player — desktop pill (right of title) --}}
        <a href="{{ route('random') }}"
           class="hidden lg:flex flex-shrink-0 items-center gap-4 rounded-full bg-green text-green-ink no-underline shadow-green-cta"
           style="padding: 14px 26px 14px 16px">
            <span class="w-11 h-11 rounded-full bg-green-ink text-green flex items-center justify-center relative">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 3h5v5"/><path d="M4 20L21 3"/><path d="M21 16v5h-5"/><path d="M15 15l6 6"/><path d="M4 4l5 5"/></svg>
                <span class="absolute top-[-2px] right-[-2px] w-[11px] h-[11px] rounded-full bg-green border-2 border-green-ink" aria-hidden="true"></span>
            </span>
            <span class="flex flex-col gap-0.5">
                <span class="font-display font-extrabold text-[20px] leading-none tracking-[-0.01em]">Random player</span>
                <span class="text-[13px] font-semibold opacity-80">Deal me someone new</span>
            </span>
        </a>

    </section>

    {{-- Random player — mobile full-width pill --}}
    <div class="px-4 pb-5 lg:hidden">
        <a href="{{ route('random') }}"
           class="flex items-center gap-[14px] rounded-full bg-green text-green-ink no-underline"
           style="height: 64px; padding: 0 20px 0 10px; box-shadow: 0 10px 26px rgba(61,226,127,.22)">
            <span class="w-11 h-11 flex-shrink-0 rounded-full bg-green-ink text-green flex items-center justify-center relative">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 3h5v5"/><path d="M4 20L21 3"/><path d="M21 16v5h-5"/><path d="M15 15l6 6"/><path d="M4 4l5 5"/></svg>
                <span class="absolute top-[-2px] right-[-2px] w-[10px] h-[10px] rounded-full bg-green border-2 border-green-ink" aria-hidden="true"></span>
            </span>
            <span class="flex-grow font-display font-extrabold text-[19px]">Random player</span>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
        </a>
    </div>

    {{-- ─── Filters — desktop ─────────────────────────────────────────────── --}}
    <section aria-label="Filters" class="hidden lg:block px-16">
        <div class="flex items-center gap-3 p-[10px] rounded-[18px] bg-surface border border-line">

            {{-- Game search --}}
            <div class="flex-grow relative" @click.outside="showDropdown = false">
                <label class="h-[52px] flex items-center gap-[10px] px-4 rounded-[12px] bg-surface-2 text-muted cursor-text">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                    <span class="sr-only">Search games</span>
                    <input type="search"
                           x-model.debounce.300ms="query"
                           @input.debounce.300ms="fetchGames($event.target.value)"
                           @keydown.escape="showDropdown = false"
                           placeholder="Search a game, e.g. Elden Ring"
                           class="flex-grow min-w-0 h-full border-0 outline-none bg-transparent font-sans text-[16px] text-text placeholder:text-muted">
                </label>

                {{-- Dropdown --}}
                <div x-show="showDropdown"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-[.98]"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute top-[calc(100%+6px)] left-0 right-0 z-[100] bg-surface border border-line rounded-[16px] shadow-card overflow-hidden py-1">
                    <template x-for="game in gameResults" :key="game.id">
                        <a :href="`/games/${game.slug}`"
                           @click="showDropdown = false"
                           class="flex items-center gap-3 px-3 py-[9px] hover:bg-surface-2 transition-colors no-underline">
                            <div class="flex-shrink-0 w-8 aspect-[3/4] rounded-[5px] [box-shadow:inset_0_0_0_1px_rgba(255,255,255,0.08)]"
                                 :style="coverStyle(game)"></div>
                            <span class="flex-grow text-[14px] font-semibold text-text truncate" x-text="game.title"></span>
                            <svg class="flex-shrink-0 text-muted" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
                        </a>
                    </template>
                </div>
            </div>

            {{-- Language pills --}}
            <div role="group" aria-label="Language" class="flex gap-1 p-1 rounded-[12px] bg-surface-2">
                <template x-for="l in ['All', 'English', 'Dutch', 'German', 'Spanish']" :key="l">
                    <button type="button"
                            @click="lang = l"
                            :aria-pressed="lang === l ? 'true' : 'false'"
                            class="h-11 px-[14px] border-0 rounded-[9px] cursor-pointer font-semibold text-[14px] transition-colors"
                            :class="lang === l ? 'bg-text text-bg' : 'bg-transparent text-muted hover:text-text'"
                            x-text="l"></button>
                </template>
            </div>

            {{-- Region select --}}
            <label class="relative flex items-center">
                <span class="sr-only">Region</span>
                <select x-model="region"
                        class="appearance-none h-[52px] w-[210px] px-4 pr-[40px] rounded-[12px] border-0 bg-surface-2 text-text font-semibold text-[14px] cursor-pointer outline-none">
                    <option>All regions</option>
                    <option>Europe</option>
                    <option>North America</option>
                    <option>Latin America</option>
                </select>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="absolute right-[14px] pointer-events-none text-muted"><path d="M6 9l6 6 6-6"/></svg>
            </label>

        </div>

        <div class="flex items-center justify-between px-1 py-[18px] pb-4 text-[14px] text-muted">
            <span><strong class="text-text" x-text="filteredPlayers.length"></strong> players · most recently active first</span>
            <button x-show="isFiltered"
                    type="button"
                    @click="clearFilters()"
                    class="h-[36px] px-3 border-0 rounded-[8px] bg-transparent text-green-text font-semibold text-[14px] cursor-pointer">
                Clear filters
            </button>
        </div>
    </section>

    {{-- ─── Filters — mobile ──────────────────────────────────────────────── --}}
    <section aria-label="Filters" class="lg:hidden px-4 flex flex-col gap-[10px]">

        {{-- Game search --}}
        <div class="relative" @click.outside="showDropdown = false">
            <label class="h-[50px] flex items-center gap-[10px] px-[14px] rounded-[14px] bg-surface border border-line text-muted cursor-text">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                <span class="sr-only">Search games</span>
                <input type="search"
                       x-model.debounce.300ms="query"
                       @input.debounce.300ms="fetchGames($event.target.value)"
                       @keydown.escape="showDropdown = false"
                       placeholder="Search a game"
                       class="flex-grow min-w-0 h-full border-0 outline-none bg-transparent font-sans text-[16px] text-text placeholder:text-muted">
            </label>

            {{-- Dropdown --}}
            <div x-show="showDropdown"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-[.98]"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute top-[calc(100%+6px)] left-0 right-0 z-[100] bg-surface border border-line rounded-[16px] shadow-card overflow-hidden py-1">
                <template x-for="game in gameResults" :key="game.id">
                    <a :href="`/games/${game.slug}`"
                       @click="showDropdown = false"
                       class="flex items-center gap-3 px-3 py-[9px] hover:bg-surface-2 transition-colors no-underline">
                        <div class="flex-shrink-0 w-7 aspect-[3/4] rounded-[5px] [box-shadow:inset_0_0_0_1px_rgba(255,255,255,0.08)]"
                             :style="coverStyle(game)"></div>
                        <span class="flex-grow text-[14px] font-semibold text-text truncate" x-text="game.title"></span>
                        <svg class="flex-shrink-0 text-muted" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
                    </a>
                </template>
            </div>
        </div>

        {{-- Language + region selects --}}
        <div class="grid grid-cols-2 gap-[10px]">
            <label class="relative flex items-center">
                <span class="sr-only">Language</span>
                <select x-model="lang"
                        class="appearance-none w-full h-12 px-[14px] pr-[36px] rounded-[14px] border border-line bg-surface text-text font-semibold text-[14px] outline-none cursor-pointer">
                    <option value="All">All languages</option>
                    <option>English</option>
                    <option>Dutch</option>
                    <option>German</option>
                    <option>Spanish</option>
                </select>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="absolute right-3 pointer-events-none text-muted"><path d="M6 9l6 6 6-6"/></svg>
            </label>
            <label class="relative flex items-center">
                <span class="sr-only">Region</span>
                <select x-model="region"
                        class="appearance-none w-full h-12 px-[14px] pr-[36px] rounded-[14px] border border-line bg-surface text-text font-semibold text-[14px] outline-none cursor-pointer">
                    <option>All regions</option>
                    <option>Europe</option>
                    <option>North America</option>
                    <option>Latin America</option>
                </select>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="absolute right-3 pointer-events-none text-muted"><path d="M6 9l6 6 6-6"/></svg>
            </label>
        </div>

        <div class="flex items-center justify-between min-h-[44px] text-[14px] text-muted">
            <span><strong class="text-text" x-text="filteredPlayers.length"></strong> players</span>
            <button x-show="isFiltered"
                    type="button"
                    @click="clearFilters()"
                    class="h-11 px-1 border-0 bg-transparent text-green-text font-semibold text-[14px] cursor-pointer">
                Clear filters
            </button>
        </div>

    </section>

    {{-- ─── Players grid ───────────────────────────────────────────────────── --}}
    <section aria-label="Players" class="px-4 lg:px-16 pb-8 grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-5 items-start">
        <template x-for="player in filteredPlayers" :key="player.id">
            <article class="flex flex-col gap-[14px] lg:gap-4 p-4 lg:p-[18px] rounded-[20px] bg-surface border border-line shadow-card">

                {{-- Avatar + name --}}
                <div class="flex items-center gap-3 lg:gap-[14px]">
                    <div class="relative flex-shrink-0 w-12 lg:w-[52px] h-12 lg:h-[52px] rounded-full flex items-center justify-center font-bold text-[16px] lg:text-[17px] text-white"
                         :style="`background: ${player.avatar_color}`">
                        <span x-text="player.initials"></span>
                        <span x-show="player.is_recently_active"
                              class="absolute -right-px -bottom-px w-[13px] lg:w-[14px] h-[13px] lg:h-[14px] rounded-full bg-dot border-[3px] border-surface shadow-glow"
                              aria-hidden="true"></span>
                    </div>
                    <div class="flex flex-col gap-[2px] lg:gap-[3px] min-w-0" :class="!player.is_recently_active ? 'flex-grow' : ''">
                        <a :href="player.profile_url"
                           x-text="player.psn_id"
                           class="font-mono font-semibold text-[16px] text-text no-underline truncate hover:text-green-text transition-colors"></a>
                        <span class="text-[13px] text-muted"
                              x-text="`${player.languages.join(', ')} · ${player.country}`"></span>
                        <span class="hidden lg:block text-[13px] font-semibold"
                              :class="player.is_recently_active ? 'text-green-text' : 'text-muted'"
                              x-text="player.activity_label"></span>
                    </div>
                    {{-- Activity label right-aligned on mobile --}}
                    <span class="lg:hidden flex-shrink-0 text-[12px] font-semibold text-right max-w-[92px]"
                          :class="player.is_recently_active ? 'text-green-text' : 'text-muted'"
                          x-text="player.activity_label"></span>
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-[6px] min-h-[28px]">
                    <span x-show="player.accepts_all_requests"
                          class="h-[28px] px-[10px] flex items-center gap-[6px] rounded-full bg-green-soft text-green-text text-[12px] font-semibold">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="4"/><path d="M2 21c0-3.9 3.1-7 7-7s7 3.1 7 7"/><path d="M19 8v6M16 11h6"/></svg>
                        Accepts all requests
                    </span>
                    <span x-show="player.verified"
                          class="h-[28px] px-[10px] flex items-center gap-[6px] rounded-full bg-surface-2 text-text text-[12px] font-semibold">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l2.4 1.8 3-.2.9 2.9 2.4 1.8-1 2.8 1 2.8-2.4 1.8-.9 2.9-3-.2L12 21l-2.4-1.8-3 .2-.9-2.9-2.4-1.8 1-2.8-1-2.8 2.4-1.8.9-2.9 3 .2z"/><path d="M8.5 12l2.5 2.5 4.5-5"/></svg>
                        Verified
                    </span>
                </div>

                {{-- Currently playing --}}
                <div class="flex flex-col gap-[6px] p-[10px] rounded-[14px] bg-surface-2" x-show="player.current_games.length > 0">
                    <span class="flex items-center gap-[6px] text-[11px] font-bold tracking-[.08em] uppercase text-green-text">
                        <span class="w-[6px] h-[6px] rounded-full bg-dot" aria-hidden="true"></span>
                        Currently playing
                    </span>
                    <div class="flex flex-col gap-[5px]">
                        <template x-for="g in player.current_games" :key="g.title">
                            <div class="flex items-center gap-2">
                                <div class="flex-shrink-0 w-8 h-8 rounded-[6px]" :style="g.cover_style"></div>
                                <span class="text-[13px] font-semibold truncate" x-text="g.title"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Favorite games --}}
                <div class="flex flex-col gap-2">
                    <span class="text-[11px] font-bold tracking-[.08em] uppercase text-muted">Favorites</span>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="game in player.favorite_games.slice(0, 3)" :key="game.title">
                            <div class="aspect-[3/4] rounded-[10px] flex items-end p-2 [box-shadow:inset_0_0_0_1px_rgba(255,255,255,0.08)]"
                                 :style="game.cover_style"
                                 :title="game.title">
                                <span class="font-display font-extrabold text-[11px] leading-[1.05] tracking-[.02em] text-white [text-shadow:0_1px_6px_rgba(0,0,0,.55)]"
                                      x-text="game.short_title"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 mt-auto" aria-live="polite">
                    <button type="button"
                            @click="copy(player.psn_id)"
                            class="flex-grow h-11 flex items-center justify-center gap-2 rounded-[12px] border font-semibold text-[14px] lg:text-[14px] text-[15px] cursor-pointer transition-colors"
                            :class="copied === player.psn_id
                                ? 'bg-green-soft text-green-text border-green-text'
                                : 'bg-surface-2 text-text border-surface-2 hover:border-line'">
                        <svg x-show="copied !== player.psn_id" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"/></svg>
                        <svg x-show="copied === player.psn_id" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        <span x-text="copied === player.psn_id ? 'Copied' : 'Copy PSN ID'"></span>
                    </button>
                    <a :href="player.profile_url"
                       aria-label="View profile"
                       class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-[12px] border border-line text-text hover:bg-surface-2 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
                    </a>
                </div>

            </article>
        </template>
    </section>

    {{-- ─── Empty state ────────────────────────────────────────────────────── --}}
    <div x-show="filteredPlayers.length === 0"
         class="mx-4 lg:mx-16 mb-8 p-12 lg:p-16 rounded-[20px] border border-dashed border-line flex flex-col items-center gap-2 text-center">
        <span class="w-[10px] h-[10px] rounded-full bg-idle" aria-hidden="true"></span>
        <span class="font-display font-extrabold text-[22px] lg:text-2xl">Nobody here yet</span>
        <span class="text-[14px] lg:text-[15px] text-muted">Try another game, or clear the filters to see everyone.</span>
    </div>

</div>


</x-layouts.app>
