<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team;


use pocketmine\utils\Utils;
use sergittos\bedwars\game\team\upgrade\trap\Trap;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\game\team\upgrade\UpgradeRegistry;
use sergittos\bedwars\session\Session;
use function array_key_exists;
use function array_shift;
use function count;
use function time;

class Upgrades {

    private int $trapTriggerTime = 0;

    /** @var Upgrade[] */
    private array $upgrades = [];

    /** @var Trap[] */
    private array $traps = [];

    public function __construct() {
        foreach(UpgradeRegistry::getAll() as $upgrade) {
            $this->upgrades[$upgrade->getId()] = $upgrade;
        }
    }

    /**
     * @return Upgrade[]
     */
    public function getAll(): array {
        return $this->upgrades;
    }

    public function get(string $id): ?Upgrade {
        return $this->upgrades[$id] ?? null;
    }

    public function canTriggerTrap(): bool {
        if(time() - $this->trapTriggerTime >= 30 and count($this->traps) !== 0) {
            $this->trapTriggerTime = time();
            return true;
        }
        return false;
    }

    /**
     * @return Trap[]
     */
    public function getTraps(): array {
        return $this->traps;
    }

    public function getTrapsCount(): int {
        return count($this->traps);
    }

    public function hasTrap(string $name): bool {
        return array_key_exists($name, $this->traps);
    }

    public function addTrap(Trap $trap): void {
        $this->traps[$trap->getName()] = $trap;
    }

    public function triggerPrimaryTrap(Session $session, Team $team): void {
        $trap = array_shift($this->traps);
        $trap->trigger($session, $team);

        $team->notifyTrap($trap, $session->getTeam());
    }

    public function __clone(): void {
        $this->traps = Utils::cloneObjectArray($this->traps);
    }

}