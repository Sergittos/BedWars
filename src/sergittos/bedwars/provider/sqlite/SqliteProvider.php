<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\provider\sqlite;


use sergittos\bedwars\BedWars;
use sergittos\bedwars\provider\Provider;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\settings\SpectatorSettings;
use SQLite3;

class SqliteProvider extends Provider {

    private SQLite3 $sqlite;

    public function __construct() {
        $this->sqlite = new SQLite3(BedWars::getInstance()->getDataFolder() . "database.db");
        $this->sqlite->exec(
            "CREATE TABLE IF NOT EXISTS bedwars_users (
                xuid VARCHAR(16) PRIMARY KEY,
                coins INT,
                kills INT,
                wins INT,
                
                flying_speed INT,
                auto_teleport BOOL,
                night_vision BOOL
            );"
        );
    }

    public function loadSession(Session $session): void {
        $xuid = $session->getPlayer()->getXuid();
        $this->insertIfNotExists($xuid);

        $data = $this->fetchUserDetails($xuid);

        $session->setCoins($data["coins"]);
        $session->setKills($data["kills"]);
        $session->setWins($data["wins"]);
        $session->setSpectatorSettings(SpectatorSettings::fromData($session, $data));
    }

    public function updateCoins(Session $session): void {
        $this->updateProperty($session, "coins");
    }

    public function updateKills(Session $session): void {
        $this->updateProperty($session, "kills");
    }

    public function updateWins(Session $session): void {
        $this->updateProperty($session, "wins");
    }

    private function insertIfNotExists(string $xuid): void {
        $stmt = $this->sqlite->prepare("INSERT OR IGNORE INTO bedwars_users (xuid, coins, kills, wins, flying_speed, auto_teleport, night_vision) VALUES (:xuid, 0, 0, 0, 0, true, true)");
        $stmt->bindParam(":xuid", $xuid);
        $stmt->execute();
    }

    private function fetchUserDetails(string $xuid): array {
        $stmt = $this->sqlite->prepare("SELECT * FROM bedwars_users WHERE xuid = :xuid");
        $stmt->bindParam(":xuid", $xuid);
        $result = $stmt->execute();

        return $result->fetchArray(SQLITE3_ASSOC);
    }

    private function updateProperty(Session $session, string $property): void {
        $stmt = $this->sqlite->prepare("UPDATE bedwars_users SET $property = :value WHERE xuid = :xuid");
        $stmt->bindValue(":value", $session->{'get' . ucfirst($property)}());
        $stmt->bindValue(":xuid", $session->getPlayer()->getXuid());
        $stmt->execute();
    }

    public function saveSession(Session $session): void {}

}