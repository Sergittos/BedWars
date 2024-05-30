<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\utils;


use pocketmine\block\utils\DyeColor;
use pocketmine\utils\TextFormat;
use function str_replace;

class ColorUtils {

    static public function translate(string $message): string {
        return str_replace([
            "{BLACK}", "{DARK_BLUE}", "{BLUE}", "{DARK_GREEN}", "{DARK_AQUA}", "{DARK_RED}", "{DARK_PURPLE}", "{MAGENTA}",
            "{GOLD}", "{ORANGE}", "{GRAY}", "{DARK_GRAY}", "{CYAN}", "{GREEN}", "{AQUA}", "{RED}", "{LIGHT_PURPLE}", "{PINK}",
            "{YELLOW}", "{WHITE}", "{OBFUSCATED}", "{BOLD}", "{STRIKETHROUGH}", "{UNDERLINE}", "{ITALIC}", "{RESET}"
        ], [
            TextFormat::BLACK, TextFormat::DARK_BLUE, TextFormat::BLUE, TextFormat::DARK_GREEN, TextFormat::DARK_AQUA,
            TextFormat::DARK_RED, TextFormat::DARK_PURPLE, TextFormat::DARK_PURPLE, TextFormat::GOLD, TextFormat::GOLD,
            TextFormat::GRAY, TextFormat::DARK_GRAY, TextFormat::BLUE, TextFormat::GREEN, TextFormat::AQUA, TextFormat::RED,
            TextFormat::LIGHT_PURPLE, TextFormat::LIGHT_PURPLE, TextFormat::YELLOW, TextFormat::WHITE, TextFormat::OBFUSCATED,
            TextFormat::BOLD, TextFormat::STRIKETHROUGH, TextFormat::UNDERLINE, TextFormat::ITALIC, TextFormat::RESET
        ], $message);
    }

    static public function getDye(string $color): DyeColor {
        return match($color) {
            TextFormat::BLACK => DyeColor::BLACK,
            TextFormat::DARK_BLUE => DyeColor::BLUE,
            TextFormat::DARK_GREEN => DyeColor::GREEN,
            TextFormat::DARK_PURPLE => DyeColor::MAGENTA,
            TextFormat::GOLD => DyeColor::ORANGE,
            TextFormat::GRAY => DyeColor::LIGHT_GRAY,
            TextFormat::DARK_GRAY => DyeColor::GRAY,
            TextFormat::BLUE => DyeColor::CYAN,
            TextFormat::GREEN => DyeColor::LIME,
            TextFormat::RED => DyeColor::RED,
            TextFormat::LIGHT_PURPLE => DyeColor::PINK,
            TextFormat::YELLOW => DyeColor::YELLOW,
            TextFormat::WHITE => DyeColor::WHITE,
        };
    }

}