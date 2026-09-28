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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $games     = $this->seedGames();
        $languages = $this->seedLanguages();
        $users     = $this->seedPlayers($games, $languages);
        $this->seedPosts($games, $users);
    }

    private function seedGames(): array
    {
        $rows = [
            ['key' => 'hd2',      'title' => 'Helldivers 2',              'short_title' => 'Helldivers 2',    'release_year' => 2024, 'placeholder_color_1' => '#E9B73A', 'placeholder_color_2' => '#2A2210'],
            ['key' => 'er',       'title' => 'Elden Ring',                'short_title' => 'Elden Ring',      'release_year' => 2022, 'placeholder_color_1' => '#B8975A', 'placeholder_color_2' => '#16130D'],
            ['key' => 'gt7',      'title' => 'Gran Turismo 7',            'short_title' => 'Gran Turismo 7',  'release_year' => 2022, 'placeholder_color_1' => '#D9463C', 'placeholder_color_2' => '#1E0D0C'],
            ['key' => 'astro',    'title' => 'Astro Bot',                 'short_title' => 'Astro Bot',       'release_year' => 2024, 'placeholder_color_1' => '#8FD3F2', 'placeholder_color_2' => '#23407A'],
            ['key' => 'fc',       'title' => 'EA Sports FC 26',           'short_title' => 'FC 26',           'release_year' => 2025, 'placeholder_color_1' => '#D8D2C6', 'placeholder_color_2' => '#262532'],
            ['key' => 'cod',      'title' => 'Call of Duty: Black Ops 6', 'short_title' => 'Black Ops 6',     'release_year' => 2024, 'placeholder_color_1' => '#E0662A', 'placeholder_color_2' => '#120B07'],
            ['key' => 'bg3',      'title' => "Baldur's Gate 3",           'short_title' => "Baldur's Gate 3", 'release_year' => 2023, 'placeholder_color_1' => '#A987D6', 'placeholder_color_2' => '#1B1130'],
            ['key' => 'yotei',    'title' => 'Ghost of Yōtei',            'short_title' => 'Ghost of Yōtei',  'release_year' => 2025, 'placeholder_color_1' => '#E7D9C4', 'placeholder_color_2' => '#6B1E1E'],
            ['key' => 'tsushima', 'title' => 'Ghost of Tsushima',         'short_title' => 'Ghost of Tsushima','release_year' => 2020, 'placeholder_color_1' => '#E8E1D0', 'placeholder_color_2' => '#5A1414'],
            ['key' => 'ghostwire','title' => 'Ghostwire: Tokyo',          'short_title' => 'Ghostwire: Tokyo','release_year' => 2022, 'placeholder_color_1' => '#6FD1C9', 'placeholder_color_2' => '#1A1440'],
            ['key' => 'recon',    'title' => 'Ghost Recon Breakpoint',    'short_title' => 'Ghost Recon',     'release_year' => 2019, 'placeholder_color_1' => '#9AA6B2', 'placeholder_color_2' => '#1D252C'],
            ['key' => 'returnal', 'title' => 'Returnal',                  'short_title' => 'Returnal',        'release_year' => 2021, 'placeholder_color_1' => '#E05A4F', 'placeholder_color_2' => '#0E1A24'],
            ['key' => 'spidey',   'title' => "Marvel's Spider-Man 2",     'short_title' => 'Spider-Man 2',    'release_year' => 2023, 'placeholder_color_1' => '#D8363A', 'placeholder_color_2' => '#141A3A'],
        ];

        $games = [];
        foreach ($rows as $row) {
            $key = $row['key'];
            unset($row['key']);
            $games[$key] = Game::create($row);
        }

        return $games;
    }

    private function seedLanguages(): array
    {
        $names = ['Dutch', 'English', 'German', 'Spanish', 'French', 'Italian', 'Portuguese', 'Polish'];

        $languages = [];
        foreach ($names as $name) {
            $languages[$name] = Language::create(['name' => $name]);
        }

        return $languages;
    }

    private function seedPlayers(array $games, array $languages): array
    {
        $rows = [
            [
                'psn_id' => 'NightOwl_Sanne', 'country' => 'Netherlands', 'region' => 'Europe',
                'languages' => ['Dutch', 'English'], 'last_active_minutes_ago' => 0,
                'accepts_all_requests' => true, 'verified' => true,
                'bio'              => 'Evening player. Long RPGs, cozy platformers and the occasional GT7 photo session. Always up for a Helldivers drop after 21:00.',
                'currently_playing' => 'astro',
                'favorites'         => ['bg3', 'gt7', 'astro', 'er', 'hd2'],
                'played'            => ['bg3' => 212, 'gt7' => 164, 'er' => 97, 'hd2' => 58, 'astro' => 38, 'fc' => 21, 'cod' => 12],
            ],
            [
                'psn_id' => 'KraterKaiser', 'country' => 'Germany', 'region' => 'Europe',
                'languages' => ['German'], 'last_active_minutes_ago' => 0,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'hd2',
                'favorites'         => ['hd2', 'er', 'cod'],
                'played'            => ['hd2' => 486, 'er' => 311, 'cod' => 142],
            ],
            [
                'psn_id' => 'lunatica_mx', 'country' => 'Mexico', 'region' => 'Latin America',
                'languages' => ['Spanish'], 'last_active_minutes_ago' => 8,
                'accepts_all_requests' => false, 'verified' => true,
                'currently_playing' => 'fc',
                'favorites'         => ['fc', 'cod', 'astro'],
                'played'            => ['fc' => 641, 'cod' => 208, 'astro' => 31],
            ],
            [
                'psn_id' => 'BrisketJoe', 'country' => 'United Kingdom', 'region' => 'Europe',
                'languages' => ['English'], 'last_active_minutes_ago' => 25,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'gt7',
                'favorites'         => ['gt7', 'fc', 'hd2'],
                'played'            => ['gt7' => 388, 'fc' => 120, 'hd2' => 64],
            ],
            [
                'psn_id' => 'Polderpiloot', 'country' => 'Belgium', 'region' => 'Europe',
                'languages' => ['Dutch'], 'last_active_minutes_ago' => 60,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'cod',
                'favorites'         => ['cod', 'hd2', 'gt7'],
                'played'            => ['cod' => 530, 'hd2' => 175, 'gt7' => 48],
            ],
            [
                'psn_id' => 'Tarnished_Tomas', 'country' => 'Spain', 'region' => 'Europe',
                'languages' => ['Spanish'], 'last_active_minutes_ago' => 120,
                'accepts_all_requests' => false, 'verified' => false,
                'currently_playing' => 'er',
                'favorites'         => ['er', 'bg3', 'astro'],
                'played'            => ['er' => 402, 'bg3' => 188, 'astro' => 26],
            ],
            [
                'psn_id' => 'm0rgenrot', 'country' => 'Austria', 'region' => 'Europe',
                'languages' => ['German'], 'last_active_minutes_ago' => 240,
                'accepts_all_requests' => true, 'verified' => true,
                'currently_playing' => 'bg3',
                'favorites'         => ['bg3', 'er', 'hd2'],
                'played'            => ['bg3' => 344, 'er' => 129, 'hd2' => 71],
            ],
            [
                'psn_id' => 'EmberJuno', 'country' => 'United States', 'region' => 'North America',
                'languages' => ['English'], 'last_active_minutes_ago' => 1440,
                'accepts_all_requests' => true, 'verified' => false,
                'currently_playing' => 'hd2',
                'favorites'         => ['astro', 'hd2', 'gt7'],
                'played'            => ['hd2' => 233, 'astro' => 44, 'gt7' => 39],
            ],
        ];

        $users = [];
        foreach ($rows as $row) {
            $user = User::create([
                'psn_id'               => $row['psn_id'],
                'bio'                  => $row['bio'] ?? null,
                'country'              => $row['country'],
                'region'               => $row['region'],
                'accepts_all_requests' => $row['accepts_all_requests'],
                'verified'             => $row['verified'],
                'last_active_at'       => Carbon::now()->subMinutes($row['last_active_minutes_ago']),
                'current_game_id'      => $games[$row['currently_playing']]->id,
            ]);

            $user->languages()->attach(
                collect($row['languages'])->map(fn($n) => $languages[$n]->id)->all()
            );

            $pivot = [];
            foreach ($row['played'] as $key => $hours) {
                $favoriteIndex = array_search($key, $row['favorites']);
                $pivot[$games[$key]->id] = [
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

    private function seedPosts(array $games, array $users): void
    {
        $author   = $users['NightOwl_Sanne'];
        $reactors = array_values(array_filter($users, fn($u) => $u->id !== $author->id));

        $rows = [
            [
                'type'            => PostType::Clip,
                'game'            => 'hd2',
                'pinned_position' => 1,
                'caption'         => 'Four seconds on the extraction clock, one reinforcement left and the whole squad yelling. We made it. Barely.',
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
                'game'            => 'gt7',
                'pinned_position' => 3,
                'caption'         => 'Photo mode on the Tokyo Expressway at 2 a.m. in-game. Best part of my week.',
                'reactions'       => ['fire' => 44, 'laugh' => 2, 'wow' => 19, 'clap' => 11, 'heart' => 27],
            ],
        ];

        foreach ($rows as $row) {
            $post = Post::create([
                'user_id'               => $author->id,
                'game_id'               => $games[$row['game']]->id,
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

    // Distribute reactions across the available reactors proportionally.
    // The unique constraint allows one reaction per user per post.
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

        // Fill any remaining slots with the dominant emoji
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
