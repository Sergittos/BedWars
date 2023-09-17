<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

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

}