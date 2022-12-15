<?php

namespace App\StorageLayer;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlatformStatisticsRepository {

    public function getPlatformStatistics(): Collection {
        $data = DB::select('
            select * from (
                select count(users.id) as num
                from users
                where
                users.deleted_at is null
            ) users_stats
            union all
            select * from (
                select count(game_flavor.id) as num
                from game_flavor
                where
                game_flavor.deleted_at is null
            ) game_flavor_stats
            union all
			select * from (
                select count(game_flavor.id) as num
                from game_flavor
                where
                game_flavor.deleted_at is null and
                game_flavor.published = 1
            ) published_game_flavor_stats;
        ');
        return collect([
            'Total number of users' => $data[0]->num,
            'Total number of games' => $data[1]->num,
            'Total number of published games' => $data[2]->num
        ]);
    }

    public function getGameFlavorsPerLanguageStatistics(): Collection {
        return collect(DB::select('
            select count(game_flavor.id) as num, language.name from game_flavor
                inner join language on language.id = game_flavor.interface_lang_id
            where game_flavor.deleted_at is null
            and game_flavor.published = 1
            group by game_flavor.interface_lang_id
        '))->flatten();
    }

    public function getNumOfGameFlavorsPerUser(): Collection {
        return collect(DB::select('
            select concat(users.name, " (", users.email, ")" ) as user_name, count(game_flavor.id) as game_flavors_num
            from users
            inner join game_flavor on game_flavor.creator_id = users.id

            where users.deleted_at is null
            and game_flavor.deleted_at is null
            and game_flavor.published = 1

            group by users.id, users.name
            order by game_flavors_num desc
            limit 10
        '));
    }
}
