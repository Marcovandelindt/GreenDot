# Green Dot

A web app for PlayStation players who want to expand their friends list. Green Dot is a discovery platform: find people who play the same games, check out their profiles, and add them on PSN. The actual friendship happens on PlayStation — Green Dot is the doorway.

The name refers to the green online indicator next to friends in your PSN friends list.

## Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.3 / Laravel 13 |
| Database | MySQL (production) · SQLite (local / tests) |
| Search | Laravel Scout + Meilisearch |
| Game data | IGDB API (via Twitch credentials) |
| Frontend | Alpine.js 3 · Tailwind CSS v4 · SCSS |
| Build | Vite 8 |
| Business logic | [lorisleiva/laravel-actions](https://laravelactions.com/) |

## Features (current)

- **Feed** — social timeline where players post updates, clips, and trophies, each optionally tagged to a game
- **Discover** — browse player profiles filtered by game, language and region
- **Games** — search the PlayStation library (IGDB-backed), see who's playing each game
- **Profiles** — PSN ID, bio, language/region, currently playing, favourite games, played/completed history with hours

## Local setup

**Requirements:** PHP 8.3+, Composer, Node 20+, a running Meilisearch instance

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database
touch database/database.sqlite   # SQLite for local dev
php artisan migrate --seed

# 4. Configure .env
#    IGDB_CLIENT_ID=...
#    IGDB_CLIENT_SECRET=...
#    SCOUT_DRIVER=meilisearch
#    MEILISEARCH_HOST=http://localhost:7700

# 5. Import games from IGDB (takes a few minutes)
php artisan igdb:import --limit=500

# 6. Start dev servers
npm run dev          # Vite (hot reload)
php artisan serve    # Laravel at http://127.0.0.1:8000
```

## Commands

```bash
php artisan serve                   # Local dev server
npm run dev                         # Vite dev server with hot reload
npm run build                       # Production asset build
php artisan test                    # Run test suite
./vendor/bin/pint                   # Fix code style (Laravel Pint)
php artisan migrate                 # Run pending migrations
php artisan migrate:fresh --seed    # Reset database with seeders
php artisan igdb:import             # Sync games from IGDB
```

## Architecture

Business logic lives exclusively in **Action classes** (`app/Actions/`), called via `ActionName::run(...)`. Controllers handle HTTP only — one Action call per method, no inline logic. All input validation is in **Form Requests** (`app/Http/Requests/`).

See [`CLAUDE.md`](CLAUDE.md) for the full conventions.

## Roadmap

1. **Phase 1 (current)** — profiles, game library, feed, player discovery
2. **Phase 2** — PSN verification badge, clip embeds, profile pinned posts
3. **Phase 3** — following, personalised feed, emoji reactions, LFG filter
