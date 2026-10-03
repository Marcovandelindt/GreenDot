<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IgdbImportCommand extends Command
{
    protected $signature = 'igdb:import
                            {--min-ratings=7 : Minimum number of user ratings a game must have}
                            {--limit=0 : Maximum number of games to import (0 = unlimited)}';

    protected $description = 'Import PS4/PS5 games from IGDB into the local games table';

    private const PLATFORMS  = [48, 167]; // PS4, PS5
    private const PAGE_SIZE  = 500;
    private const RATE_DELAY  = 250_000; // 250 ms in microseconds (IGDB: 4 req/s)

    public function handle(): int
    {
        $minRatings = (int) $this->option('min-ratings');
        $limit      = (int) $this->option('limit');

        $this->info("Fetching Twitch access token…");
        $token = $this->fetchToken();
        if ($token === null) {
            $this->error('Failed to obtain Twitch access token. Check IGDB_CLIENT_ID and IGDB_CLIENT_SECRET.');
            return self::FAILURE;
        }

        $client = Http::withHeaders([
            'Client-ID'     => config('services.igdb.client_id'),
            'Authorization' => "Bearer {$token}",
        ])->baseUrl('https://api.igdb.com/v4');

        $offset   = 0;
        $imported = 0;
        $skipped  = 0;

        $limitLabel = $limit > 0 ? "limit={$limit}, " : '';
        $this->info("Starting import ({$limitLabel}min_rating_count={$minRatings})…");

        do {
            $pageSize = $limit > 0 ? min(self::PAGE_SIZE, $limit - $imported) : self::PAGE_SIZE;
            $games    = $this->fetchPage($client, $offset, $minRatings, $pageSize);

            if (empty($games)) {
                break;
            }

            $rows = [];
            foreach ($games as $game) {
                $title = $game['name'] ?? null;
                if (empty($title)) {
                    $skipped++;
                    continue;
                }

                $coverUrl = isset($game['cover']['image_id'])
                    ? "https://images.igdb.com/igdb/image/upload/t_cover_big/{$game['cover']['image_id']}.jpg"
                    : null;

                $releaseYear = isset($game['first_release_date'])
                    ? (int) date('Y', $game['first_release_date'])
                    : null;

                $rows[] = [
                    'external_id'          => $game['id'],
                    'title'                => $title,
                    'short_title'          => null,
                    'slug'                 => $this->uniqueSlug($title, $game['id']),
                    'summary'              => $game['summary'] ?? null,
                    'cover_url'            => $coverUrl,
                    'placeholder_color_1'  => null,
                    'placeholder_color_2'  => null,
                    'release_year'         => $releaseYear,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            }

            if (!empty($rows)) {
                Game::upsert(
                    $rows,
                    uniqueBy: ['slug'],
                    update:   ['external_id', 'title', 'summary', 'cover_url', 'release_year', 'updated_at'],
                );
                $imported += count($rows);
            }

            $this->line("  offset={$offset} → " . count($rows) . " rows upserted");

            if ($limit > 0 && $imported >= $limit) {
                break;
            }

            $offset += self::PAGE_SIZE;
            usleep(self::RATE_DELAY);

        } while (count($games) === $pageSize);

        $this->info("Done. Imported/updated: {$imported}, skipped: {$skipped}.");

        return self::SUCCESS;
    }

    private function fetchToken(): ?string
    {
        $response = Http::asForm()->post('https://id.twitch.tv/oauth2/token', [
            'client_id'     => config('services.igdb.client_id'),
            'client_secret' => config('services.igdb.client_secret'),
            'grant_type'    => 'client_credentials',
        ]);

        if (!$response->successful()) {
            return null;
        }

        return $response->json('access_token');
    }

    private function fetchPage(PendingRequest $client, int $offset, int $minRatings, int $pageSize): array
    {
        $platforms = implode(',', self::PLATFORMS);
        $limit     = $pageSize;

        $query = <<<APICALYPSE
            fields id,name,slug,summary,first_release_date,cover.image_id,total_rating_count;
            where platforms = ({$platforms})
              & total_rating_count >= {$minRatings};
            sort total_rating_count desc;
            limit {$limit};
            offset {$offset};
            APICALYPSE;

        $response = $client->withBody($query, 'text/plain')->post('games');

        if (!$response->successful()) {
            $this->warn("  IGDB request failed (offset={$offset}): HTTP {$response->status()}");
            $this->warn("  " . substr($response->body(), 0, 300));
            return [];
        }

        $data = $response->json();

        if (!is_array($data) || isset($data['message'])) {
            $this->warn("  Unexpected IGDB response: " . substr($response->body(), 0, 300));
            return [];
        }

        return $data;
    }

    private function uniqueSlug(string $title, int $externalId): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'game';
        }

        $slug = $base;

        if (Game::where('slug', $slug)->whereNull('external_id')->doesntExist()
            && Game::where('slug', $slug)->where('external_id', '!=', $externalId)->doesntExist()) {
            return $slug;
        }

        return $base . '-' . $externalId;
    }
}
