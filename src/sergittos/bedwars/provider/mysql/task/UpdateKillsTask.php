<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql\task;


use mysqli;
use sergittos\bedwars\provider\mysql\MysqlAsyncTask;
use sergittos\bedwars\session\Session;

class UpdateKillsTask extends MysqlAsyncTask {

    private string $xuid;
    private int $kills;

    public function __construct(Session $session) {
        $this->xuid = $session->getPlayer()->getXuid();
        $this->kills = $session->getKills();
        parent::__construct();
    }

    protected function onConnection(mysqli $mysqli): void {
        $stmt = $mysqli->prepare("UPDATE bedwars_users SET kills = ? WHERE xuid = ?");
        $stmt->bind_param("is", ...[$this->kills, $this->xuid]);
        $stmt->execute();
        $stmt->close();
    }

}