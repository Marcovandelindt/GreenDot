<x-layouts.app title="{{ $game->title }} — Green Dot">
@php
    $gameInit = [
        'playing'       => $isPlaying,
        'favorite'      => $isFavorite,
        'favoriteCount' => $favoriteCount,
        'urlPlaying'    => route('profile.games.playing', $game),
        'urlFavorite'   => route('profile.games.favorite', $game),
    ];
@endphp
<div x-data="gameDetail(@json($gameInit))"
     x-init="init()">

    {{-- ─── Hero ───────────────────────────────────────────────────────────── --}}
    <section class="px-4 lg:px-16 pt-7 pb-6 lg:pt-14 lg:pb-10">

        <div class="flex items-start gap-6 lg:gap-10">

            {{-- Cover --}}
            <div class="flex-shrink-0 w-[100px] lg:w-[160px] rounded-[12px] lg:rounded-[16px] overflow-hidden [box-shadow:inset_0_0_0_1px_rgba(255,255,255,0.08)]"
                 style="aspect-ratio: 3/4">
                @if($game->cover_url)
                    <img src="{{ str_replace('t_cover_big', 't_cover_big_2x', $game->cover_url) }}"
                         alt="{{ $game->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full" style="background: {{ $game->placeholderGradient() }}"></div>
                @endif
            </div>

            {{-- Info + actions --}}
            <div class="flex flex-col gap-4 lg:gap-5 pt-1 min-w-0 flex-grow">

                <div class="flex flex-col gap-[6px]">
                    @if($game->release_year)
                        <span class="text-[12px] lg:text-[13px] font-semibold text-muted tracking-wide uppercase">{{ $game->release_year }}</span>
                    @endif
                    <h1 class="m-0 font-display font-extrabold text-[24px] lg:text-[40px] leading-[1.05] tracking-[-0.02em]">
                        {{ $game->title }}
                    </h1>
                </div>

                {{-- Action buttons --}}
                <div class="flex flex-col gap-2 lg:flex-row lg:gap-3">

                    {{-- Playing now --}}
                    <button type="button"
                            @click="toggle('playing')"
                            :disabled="loading === 'playing'"
                            class="h-11 lg:h-12 px-5 flex items-center justify-center gap-2 rounded-[12px] font-semibold text-[14px] cursor-pointer border transition-colors disabled:opacity-60"
                            :class="playing
                                ? 'bg-green text-green-ink border-green'
                                : 'bg-surface border-line text-text hover:bg-surface-2'"
                            style="box-shadow: none"
                            :style="playing ? 'box-shadow: 0 6px 18px rgba(61,226,127,.22)' : ''">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path x-show="!playing" d="M10 8l6 4-6 4V8z"/>
                            <path x-show="playing" d="M9 9h2v6H9zM13 9h2v6h-2z"/>
                        </svg>
                        <span x-text="playing ? 'Playing now' : 'Set as playing now'"></span>
                    </button>

                    {{-- Add to favorites --}}
                    <button type="button"
                            @click="toggle('favorite')"
                            :disabled="loading === 'favorite' || (!favorite && favoriteCount >= 5)"
                            class="h-11 lg:h-12 px-5 flex items-center justify-center gap-2 rounded-[12px] font-semibold text-[14px] cursor-pointer border transition-colors disabled:opacity-40"
                            :class="favorite
                                ? 'bg-surface-2 text-text border-line'
                                : 'bg-surface border-line text-text hover:bg-surface-2'">
                        <svg width="15" height="15" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
                             :fill="favorite ? 'currentColor' : 'none'">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <span x-text="favoriteLabel"></span>
                    </button>

                </div>

                {{-- Hint when favorites full --}}
                <p x-show="!favorite && favoriteCount >= 5"
                   class="m-0 text-[13px] text-muted">
                    Favorites full — remove one from your profile to add this game.
                </p>

            </div>
        </div>
    </section>

    {{-- ─── Players ─────────────────────────────────────────────────────────── --}}
    @if($players->isNotEmpty())
        <section class="px-4 lg:px-16 py-5 lg:py-6 border-t border-line" aria-label="Players">
            <h2 class="m-0 mb-4 text-[11px] font-bold tracking-[.08em] uppercase text-muted">
                Players
                <span class="opacity-50 font-semibold normal-case tracking-normal text-[13px] ml-1">({{ $players->count() }})</span>
            </h2>
            <div class="flex flex-col gap-2 lg:gap-3 max-w-[640px]">
                @foreach($players as $player)
                    <a href="{{ route('profile.show', $player->psn_id) }}"
                       class="flex items-center gap-3 lg:gap-4 p-3 lg:p-4 rounded-[16px] bg-surface border border-line no-underline hover:bg-surface-2 transition-colors group">

                        {{-- Avatar --}}
                        <div class="relative flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-[14px] text-white"
                             style="background: {{ $player->avatarColor() }}">
                            {{ $player->initials() }}
                            @if($player->isRecentlyActive())
                                <span class="absolute -right-px -bottom-px w-3 h-3 rounded-full bg-dot border-2 shadow-glow"
                                      style="border-color: var(--gd-surface)"
                                      aria-hidden="true"></span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex flex-col gap-[3px] min-w-0 flex-grow">
                            <span class="font-mono font-semibold text-[15px] text-text truncate group-hover:text-green-text transition-colors">
                                {{ $player->psn_id }}
                            </span>
                            <span class="text-[13px] text-muted">
                                {{ $player->languages->pluck('name')->join(', ') }}
                                @if($player->country) · {{ $player->country }} @endif
                            </span>
                        </div>

                        {{-- Activity --}}
                        <span class="flex-shrink-0 text-[12px] lg:text-[13px] font-semibold"
                              :class="false"
                              @class(['text-green-text' => $player->isRecentlyActive(), 'text-muted' => !$player->isRecentlyActive()])>
                            {{ $player->activityLabel() }}
                        </span>

                        <svg class="flex-shrink-0 text-muted group-hover:text-text transition-colors" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
                    </a>
                @endforeach
            </div>
        </section>
    @else
        <div class="px-4 lg:px-16 py-8 border-t border-line">
            <p class="m-0 text-[15px] text-muted">No players found for this game yet.</p>
        </div>
    @endif

</div>

<script>
function gameDetail({ playing, favorite, favoriteCount, urlPlaying, urlFavorite }) {
    return {
        playing,
        favorite,
        favoriteCount,
        loading: null,

        get favoriteLabel() {
            if (this.favorite) return 'In favorites';
            if (this.favoriteCount >= 5) return 'Favorites full';
            return 'Add to favorites (' + this.favoriteCount + '/5)';
        },

        init() {},

        async toggle(type) {
            this.loading = type;
            try {
                const url = type === 'playing' ? urlPlaying : urlFavorite;
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (type === 'playing') {
                    this.playing = data.active;
                } else {
                    this.favorite      = data.active;
                    this.favoriteCount = data.count;
                }
            } finally {
                this.loading = null;
            }
        },
    };
}
</script>
</x-layouts.app>
