<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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