<?php

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