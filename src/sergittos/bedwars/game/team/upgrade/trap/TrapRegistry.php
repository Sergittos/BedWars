<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\trap;


use pocketmine\utils\RegistryTrait;
use sergittos\bedwars\game\team\upgrade\trap\presets\Alarm;
use sergittos\bedwars\game\team\upgrade\trap\presets\CounterOffensive;
use sergittos\bedwars\game\team\upgrade\trap\presets\ItsATrap;
use sergittos\bedwars\game\team\upgrade\trap\presets\MinerFatigue;

/**
 * @method static Alarm ALARM()
 * @method static CounterOffensive COUNTER_OFFENSIVE()
 * @method static ItsATrap ITS_A_TRAP()
 * @method static MinerFatigue MINER_FATIGUE()
 */
class TrapRegistry {
    use RegistryTrait;

    /**
     * @return Trap[]
     */
    static public function getAll(): array {
        return self::_registryGetAll();
    }

    static protected function setup(): void {
        self::register("alarm", new Alarm());
        self::register("counter_offensive", new CounterOffensive());
        self::register("its_a_trap", new ItsATrap());
        self::register("miner_fatigue", new MinerFatigue());
    }

    static public function register(string $name, Trap $trap): void {
        self::_registryRegister($name, $trap);
    }

}