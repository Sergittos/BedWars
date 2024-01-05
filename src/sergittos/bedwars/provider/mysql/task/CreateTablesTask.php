<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql\task;


use mysqli;
use sergittos\bedwars\provider\mysql\MysqlAsyncTask;

class CreateTablesTask extends MysqlAsyncTask {

    protected function onConnection(mysqli $mysqli): void {
        $mysqli->query(
            "CREATE TABLE IF NOT EXISTS bedwars_users (
                xuid VARCHAR(16) PRIMARY KEY,
                coins INT,
                kills INT,
                wins INT,
                
                flying_speed INT,
                auto_teleport BOOL,
                night_vision BOOL
            )"
        );
    }

}