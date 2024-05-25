<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql;


use mysqli;
use pocketmine\scheduler\AsyncTask;
use sergittos\bedwars\BedWars;

abstract class MysqlAsyncTask extends AsyncTask {

    private MysqlCredentials $credentials;

    public function __construct(?MysqlCredentials $credentials = null) {
        $this->credentials = $credentials ?? BedWars::getInstance()->getProvider()->getCredentials();
    }

    public function onRun(): void {
        $mysqli = $this->credentials->getMysqli();
        $this->onConnection($mysqli);
        $mysqli->close();
    }

    abstract protected function onConnection(mysqli $mysqli): void;

}