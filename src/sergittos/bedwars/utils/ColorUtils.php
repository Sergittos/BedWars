<?php

declare(strict_types=1);


namespace sergittos\bedwars\utils;


use pocketmine\block\utils\DyeColor;
use pocketmine\utils\TextFormat;
use function str_replace;

class ColorUtils {

    static public function translate(string $message): string {
        $message = str_replace("{BLACK}", TextFormat::BLACK, $message);
        $message = str_replace("{DARK_BLUE}", TextFormat::DARK_BLUE, $message);
        $message = str_replace("{BLUE}", TextFormat::DARK_BLUE, $message);
        $message = str_replace("{DARK_GREEN}", TextFormat::DARK_GREEN, $message);
        $message = str_replace("{DARK_AQUA}", TextFormat::DARK_AQUA, $message);
        $message = str_replace("{DARK_RED}", TextFormat::DARK_RED, $message);
        $message = str_replace("{DARK_PURPLE}", TextFormat::DARK_PURPLE, $message);
        $message = str_replace("{MAGENTA}", TextFormat::DARK_PURPLE, $message);
        $message = str_replace("{GOLD}", TextFormat::GOLD, $message);
        $message = str_replace("{ORANGE}", TextFormat::GOLD, $message);
        $message = str_replace("{GRAY}", TextFormat::GRAY, $message);
        $message = str_replace("{DARK_GRAY}", TextFormat::DARK_GRAY, $message);
        $message = str_replace("{CYAN}", TextFormat::BLUE, $message);
        $message = str_replace("{GREEN}", TextFormat::GREEN, $message);
        $message = str_replace("{AQUA}", TextFormat::AQUA, $message);
        $message = str_replace("{RED}", TextFormat::RED, $message);
        $message = str_replace("{LIGHT_PURPLE}", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace("{PINK}", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace("{YELLOW}", TextFormat::YELLOW, $message);
        $message = str_replace("{WHITE}", TextFormat::WHITE, $message);
        $message = str_replace("{OBFUSCATED}", TextFormat::OBFUSCATED, $message);
        $message = str_replace("{BOLD}", TextFormat::BOLD, $message);
        $message = str_replace("{STRIKETHROUGH}", TextFormat::STRIKETHROUGH, $message);
        $message = str_replace("{UNDERLINE}", TextFormat::UNDERLINE, $message);
        $message = str_replace("{ITALIC}", TextFormat::ITALIC, $message);
        $message = str_replace("{RESET}", TextFormat::RESET, $message);

        return $message;
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