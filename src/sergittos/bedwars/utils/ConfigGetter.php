<?php

declare(strict_types=1);


namespace sergittos\bedwars\utils;


use sergittos\bedwars\BedWars;

class ConfigGetter {

    static private function get(string $key, mixed $default = false): mixed {
        return BedWars::getInstance()->getConfig()->get($key, $default);
    }

    static public function getVersion(): int|float {
        return self::get("version", 1.0);
    }

    static public function getIP(): string {
        return self::get("ip", "play.server.net");
    }

    static public function isSpawnProtectionEnabled(): bool {
        return self::get("spawn-protection", true);
    }

    static public function getProvider(): string {
        return self::get("provider", "sqlite");
    }

    static public function getMysqlCredentials(): array {
        return self::get("mysql-credentials", []);
    }

}