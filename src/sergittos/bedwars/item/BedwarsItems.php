<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\item;


use pocketmine\item\Item;
use pocketmine\utils\CloningRegistryTrait;
use sergittos\bedwars\item\game\LeaveGameItem;
use sergittos\bedwars\item\game\TrackerShop;
use sergittos\bedwars\item\spectator\PlayAgainItem;
use sergittos\bedwars\item\spectator\ReturnToLobbyItem;
use sergittos\bedwars\item\spectator\SpectatorSettingsItem;
use sergittos\bedwars\item\spectator\TeleporterItem;

/**
 * @method static LeaveGameItem LEAVE_GAME()
 * @method static PlayAgainItem PLAY_AGAIN()
 * @method static ReturnToLobbyItem RETURN_TO_LOBBY()
 * @method static SpectatorSettingsItem SPECTATOR_SETTINGS()
 * @method static TeleporterItem TELEPORTER()
 * @method static TrackerShop TRACKER_SHOP()
 */
class BedwarsItems {
    use CloningRegistryTrait;

    protected static function setup(): void {
        self::register("leave_game", new LeaveGameItem());
        self::register("play_again", new PlayAgainItem());
        self::register("return_to_lobby", new ReturnToLobbyItem());
        self::register("spectator_settings", new SpectatorSettingsItem());
        self::register("teleporter", new TeleporterItem());
        self::register("tracker_shop", new TrackerShop());
    }

    /**
     * @return BedwarsItem[]
     */
    static public function getAll(): array {
        return self::_registryGetAll();
    }

    /**
     * @return Item
     */
    static public function get(string $name): object {
        return self::_registryFromString($name);
    }

    static private function register(string $name, BedwarsItem $item) : void{
        self::_registryRegister($name, $item);
    }

}