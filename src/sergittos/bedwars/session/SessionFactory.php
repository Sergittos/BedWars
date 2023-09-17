<?php

declare(strict_types=1);


namespace sergittos\bedwars\session;


use pocketmine\player\Player;
use function strtolower;

class SessionFactory {

    /** @var Session[] */
    static private array $sessions = [];

    /**
     * @return Session[]
     */
    static public function getSessions(): array {
        return self::$sessions;
    }

    static public function hasSession(Player $player): bool {
        return isset(self::$sessions[strtolower($player->getName())]);
    }

    static public function getSession(Player $player): ?Session {
        return self::$sessions[strtolower($player->getName())] ?? null;
    }

    static public function getSessionByName(string $name): ?Session {
        return self::$sessions[strtolower($name)] ?? null;
    }

    static public function createSession(Player $player): void {
        self::$sessions[strtolower($player->getName())] = new Session($player);
    }

    static public function removeSession(Player $player): void {
        self::getSession($player)->save();
        unset(self::$sessions[strtolower($player->getName())]);
    }

}