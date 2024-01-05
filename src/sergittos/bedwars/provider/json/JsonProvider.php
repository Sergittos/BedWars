<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\json;


use pocketmine\utils\Config;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\provider\Provider;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\settings\SpectatorSettings;
use function is_dir;
use function mkdir;

class JsonProvider extends Provider {

    public function __construct() {
        $users_dir = $this->getUsersDir();
        if(!is_dir($users_dir)) {
            mkdir($users_dir);
        }
    }

    public function loadSession(Session $session): void {
        $config = $this->getSessionConfig($session);
        $session->setCoins($config->get("coins"));
        $session->setKills($config->get("kills"));
        $session->setWins($config->get("wins"));
        $session->setSpectatorSettings(SpectatorSettings::fromData($session, $config->get("spectator_settings")));
    }

    public function saveSession(Session $session): void {
        $config = $this->getSessionConfig($session);
        $config->set("coins", $session->getCoins());
        $config->set("kills", $session->getKills());
        $config->set("wins", $session->getWins());
        $config->set("spectator_settings", [
            "flying_speed" => $session->getSpectatorSettings()->getFlyingSpeed(),
            "auto_teleport" => $session->getSpectatorSettings()->getAutoTeleport(),
            "night_vision" => $session->getSpectatorSettings()->getNightVision()
        ]);
        $config->save();
    }

    public function updateCoins(Session $session): void {}

    public function updateKills(Session $session): void {}

    public function updateWins(Session $session): void {}

    private function getSessionConfig(Session $session): Config {
        return new Config($this->getUsersDir() . $session->getPlayer()->getXuid() . ".json", Config::JSON, [
            "coins" => 0,
            "kills" => 0,
            "wins" => 0,
            "spectator_settings" => [
                "flying_speed" => 0,
                "auto_teleport" => true,
                "night_vision" => true
            ]
        ]);
    }

    private function getUsersDir(): string {
        return BedWars::getInstance()->getDataFolder() . "users/";
    }

}