<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item;


use pocketmine\item\Item;
use pocketmine\utils\CloningRegistryTrait;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\shop\ShopRegistry;
use sergittos\bedwars\item\game\LeaveGameItem;
use sergittos\bedwars\item\game\TrackerShopItem;
use sergittos\bedwars\item\setup\AddGeneratorItem;
use sergittos\bedwars\item\setup\AddVillagerItem;
use sergittos\bedwars\item\setup\CancelItem;
use sergittos\bedwars\item\setup\ClaimingWandItem;
use sergittos\bedwars\item\setup\ConfigurationItem;
use sergittos\bedwars\item\setup\CreateMapItem;
use sergittos\bedwars\item\setup\ExitSetupItem;
use sergittos\bedwars\item\setup\SetBedPositionItem;
use sergittos\bedwars\item\setup\SetTeamGeneratorItem;
use sergittos\bedwars\item\spectator\PlayAgainItem;
use sergittos\bedwars\item\spectator\ReturnToLobbyItem;
use sergittos\bedwars\item\spectator\SpectatorSettingsItem;
use sergittos\bedwars\item\spectator\TeleporterItem;
use function count;

/**
 * @method static Item LEAVE_GAME()
 * @method static Item PLAY_AGAIN()
 * @method static Item RETURN_TO_LOBBY()
 * @method static Item SPECTATOR_SETTINGS()
 * @method static Item TELEPORTER()
 * @method static Item TRACKER_SHOP()
 * @method static Item DIAMOND_GENERATOR()
 * @method static Item EMERALD_GENERATOR()
 * @method static Item ITEM_VILLAGER()
 * @method static Item UPGRADES_VILLAGER()
 * @method static Item CONFIGURATION()
 * @method static Item CREATE_MAP()
 * @method static Item EXIT_SETUP()
 * @method static Item TEAM_GENERATOR()
 * @method static Item BED_POSITION()
 * @method static Item CLAIMING_WAND()
 * @method static Item CANCEL()
 */
class BedwarsItemRegistry {
    use CloningRegistryTrait {
        _registryFromString as fromString;
    }

    /**
     * @return BedwarsItem[]
     */
    static public function getAll(): array {
        return self::_registryGetAll();
    }

    protected static function setup(): void {
        self::register("leave_game", new LeaveGameItem());
        self::register("play_again", new PlayAgainItem());
        self::register("return_to_lobby", new ReturnToLobbyItem());
        self::register("spectator_settings", new SpectatorSettingsItem());
        self::register("teleporter", new TeleporterItem());
        self::register("tracker_shop", new TrackerShopItem());

        self::register("diamond_generator", new AddGeneratorItem(GeneratorType::DIAMOND));
        self::register("emerald_generator", new AddGeneratorItem(GeneratorType::EMERALD));
        self::register("item_villager", new AddVillagerItem(ShopRegistry::ITEM()));
        self::register("upgrades_villager", new AddVillagerItem(ShopRegistry::UPGRADES()));
        self::register("configuration", new ConfigurationItem());
        self::register("create_map", new CreateMapItem());
        self::register("exit_setup", new ExitSetupItem());
        self::register("team_generator", new SetTeamGeneratorItem());
        self::register("bed_position", new SetBedPositionItem());
        self::register("claiming_wand", new ClaimingWandItem());
        self::register("cancel", new CancelItem());
    }

    /**
     * @return BedwarsItem
     */
    static public function get(string $name): object {
        return self::fromString($name);
    }

    static public function _registryFromString(string $name): Item {
        return self::fromString($name)->asItem();
    }

    static private function register(string $name, BedwarsItem $item): void {
        self::_registryRegister($name, $item);
    }

    static public function __callStatic($name, $arguments) {
        if(count($arguments) > 0) {
            throw new \ArgumentCountError("Expected exactly 0 arguments, " . count($arguments) . " passed");
        }

        //fast path
        if(self::$members !== null and isset(self::$members[$name])) {
            return self::preprocessMember(self::$members[$name])->asItem();
        }

        //fallback
        try {
            return self::_registryFromString($name);
        } catch(\InvalidArgumentException $exception) {
            throw new \Error($exception->getMessage(), 0, $exception);
        }
    }

}