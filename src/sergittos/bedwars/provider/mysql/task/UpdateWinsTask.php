<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql\task;


use mysqli;
use sergittos\bedwars\provider\mysql\MysqlAsyncTask;
use sergittos\bedwars\session\Session;

class UpdateWinsTask extends MysqlAsyncTask {

    private string $xuid;
    private int $wins;

    public function __construct(Session $session) {
        $this->xuid = $session->getPlayer()->getXuid();
        $this->wins = $session->getWins();
        parent::__construct();
    }

    protected function onConnection(mysqli $mysqli): void {
        $stmt = $mysqli->prepare("UPDATE bedwars_users SET wins = ? WHERE xuid = ?");
        $stmt->bind_param("is", ...[$this->wins, $this->xuid]);
        $stmt->execute();
        $stmt->close();
    }

}