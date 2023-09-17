<?php

declare(strict_types=1);


namespace sergittos\bedwars\utils;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\ItemTypeIds;
use function strtolower;

class GameUtils {

    static public function getMode(int $players_per_team): string {
        return match($players_per_team) {
            1 => "Solo",
            2 => "Duos",
            4 => "Squads",
            default => "Unknown"
        };
    }

    static public function getGeneratorColor(string $name): string {
        return match(strtolower($name)) {
            "emerald" => "{DARK_GREEN}",
            "diamond" => "{AQUA}",
            "gold" => "{GOLD}",
            "iron" => "{WHITE}",
            default => ""
        };
    }

    static public function getEffectDuration(EffectInstance $effect): int {
        return match($effect->getType()) {
            VanillaEffects::SPEED(), VanillaEffects::JUMP_BOOST() => 45,
            VanillaEffects::INVISIBILITY() => 30,
            default => 0
        } * 20;
    }

    static public function getEffectAmplifier(EffectInstance $effect): int {
        return match($effect->getType()) {
            VanillaEffects::SPEED() => 1,
            VanillaEffects::JUMP_BOOST() => 4,
            default => 0
        };
    }

    static public function getCountById(int $id): int {
        return match($id) {
            ItemTypeIds::IRON_INGOT => 48,
            ItemTypeIds::GOLD_INGOT => 16,
            ItemTypeIds::DIAMOND => 4,
            ItemTypeIds::EMERALD => 2,
            default => 64
        };
    }

    static public function getColoredTitleNumber(int $number): string {
        return match(true) {
            $number <= 3 => "{RED}",
            $number <= 5 => "{YELLOW}",
            default => "{GREEN}"
        } . $number;
    }

    static public function getColoredMessageNumber(int $number): string {
        return match(true) {
            $number <= 5 => "{RED}",
            $number <= 10 => "{GOLD}",
            $number <= 20 => "{AQUA}",
            default => "{GREEN}"
        } . $number;
    }

}