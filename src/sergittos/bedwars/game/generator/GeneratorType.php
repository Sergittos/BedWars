<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace sergittos\bedwars\game\generator;


use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\generator\presets\DiamondGenerator;
use sergittos\bedwars\game\generator\presets\EmeraldGenerator;
use sergittos\bedwars\game\generator\presets\GoldGenerator;
use sergittos\bedwars\game\generator\presets\IronGenerator;
use sergittos\bedwars\game\generator\presets\TeamEmeraldGenerator;
use function ucfirst;

enum GeneratorType: string {

    case IRON = "iron";
    case GOLD = "gold";
    case DIAMOND = "diamond";
    case EMERALD = "emerald";
    case TEAM_EMERALD = "team emerald";

    public function createGenerator(Vector3 $position): Generator {
        return match($this) {
            self::IRON => new IronGenerator($position),
            self::GOLD => new GoldGenerator($position),
            self::DIAMOND => new DiamondGenerator($position),
            self::EMERALD => new EmeraldGenerator($position),
            self::TEAM_EMERALD => new TeamEmeraldGenerator($position),
        };
    }

    public function getColor(): string {
        return match($this) {
            self::IRON => TextFormat::WHITE,
            self::GOLD => TextFormat::GOLD,
            self::DIAMOND => TextFormat::AQUA,
            self::EMERALD => TextFormat::DARK_GREEN,
            self::TEAM_EMERALD => "",
        };
    }

    public function getDisplayName(): string {
        return $this->getColor() . $this->getName();
    }

    public function getName(): string {
        return ucfirst($this->value);
    }

}
