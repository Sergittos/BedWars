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
use sergittos\bedwars\session\Session;

class UpdateCoinsTask extends MysqlAsyncTask {

    private string $xuid;
    private int $coins;

    public function __construct(Session $session) {
        $this->xuid = $session->getPlayer()->getXuid();
        $this->coins = $session->getCoins();
        parent::__construct();
    }

    protected function onConnection(mysqli $mysqli): void {
        $stmt = $mysqli->prepare("UPDATE bedwars_users SET coins = ? WHERE xuid = ?");
        $stmt->bind_param("is", ...[$this->coins, $this->xuid]);
        $stmt->execute();
        $stmt->close();
    }

}