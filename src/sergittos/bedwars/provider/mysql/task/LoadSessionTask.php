<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql\task;


use mysqli;
use sergittos\bedwars\provider\mysql\MysqlAsyncTask;
use sergittos\bedwars\session\Session;

class LoadSessionTask extends MysqlAsyncTask {

    private string $xuid;

    public function __construct(Session $session) {
        $this->xuid = $session->getPlayer()->getXuid();
        parent::__construct();
    }

    protected function onConnection(mysqli $mysqli): void {
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE xuid = ?");
        $stmt->bind_param("s", $this->xuid);
        $stmt->execute();

        $result = $stmt->get_result();

        // TODO

        $stmt->free_result();
        $stmt->close();
    }

}