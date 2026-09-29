<x-layouts.app title="Set up your profile — Green Dot">
    @php
        $init = [
            'bio'        => old('bio', $user->bio ?? ''),
            'country'    => old('country', $user->country ?? ''),
            'region'     => old('region', $user->region ?? ''),
            'acceptsAll' => (bool) old('accepts_all_requests', $user->accepts_all_requests),
            'favGames'   => $favGames,
            'nowPlaying' => $nowPlaying,
        ];
    @endphp
    <script>window.__onboardingInit = @json($init);</script>
    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-72px)] px-4 py-12">
        <div x-data="onboarding"
             class="w-full max-w-[480px]">

            {{-- Step indicator --}}
            <div class="flex items-center justify-center mb-8">
                <div class="flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[13px] font-bold transition-colors"
                         :class="step >= 1 ? 'bg-green text-green-ink' : 'bg-surface-2 border border-line text-muted'">1</div>
                    <span class="text-[11px] font-semibold transition-colors"
                          :class="step === 1 ? 'text-text' : 'text-muted'">About you</span>
                </div>
                <div class="w-14 h-px mx-3 mb-5 transition-colors"
                     :class="step >= 2 ? 'bg-green' : 'bg-line'"></div>
                <div class="flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[13px] font-bold transition-colors"
                         :class="step >= 2 ? 'bg-green text-green-ink' : 'bg-surface-2 border border-line text-muted'">2</div>
                    <span class="text-[11px] font-semibold transition-colors"
                          :class="step === 2 ? 'text-text' : 'text-muted'">Your games</span>
                </div>
            </div>

            {{-- Card --}}
            <form method="POST" action="{{ route('profile.edit') }}"
                  class="bg-surface border border-line rounded-[20px] p-8 shadow-card">
                @csrf

                {{-- Hidden inputs managed by Alpine --}}
                <input type="hidden" name="accepts_all_requests" :value="acceptsAll ? '1' : '0'">
                <template x-for="(game, index) in favGames" :key="game.id">
                    <input type="hidden" :name="`favorite_game_ids[${index}]`" :value="game.id">
                </template>
                <input type="hidden" name="current_game_id" :value="nowPlaying ? nowPlaying.id : ''">

                {{-- ── Step 1: About you ──────────────────────────────── --}}
                <div x-show="step === 1">
                    <h1 class="font-display font-extrabold text-[26px] tracking-[-0.03em] mb-1">Set up your profile</h1>
                    <p class="text-[14px] text-muted mb-6 leading-relaxed">Let other players know who you are.</p>

                    {{-- Bio --}}
                    <div class="mb-5">
                        <div class="flex justify-between items-baseline mb-1.5">
                            <label for="bio" class="text-[13px] font-semibold text-muted">Bio</label>
                            <span class="text-[12px] transition-colors"
                                  :class="bioLeft <= 20 ? 'text-amber' : 'text-muted'"
                                  x-text="`${bioLeft} left`"></span>
                        </div>
                        <textarea id="bio" name="bio" x-model="bio" maxlength="160" rows="3"
                                  placeholder="A few words about you as a player…"
                                  class="w-full bg-surface border border-line rounded-[12px] px-4 py-3 text-text text-[14px] placeholder-muted focus:outline-none focus:border-green transition-colors resize-none @error('bio') border-red-500 @enderror"></textarea>
                        @error('bio')
                            <p class="text-[13px] text-red-400 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Country + Region --}}
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div>
                            <label for="country" class="text-[13px] font-semibold text-muted mb-1.5 block">Country</label>
                            <input id="country" type="text" name="country" value="{{ old('country', $user->country) }}"
                                   placeholder="e.g. Netherlands"
                                   class="w-full h-[48px] bg-surface border border-line rounded-[12px] px-4 text-text text-[14px] placeholder-muted focus:outline-none focus:border-green transition-colors @error('country') border-red-500 @enderror">
                        </div>
                        <div>
                            <label for="region" class="text-[13px] font-semibold text-muted mb-1.5 block">Region</label>
                            <div class="relative">
                                <select id="region" name="region"
                                        class="w-full h-[48px] bg-surface border border-line rounded-[12px] px-4 pr-10 text-text text-[14px] focus:outline-none focus:border-green transition-colors appearance-none @error('region') border-red-500 @enderror">
                                    <option value="">Select…</option>
                                    @foreach(['Europe', 'North America', 'South America', 'Asia', 'Oceania', 'Middle East', 'Africa'] as $r)
                                        <option value="{{ $r }}" {{ old('region', $user->region) === $r ? 'selected' : '' }}>{{ $r }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Languages --}}
                    <div class="mb-5">
                        <label class="text-[13px] font-semibold text-muted mb-2 block">Languages</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($languages as $language)
                                <label class="cursor-pointer select-none">
                                    <input type="checkbox" name="language_ids[]" value="{{ $language->id }}"
                                           {{ in_array($language->id, $selectedLanguageIds) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <span class="inline-flex h-9 px-4 items-center rounded-full border border-line text-muted text-[13px] font-semibold transition-colors peer-checked:bg-green peer-checked:text-green-ink peer-checked:border-green hover:text-text hover:border-text cursor-pointer">
                                        {{ $language->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Accepts all requests toggle --}}
                    <div class="flex items-center justify-between pt-5 border-t border-line">
                        <div>
                            <p class="text-[14px] font-semibold text-text">Open to friend requests</p>
                            <p class="text-[13px] text-muted mt-0.5">Show others you welcome new connections</p>
                        </div>
                        <button type="button" @click="acceptsAll = !acceptsAll"
                                :class="acceptsAll ? 'bg-green' : 'bg-surface-2 border border-line'"
                                class="relative ml-4 w-11 h-6 rounded-full transition-colors flex-shrink-0 cursor-pointer">
                            <span :class="acceptsAll ? 'translate-x-5' : 'translate-x-0.5'"
                                  class="absolute top-0.5 w-5 h-5 bg-white rounded-full transition-transform shadow-sm block"></span>
                        </button>
                    </div>
                </div>

                {{-- ── Step 2: Your games ─────────────────────────────── --}}
                <div x-show="step === 2">
                    <h1 class="font-display font-extrabold text-[26px] tracking-[-0.03em] mb-1">Your games</h1>
                    <p class="text-[14px] text-muted mb-6 leading-relaxed">Add your favorites and what you're playing now.</p>

                    {{-- Favorite games --}}
                    <div class="mb-6">
                        <div class="flex items-baseline justify-between mb-2">
                            <label class="text-[13px] font-semibold text-muted">Favorite games</label>
                            <span class="text-[12px] text-muted" x-text="`${favGames.length}/5`"></span>
                        </div>

                        {{-- Selected games --}}
                        <div class="space-y-2 mb-2" x-show="favGames.length > 0">
                            <template x-for="game in favGames" :key="game.id">
                                <div class="flex items-center gap-3 px-3 py-2 bg-surface-2 border border-line rounded-[12px]">
                                    <div class="w-9 h-9 rounded-[8px] flex-shrink-0 overflow-hidden">
                                        <template x-if="game.cover_url">
                                            <img :src="game.cover_url" :alt="game.title" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!game.cover_url">
                                            <div class="w-full h-full" :style="`background: linear-gradient(135deg, ${game.placeholder_color_1 || '#2a2d3a'}, ${game.placeholder_color_2 || '#1a1d27'})`"></div>
                                        </template>
                                    </div>
                                    <span class="text-[14px] font-semibold text-text flex-1 min-w-0 truncate"
                                          x-text="game.short_title || game.title"></span>
                                    <button type="button" @click="removeFavGame(game.id)"
                                            class="flex-shrink-0 ml-2 text-muted hover:text-text transition-colors">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M18 6 6 18M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- Search (hidden when 5 games reached) --}}
                        <div class="relative" x-show="favGames.length < 5">
                            <input type="text" x-model="favQuery" @input="searchFavGames()"
                                   placeholder="Search for a game…"
                                   class="w-full h-[48px] bg-surface border border-line rounded-[12px] px-4 pr-10 text-text text-[14px] placeholder-muted focus:outline-none focus:border-green transition-colors">
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                            </svg>
                            <div x-show="favResults.length > 0"
                                 class="absolute top-full left-0 right-0 mt-1 bg-surface border border-line rounded-[12px] shadow-card overflow-hidden z-10">
                                <template x-for="game in favResults" :key="game.id">
                                    <button type="button" @click="addFavGame(game)"
                                            class="flex items-center gap-3 w-full px-3 py-2.5 hover:bg-surface-2 transition-colors text-left">
                                        <div class="w-8 h-8 rounded-[6px] flex-shrink-0 overflow-hidden">
                                            <template x-if="game.cover_url">
                                                <img :src="game.cover_url" :alt="game.title" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!game.cover_url">
                                                <div class="w-full h-full" :style="`background: linear-gradient(135deg, ${game.placeholder_color_1 || '#2a2d3a'}, ${game.placeholder_color_2 || '#1a1d27'})`"></div>
                                            </template>
                                        </div>
                                        <span class="text-[14px] font-semibold text-text truncate"
                                              x-text="game.title"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <p class="text-[12px] text-muted mt-2" x-show="favGames.length >= 5">Maximum of 5 favorite games reached.</p>
                    </div>

                    {{-- Now playing --}}
                    <div>
                        <label class="text-[13px] font-semibold text-muted mb-2 block">
                            Now playing
                            <span class="font-normal ml-1">— optional</span>
                        </label>
                        <div class="relative">
                            <input type="text" x-model="nowQuery" @input="searchNowPlaying()"
                                   placeholder="Search for a game…"
                                   class="w-full h-[48px] bg-surface border border-line rounded-[12px] px-4 pr-10 text-text text-[14px] placeholder-muted focus:outline-none focus:border-green transition-colors">
                            <button type="button" x-show="nowPlaying" @click="clearNowPlaying()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-text transition-colors">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 6 6 18M6 6l12 12"/>
                                </svg>
                            </button>
                            <svg x-show="!nowPlaying" class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                            </svg>
                            <div x-show="nowResults.length > 0"
                                 class="absolute top-full left-0 right-0 mt-1 bg-surface border border-line rounded-[12px] shadow-card overflow-hidden z-10">
                                <template x-for="game in nowResults" :key="game.id">
                                    <button type="button" @click="selectNowPlaying(game)"
                                            class="flex items-center gap-3 w-full px-3 py-2.5 hover:bg-surface-2 transition-colors text-left">
                                        <div class="w-8 h-8 rounded-[6px] flex-shrink-0 overflow-hidden">
                                            <template x-if="game.cover_url">
                                                <img :src="game.cover_url" :alt="game.title" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!game.cover_url">
                                                <div class="w-full h-full" :style="`background: linear-gradient(135deg, ${game.placeholder_color_1 || '#2a2d3a'}, ${game.placeholder_color_2 || '#1a1d27'})`"></div>
                                            </template>
                                        </div>
                                        <span class="text-[14px] font-semibold text-text truncate"
                                              x-text="game.title"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Navigation ─────────────────────────────────────── --}}
                <div class="mt-8 flex gap-3">
                    <button type="button" x-show="step === 2" @click="step = 1"
                            class="flex-1 h-[48px] bg-transparent border border-line text-muted rounded-[14px] font-semibold text-[14px] cursor-pointer hover:text-text hover:border-text transition-colors">
                        Back
                    </button>
                    <button type="button" x-show="step === 1" @click="step = 2"
                            class="w-full h-[48px] bg-green text-green-ink rounded-[14px] font-semibold text-[15px] cursor-pointer border-0">
                        Continue
                    </button>
                    <button type="submit" x-show="step === 2"
                            class="flex-1 h-[48px] bg-green text-green-ink rounded-[14px] font-semibold text-[15px] cursor-pointer border-0">
                        Save profile
                    </button>
                </div>
            </form>

            <p class="text-center mt-5 text-[13px] text-muted">
                <a href="{{ route('discover') }}" class="hover:text-text transition-colors">Skip for now</a>
            </p>
        </div>
    </div>

</x-layouts.app>
