import Alpine from 'alpinejs';

Alpine.data('onboarding', () => {
    const initial = window.__onboardingInit || {};

    return {
        step: 1,
        bio: initial.bio ?? '',
        acceptsAll: !!initial.acceptsAll,

        favGames: initial.favGames ?? [],
        favQuery: '',
        favResults: [],
        favDebounce: null,

        nowPlaying: initial.nowPlaying ?? null,
        nowQuery: (initial.nowPlaying && initial.nowPlaying.title) ? initial.nowPlaying.title : '',
        nowResults: [],
        nowDebounce: null,

        get bioLeft() {
            return 160 - this.bio.length;
        },

        searchFavGames() {
            clearTimeout(this.favDebounce);
            if (this.favQuery.length < 2) { this.favResults = []; return; }
            this.favDebounce = setTimeout(async () => {
                const res = await fetch(`/games/search?q=${encodeURIComponent(this.favQuery)}`);
                this.favResults = await res.json();
            }, 300);
        },

        addFavGame(game) {
            if (this.favGames.length >= 5) return;
            if (this.favGames.find(g => g.id === game.id)) return;
            this.favGames.push(game);
            this.favQuery = '';
            this.favResults = [];
        },

        removeFavGame(id) {
            this.favGames = this.favGames.filter(g => g.id !== id);
        },

        searchNowPlaying() {
            clearTimeout(this.nowDebounce);
            if (this.nowPlaying && this.nowQuery === this.nowPlaying.title) return;
            if (this.nowPlaying) this.nowPlaying = null;
            if (this.nowQuery.length < 2) { this.nowResults = []; return; }
            this.nowDebounce = setTimeout(async () => {
                const res = await fetch(`/games/search?q=${encodeURIComponent(this.nowQuery)}`);
                this.nowResults = await res.json();
            }, 300);
        },

        selectNowPlaying(game) {
            this.nowPlaying = game;
            this.nowQuery = game.title;
            this.nowResults = [];
        },

        clearNowPlaying() {
            this.nowPlaying = null;
            this.nowQuery = '';
            this.nowResults = [];
        },
    };
});

window.Alpine = Alpine;
Alpine.start();
