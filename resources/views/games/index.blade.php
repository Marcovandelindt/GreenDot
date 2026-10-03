<x-layouts.app title="Games — Green Dot">

<div x-data="gameBrowse" class="px-4 lg:px-16 py-7 lg:py-10">

    {{-- ─── Header ───────────────────────────────────────────────────────── --}}
    <div class="max-w-[720px] mb-7 lg:mb-10 flex flex-col gap-3">
        <h1 class="m-0 font-display font-extrabold text-[36px] lg:text-[56px] leading-[1.05] lg:leading-none tracking-[-0.03em]">
            Games
        </h1>
        <p class="m-0 text-[15px] lg:text-[17px] text-muted leading-relaxed">
            Search the PlayStation library. Click a game to see who's playing it.
        </p>
    </div>

    {{-- ─── Search ────────────────────────────────────────────────────────── --}}
    <div class="max-w-[720px] mb-8">
        <label class="h-[56px] lg:h-[60px] flex items-center gap-3 px-5 rounded-[16px] bg-surface border border-line text-muted cursor-text">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <span class="sr-only">Search games</span>
            <input type="search"
                   x-model="query"
                   @input.debounce.300ms="search($event.target.value)"
                   placeholder="Search for a game, e.g. Elden Ring"
                   class="flex-grow min-w-0 h-full border-0 outline-none bg-transparent font-sans text-[16px] lg:text-[18px] text-text placeholder:text-muted">
        </label>
    </div>

    {{-- ─── Results ────────────────────────────────────────────────────────── --}}
    <div x-show="results.length > 0"
         class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-5">
        <template x-for="game in results" :key="game.id">
            <a :href="`/games/${game.slug}`"
               class="group flex flex-col gap-[10px] no-underline">
                <div class="w-full rounded-[12px] overflow-hidden [box-shadow:inset_0_0_0_1px_rgba(255,255,255,0.07)]"
                     style="aspect-ratio: 3/4"
                     :style="coverStyle(game)"></div>
                <div class="flex flex-col gap-[2px]">
                    <span class="text-[13px] font-semibold text-text group-hover:text-green-text transition-colors leading-snug truncate"
                          x-text="game.title"></span>
                    <span x-show="game.release_year"
                          class="text-[12px] text-muted"
                          x-text="game.release_year"></span>
                </div>
            </a>
        </template>
    </div>

    {{-- ─── Empty / prompt ──────────────────────────────────────────────────── --}}
    <div x-show="results.length === 0"
         class="py-20 flex flex-col items-center gap-2 text-center">
        <svg class="text-muted mb-2" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
        <span class="font-display font-extrabold text-[22px]" x-text="query.length > 0 ? 'No games found' : 'Start searching'"></span>
        <span class="text-[14px] text-muted" x-text="query.length > 0 ? 'Try a different title.' : 'Type a game title above.'"></span>
    </div>

</div>

</x-layouts.app>
