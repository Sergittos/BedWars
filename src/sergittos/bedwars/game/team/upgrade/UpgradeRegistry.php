<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade;


use pocketmine\utils\CloningRegistryTrait;
use sergittos\bedwars\game\team\upgrade\presets\ArmorProtection;
use sergittos\bedwars\game\team\upgrade\presets\HealPool;
use sergittos\bedwars\game\team\upgrade\presets\IronForge;
use sergittos\bedwars\game\team\upgrade\presets\ManiacMiner;
use sergittos\bedwars\game\team\upgrade\presets\SharpenedSwords;

/**
 * @method static ArmorProtection ARMOR_PROTECTION()
 * @method static ArmorProtection HEAL_POOL()
 * @method static ArmorProtection IRON_FORGE()
 * @method static ArmorProtection MANIAC_MINER()
 * @method static ArmorProtection SHARPENED_SWORDS()
 */
class UpgradeRegistry {
    use CloningRegistryTrait;

    /**
     * @return Upgrade[]
     */
    static public function getAll(): array {
        return self::_registryGetAll();
    }

    static protected function setup(): void {
        self::register("armor_protection", new ArmorProtection());
        self::register("heal_pool", new HealPool());
        self::register("iron_forge", new IronForge());
        self::register("maniac_miner", new ManiacMiner());
        self::register("sharpened_swords", new SharpenedSwords());
    }

    static public function register(string $name, Upgrade $upgrade): void {
        self::_registryRegister($name, $upgrade);
    }

}