<?php

namespace Database\Seeders;

use App\Enums\PostType;
use App\Models\Game;
use App\Models\Language;
use App\Models\Post;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $games = $this->loadGames();

        if ($games->isEmpty()) {
            $this->command->warn('No games found — run `php artisan igdb:import` before seeding.');
            return;
        }

        $languages = $this->seedLanguages();
        $users     = $this->seedPlayers($games, $languages);
        $this->seedPosts($games, $users);
    }

    private function loadGames(): Collection
    {
        $titles = [
            'er'         => 'Elden Ring',
            'gow'        => 'God of War',
            'gowr'       => 'God of War Ragnarök',
            'rdr2'       => 'Red Dead Redemption 2',
            'tw3'        => 'The Witcher 3: Wild Hunt',
            'bb'         => 'Bloodborne',
            'tsushima'   => 'Ghost of Tsushima',
            'hades'      => 'Hades',
            'cp2077'     => 'Cyberpunk 2077',
            'spidey'     => "Marvel's Spider-Man",
            'hollow'     => 'Hollow Knight',
            'ds3'        => 'Dark Souls III',
            'sekiro'     => 'Sekiro: Shadows Die Twice',
            'bg3'        => 'Baldur\'s Gate III',
            'nier'       => 'NieR: Automata',
            'death'      => 'Death Stranding',
            'stardew'    => 'Stardew Valley',
            'control'    => 'Control',
            'horizon'    => 'Horizon Zero Dawn',
            'celeste'    => 'Celeste',
            'it_takes_2' => 'It Takes Two',
        ];

        $byTitle = Game::whereIn('title', array_values($titles))->get()->keyBy('title');

        return collect($titles)->map(fn($title) => $byTitle->get($title))->filter();
    }

    private function seedLanguages(): array
    {
        $names = ['Dutch', 'English', 'German', 'Spanish', 'French', 'Italian', 'Portuguese', 'Polish'];

        $languages = [];
        foreach ($names as $name) {
            $languages[$name] = Language::firstOrCreate(['name' => $name]);
        }

        return $languages;
    }

    private function seedPlayers(Collection $games, array $languages): array
    {
        $rows = [
            [
                'psn_id'   => 'NightOwl_Sanne', 'country' => 'Netherlands', 'region' => 'Europe',
                'languages' => ['Dutch', 'English'], 'last_active_minutes_ago' => 0,
                'accepts_all_requests' => true, 'verified' => true,
                'bio'               => 'Evening player. Long RPGs, cozy platformers and the occasional roguelite run. Always up for a co-op drop after 21:00.',
                'currently_playing' => 'hades',
                'favorites'         => ['er', 'hades', 'stardew', 'hollow', 'gow'],
                'played'            => ['er' => 212, 'hades' => 164, 'stardew' => 97, 'hollow' => 58, 'gow' => 38, 'celeste' => 21, 'bb' => 12],
            ],
            [
                'psn_id'   => 'KraterKaiser', 'country' => 'Germany', 'region' => 'Europe',
                'languages' => ['German'], 'last_active_minutes_ago' => 0,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'bb',
                'favorites'         => ['bb', 'er', 'ds3'],
                'played'            => ['bb' => 486, 'er' => 311, 'ds3' => 142, 'sekiro' => 78],
            ],
            [
                'psn_id'   => 'lunatica_mx', 'country' => 'Mexico', 'region' => 'Latin America',
                'languages' => ['Spanish'], 'last_active_minutes_ago' => 8,
                'accepts_all_requests' => false, 'verified' => true,
                'currently_playing' => 'cp2077',
                'favorites'         => ['cp2077', 'spidey', 'nier'],
                'played'            => ['cp2077' => 641, 'spidey' => 208, 'nier' => 31],
            ],
            [
                'psn_id'   => 'BrisketJoe', 'country' => 'United Kingdom', 'region' => 'Europe',
                'languages' => ['English'], 'last_active_minutes_ago' => 25,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'rdr2',
                'favorites'         => ['rdr2', 'tw3', 'gow'],
                'played'            => ['rdr2' => 388, 'tw3' => 120, 'gow' => 64, 'cp2077' => 44],
            ],
            [
                'psn_id'   => 'Polderpiloot', 'country' => 'Belgium', 'region' => 'Europe',
                'languages' => ['Dutch'], 'last_active_minutes_ago' => 60,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'death',
                'favorites'         => ['death', 'hades', 'control'],
                'played'            => ['death' => 530, 'hades' => 175, 'control' => 48, 'celeste' => 22],
            ],
            [
                'psn_id'   => 'Tarnished_Tomas', 'country' => 'Spain', 'region' => 'Europe',
                'languages' => ['Spanish'], 'last_active_minutes_ago' => 120,
                'accepts_all_requests' => false, 'verified' => false,
                'currently_playing' => 'sekiro',
                'favorites'         => ['er', 'ds3', 'sekiro'],
                'played'            => ['er' => 402, 'ds3' => 188, 'sekiro' => 88, 'bb' => 26],
            ],
            [
                'psn_id'   => 'm0rgenrot', 'country' => 'Austria', 'region' => 'Europe',
                'languages' => ['German'], 'last_active_minutes_ago' => 240,
                'accepts_all_requests' => true, 'verified' => true,
                'currently_playing' => 'bg3',
                'favorites'         => ['bg3', 'er', 'cp2077'],
                'played'            => ['bg3' => 344, 'er' => 129, 'cp2077' => 71],
            ],
            [
                'psn_id'   => 'EmberJuno', 'country' => 'United States', 'region' => 'North America',
                'languages' => ['English'], 'last_active_minutes_ago' => 1440,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'tsushima',
                'favorites'         => ['tsushima', 'gow', 'horizon'],
                'played'            => ['tsushima' => 233, 'gow' => 44, 'horizon' => 39, 'it_takes_2' => 22],
            ],
        ];

        $users = [];
        foreach ($rows as $row) {
            $currentGame = $games->get($row['currently_playing']);

            $user = User::create([
                'psn_id'               => $row['psn_id'],
                'bio'                  => $row['bio'] ?? null,
                'country'              => $row['country'],
                'region'               => $row['region'],
                'accepts_all_requests' => $row['accepts_all_requests'],
                'verified'             => $row['verified'],
                'last_active_at'       => Carbon::now()->subMinutes($row['last_active_minutes_ago']),
                'current_game_id'      => $currentGame?->id,
            ]);

            $user->languages()->attach(
                collect($row['languages'])->map(fn($n) => $languages[$n]->id)->all()
            );

            $pivot = [];
            foreach ($row['played'] as $key => $hours) {
                $game = $games->get($key);
                if (! $game) {
                    continue;
                }
                $favoriteIndex = array_search($key, $row['favorites']);
                $pivot[$game->id] = [
                    'hours'             => $hours,
                    'is_favorite'       => $favoriteIndex !== false,
                    'favorite_position' => $favoriteIndex !== false ? $favoriteIndex + 1 : null,
                ];
            }
            $user->games()->attach($pivot);

            $users[$row['psn_id']] = $user;
        }

        return $users;
    }

    private function seedPosts(Collection $games, array $users): void
    {
        $author   = $users['NightOwl_Sanne'];
        $reactors = array_values(array_filter($users, fn($u) => $u->id !== $author->id));

        $rows = [
            [
                'type'            => PostType::Clip,
                'game'            => 'hades',
                'pinned_position' => 1,
                'caption'         => 'Three Skelly rooms in a row, a Zeus build that just clicked, and the mirror halfway done. This run is different.',
                'reactions'       => ['fire' => 128, 'laugh' => 41, 'wow' => 17, 'clap' => 9, 'heart' => 22],
            ],
            [
                'type'                  => PostType::Trophy,
                'game'                  => 'er',
                'pinned_position'       => 2,
                'caption'               => '112 hours, one very stubborn strength build and zero regrets. On to NG+.',
                'trophy_name'           => 'Elden Lord',
                'trophy_rarity'         => 'Ultra rare',
                'trophy_rarity_percent' => 6.1,
                'reactions'             => ['fire' => 96, 'laugh' => 3, 'wow' => 12, 'clap' => 58, 'heart' => 31],
            ],
            [
                'type'            => PostType::Screenshot,
                'game'            => 'gow',
                'pinned_position' => 3,
                'caption'         => 'Photo mode at the Lake of Nine. Best part of a slow Sunday afternoon.',
                'reactions'       => ['fire' => 44, 'laugh' => 2, 'wow' => 19, 'clap' => 11, 'heart' => 27],
            ],
        ];

        foreach ($rows as $row) {
            $game = $games->get($row['game']);
            if (! $game) {
                continue;
            }

            $post = Post::create([
                'user_id'               => $author->id,
                'game_id'               => $game->id,
                'type'                  => $row['type'],
                'caption'               => $row['caption'],
                'trophy_name'           => $row['trophy_name'] ?? null,
                'trophy_rarity'         => $row['trophy_rarity'] ?? null,
                'trophy_rarity_percent' => $row['trophy_rarity_percent'] ?? null,
                'pinned_position'       => $row['pinned_position'],
            ]);

            $this->assignReactions($post, $reactors, $row['reactions']);
        }
    }

    private function assignReactions(Post $post, array $reactors, array $emojiCounts): void
    {
        $total = array_sum($emojiCounts);
        if ($total === 0 || count($reactors) === 0) {
            return;
        }

        arsort($emojiCounts);

        $n           = count($reactors);
        $assignments = [];

        foreach ($emojiCounts as $emoji => $count) {
            $share = (int) round($n * $count / $total);
            $share = min($share, $n - count($assignments));
            for ($i = 0; $i < $share; $i++) {
                $assignments[] = $emoji;
            }
        }

        $dominant = array_key_first($emojiCounts);
        while (count($assignments) < $n) {
            $assignments[] = $dominant;
        }

        foreach ($reactors as $i => $user) {
            PostReaction::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'emoji'   => $assignments[$i],
            ]);
        }
    }
}
