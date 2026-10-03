<x-layouts.app title="Feed — Green Dot">

<script>
window.__feedData = {
    posts: @json($posts->map(fn($post) => [
        'id'         => $post->id,
        'caption'    => $post->caption,
        'type'       => $post->type->value,
        'created_at' => $post->created_at->toISOString(),
        'user'       => [
            'psn_id'       => $post->user->psn_id,
            'initials'     => $post->user->initials(),
            'avatar_color' => $post->user->avatarColor(),
            'profile_url'  => route('profile.show', $post->user->psn_id),
        ],
        'game' => $post->game ? [
            'title'               => $post->game->title,
            'slug'                => $post->game->slug,
            'cover_url'           => $post->game->cover_url,
            'placeholder_color_1' => $post->game->placeholder_color_1,
            'placeholder_color_2' => $post->game->placeholder_color_2,
        ] : null,
        'reactions' => [],
    ])->values()),
    urlStore:  '{{ route('posts.store') }}',
    urlSearch: '{{ route('games.search') }}',
};
</script>

<div x-data="feedPage" class="px-4 lg:px-16 py-7 lg:py-10">
    <div class="max-w-[1120px] mx-auto flex gap-8 items-start">

        {{-- ─── Feed column ─────────────────────────────────────────────────── --}}
        <div class="flex-grow min-w-0 flex flex-col gap-4">

            {{-- Create post box --}}
            <div class="rounded-[20px] bg-surface border border-line p-4 lg:p-5 flex flex-col gap-3">

                <div class="flex gap-3 items-start">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-[14px] text-white"
                         style="background: {{ auth()->user()->avatarColor() }}">
                        {{ auth()->user()->initials() }}
                    </div>

                    {{-- Textarea --}}
                    <div class="flex-grow flex flex-col gap-1">
                        <textarea x-model="caption"
                                  @input="caption = $event.target.value"
                                  maxlength="280"
                                  rows="3"
                                  placeholder="What's happening in your PlayStation world?"
                                  class="w-full resize-none rounded-[12px] bg-surface-2 border border-transparent text-text text-[15px] placeholder:text-muted px-4 py-3 outline-none focus:border-line transition-colors font-sans"></textarea>
                        <div class="flex justify-end">
                            <span class="text-[12px] font-semibold"
                                  :class="charsLeft < 20 ? 'text-text' : 'text-muted'"
                                  x-text="charsLeft + ' left'"></span>
                        </div>
                    </div>
                </div>

                {{-- Game search --}}
                <div class="relative ml-[52px]" @click.outside="showGameDropdown = false">
                    <div class="flex items-center gap-2">
                        <label class="flex-grow h-10 flex items-center gap-[10px] px-3 rounded-[10px] bg-surface-2 border border-line text-muted cursor-text">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                            <span class="sr-only">Tag a game</span>
                            <input type="search"
                                   x-model="gameQuery"
                                   @input.debounce.300ms="searchGames($event.target.value)"
                                   @keydown.escape="showGameDropdown = false"
                                   placeholder="Tag a game (optional)"
                                   class="flex-grow min-w-0 h-full border-0 outline-none bg-transparent font-sans text-[14px] text-text placeholder:text-muted">
                        </label>
                        <button x-show="selectedGame"
                                type="button"
                                @click="clearGame()"
                                class="flex-shrink-0 h-10 px-3 rounded-[10px] bg-surface-2 border border-line text-muted hover:text-text text-[13px] font-semibold cursor-pointer transition-colors">
                            Remove
                        </button>
                    </div>

                    {{-- Selected game badge --}}
                    <div x-show="selectedGame" class="mt-2 flex items-center gap-2">
                        <div class="w-6 flex-shrink-0 rounded-[4px]"
                             style="aspect-ratio: 3/4"
                             :style="selectedGame ? coverStyle(selectedGame) : ''"></div>
                        <span class="text-[13px] font-semibold text-text truncate" x-text="selectedGame?.title"></span>
                    </div>

                    {{-- Dropdown --}}
                    <div x-show="showGameDropdown"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-[.98]"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute top-[calc(100%+6px)] left-0 right-0 z-[100] bg-surface border border-line rounded-[14px] shadow-card overflow-hidden py-1">
                        <template x-for="game in gameResults" :key="game.id">
                            <button type="button"
                                    @click="selectGame(game)"
                                    class="w-full flex items-center gap-3 px-3 py-[9px] hover:bg-surface-2 transition-colors text-left cursor-pointer border-0 bg-transparent">
                                <div class="flex-shrink-0 w-7 rounded-[4px]"
                                     style="aspect-ratio: 3/4"
                                     :style="coverStyle(game)"></div>
                                <span class="flex-grow text-[14px] font-semibold text-text truncate" x-text="game.title"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Submit row --}}
                <div class="flex justify-end ml-[52px]">
                    <button type="button"
                            @click="submit()"
                            :disabled="submitting || !caption.trim()"
                            class="h-10 px-5 rounded-[10px] bg-green text-green-ink font-semibold text-[14px] cursor-pointer transition-opacity disabled:opacity-40 border-0">
                        <span x-text="submitting ? 'Posting…' : 'Post'"></span>
                    </button>
                </div>

            </div>

            {{-- Post cards --}}
            <template x-for="post in posts" :key="post.id">
                <article class="rounded-[20px] bg-surface border border-line p-4 lg:p-5 flex flex-col gap-3">

                    {{-- Author row --}}
                    <div class="flex items-center gap-3">
                        <a :href="post.user.profile_url"
                           class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-[14px] text-white no-underline"
                           :style="`background: ${post.user.avatar_color}`"
                           x-text="post.user.initials"></a>
                        <div class="flex flex-col gap-[2px] flex-grow min-w-0">
                            <a :href="post.user.profile_url"
                               x-text="post.user.psn_id"
                               class="font-mono font-semibold text-[15px] text-text no-underline hover:text-green-text transition-colors truncate"></a>
                            <span class="text-[12px] text-muted" x-text="timeAgo(post.created_at)"></span>
                        </div>
                        <span x-show="post.type !== 'update'"
                              class="flex-shrink-0 h-[22px] px-[8px] flex items-center rounded-full bg-surface-2 text-muted text-[11px] font-semibold capitalize"
                              x-text="post.type"></span>
                    </div>

                    {{-- Game tag --}}
                    <div x-show="post.game" class="flex items-center gap-2">
                        <div class="w-8 flex-shrink-0 rounded-[5px]"
                             style="aspect-ratio: 3/4"
                             :style="post.game ? coverStyle(post.game) : ''"></div>
                        <a :href="`/games/${post.game?.slug}`"
                           x-text="post.game?.title"
                           class="text-[13px] font-semibold text-text no-underline hover:text-green-text transition-colors truncate"></a>
                    </div>

                    {{-- Caption --}}
                    <p class="m-0 text-[15px] leading-relaxed text-text" x-text="post.caption"></p>

                </article>
            </template>

            {{-- Empty state --}}
            <div x-show="posts.length === 0"
                 class="p-12 lg:p-16 rounded-[20px] border border-dashed border-line flex flex-col items-center gap-2 text-center">
                <span class="w-[10px] h-[10px] rounded-full bg-idle" aria-hidden="true"></span>
                <span class="font-display font-extrabold text-[22px]">Nothing posted yet</span>
                <span class="text-[14px] text-muted">Be the first to share something.</span>
            </div>

        </div>

        {{-- ─── Sidebar ──────────────────────────────────────────────────────── --}}
        <aside class="hidden lg:flex flex-col gap-4 w-[280px] flex-shrink-0">

            {{-- Currently playing --}}
            @php $currentGames = auth()->user()->currentGames()->take(5)->get(); @endphp
            @if($currentGames->isNotEmpty())
            <div class="rounded-[20px] bg-surface border border-line p-4 flex flex-col gap-3">
                <h2 class="m-0 text-[11px] font-bold tracking-[.08em] uppercase text-muted">Now playing</h2>
                <ul class="m-0 p-0 list-none flex flex-col gap-[10px]">
                    @foreach($currentGames as $game)
                    <li class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-[6px]"
                             style="{{ $game->coverStyle() }}"></div>
                        <a href="{{ route('games.show', $game->slug) }}"
                           class="text-[13px] font-semibold text-text no-underline hover:text-green-text transition-colors truncate">
                            {{ $game->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Discover link --}}
            <a href="{{ route('discover') }}"
               class="rounded-[20px] bg-surface border border-line p-4 flex items-center justify-between no-underline hover:bg-surface-2 transition-colors group">
                <div class="flex flex-col gap-1">
                    <span class="font-semibold text-[14px] text-text">Find players</span>
                    <span class="text-[12px] text-muted">Browse who's on tonight</span>
                </div>
                <svg class="text-muted group-hover:text-text transition-colors" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
            </a>

        </aside>

    </div>
</div>

</x-layouts.app>
