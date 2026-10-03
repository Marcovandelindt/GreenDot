import Alpine from 'alpinejs';

Alpine.data('discoverPage', () => {
    const players = window.__discoverData || [];

    return {
        players,
        query: '',
        lang: 'All',
        region: 'All regions',
        copied: null,
        gameResults: [],
        showDropdown: false,

        async fetchGames(q) {
            if (q.trim().length < 2) {
                this.gameResults = [];
                this.showDropdown = false;
                return;
            }
            try {
                const res = await fetch(`/games/search?q=${encodeURIComponent(q)}`);
                this.gameResults = await res.json();
                this.showDropdown = this.gameResults.length > 0;
            } catch {
                this.gameResults = [];
                this.showDropdown = false;
            }
        },

        coverStyle(game) {
            if (game.cover_url) return `background: url('${game.cover_url}') center/cover no-repeat`;
            const c1 = game.placeholder_color_1 || '#1a1a2e';
            const c2 = game.placeholder_color_2 || '#16213e';
            return `background: linear-gradient(160deg, ${c1} 0%, ${c2} 100%)`;
        },

        get filteredPlayers() {
            const q = this.query.trim().toLowerCase();
            return this.players.filter(p => {
                if (this.lang !== 'All' && !p.languages.includes(this.lang)) return false;
                if (this.region !== 'All regions' && p.region !== this.region) return false;
                if (q && !p.game_titles.some(t => t.toLowerCase().includes(q))) return false;
                return true;
            });
        },

        get isFiltered() {
            return this.query !== '' || this.lang !== 'All' || this.region !== 'All regions';
        },

        copy(psnId) {
            navigator.clipboard.writeText(psnId).catch(() => {});
            this.copied = psnId;
            setTimeout(() => { if (this.copied === psnId) this.copied = null; }, 3000);
        },

        clearFilters() {
            this.query = '';
            this.lang = 'All';
            this.region = 'All regions';
        },
    };
});

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
