<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\utils;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\ItemTypeIds;

class GameUtils {

    static public function intToRoman(int $number): string {
        return match($number) {
            1 => "I",
            2 => "II",
            3 => "III",
            4 => "IV",
            5 => "MAX",
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