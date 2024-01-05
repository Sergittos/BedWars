<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql\task;


use mysqli;
use sergittos\bedwars\provider\mysql\MysqlAsyncTask;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\session\settings\SpectatorSettings;

class LoadSessionTask extends MysqlAsyncTask {

    private string $xuid;
    private string $username;

    public function __construct(Session $session) {
        $this->xuid = $session->getPlayer()->getXuid();
        $this->username = $session->getUsername();
        parent::__construct();
    }

    protected function onConnection(mysqli $mysqli): void {
        $this->insertIfNotExists($mysqli);
        $this->fetchUserDetails($mysqli);
    }

    private function insertIfNotExists(mysqli $mysqli): void {
        $stmt = $mysqli->prepare("INSERT IGNORE INTO bedwars_users (xuid, coins, kills, wins, flying_speed, auto_teleport, night_vision) VALUES (?, 0, 0, 0, 0, true, true)");
        $stmt->bind_param("s", ...[$this->xuid]);
        $stmt->execute();
        $stmt->close();
    }

    private function fetchUserDetails(mysqli $mysqli): void {
        $stmt = $mysqli->prepare("SELECT * FROM bedwars_users WHERE xuid = ?");
        $stmt->bind_param("s", ...[$this->xuid]);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $this->setResult($rows);

        $stmt->free_result();
        $stmt->close();
    }

    public function onCompletion(): void {
        $session = SessionFactory::getSessionByName($this->username);
        if($session === null) {
            return;
        }

        $rows = $this->getResult();
        $data = $rows[0];

        $session->setKills((int) $data["kills"]);
        $session->setWins((int) $data["wins"]);
        $session->setCoins((int) $data["coins"]);
        $session->setSpectatorSettings(SpectatorSettings::fromData($session, $data));
    }

}