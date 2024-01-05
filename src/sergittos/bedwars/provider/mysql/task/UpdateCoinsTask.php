<?php

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