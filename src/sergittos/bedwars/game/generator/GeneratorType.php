<?php


namespace sergittos\bedwars\game\generator;


use pocketmine\math\Vector3;
use sergittos\bedwars\game\generator\presets\DiamondGenerator;
use sergittos\bedwars\game\generator\presets\EmeraldGenerator;
use sergittos\bedwars\game\generator\presets\GoldGenerator;
use sergittos\bedwars\game\generator\presets\IronGenerator;
use sergittos\bedwars\game\generator\presets\TeamEmeraldGenerator;

enum GeneratorType {

    case IRON;
    case GOLD;
    case DIAMOND;
    case EMERALD;
    case TEAM_EMERALD;

    static public function toGenerator(Vector3 $position, GeneratorType $type): Generator {
        return match($type) {
            self::IRON => new IronGenerator($position),
            self::GOLD => new GoldGenerator($position),
            self::DIAMOND => new DiamondGenerator($position),
            self::EMERALD => new EmeraldGenerator($position),
            self::TEAM_EMERALD => new TeamEmeraldGenerator($position),
        };
    }

    static public function fromString(string $type): self {
        return match(strtolower($type)) {
            "iron" => self::IRON,
            "gold" => self::GOLD,
            "diamond" => self::DIAMOND,
            "emerald" => self::EMERALD,
            "team_emerald" => self::TEAM_EMERALD,
            default => throw new \InvalidArgumentException("Invalid generator type: $type"),
        };
    }

    public function toString(): string {
        return match($this) {
            self::IRON => "Iron",
            self::GOLD => "Gold",
            self::DIAMOND => "Diamond",
            self::EMERALD => "Emerald",
            self::TEAM_EMERALD => "Team Emerald",
        };
    }

}
