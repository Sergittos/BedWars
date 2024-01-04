<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql;


use pocketmine\Server;
use sergittos\bedwars\provider\Provider;
use sergittos\bedwars\session\Session;

class MysqlProvider extends Provider {

    public function __construct() {
    }

    public function loadSession(Session $session): void {
        // TODO: Implement loadSession() method.
    }

    public function saveSession(Session $session): void {
        // TODO: Implement saveSession() method.
    }

    private function scheduleAsyncTask(MysqlAsyncTask $task): void {
        Server::getInstance()->getAsyncPool()->submitTask($task);
    }

}