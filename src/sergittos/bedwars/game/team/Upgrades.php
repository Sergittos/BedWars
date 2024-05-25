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
use sergittos\bedwars\game\team\upgrade\presets\ArmorProtection;
use sergittos\bedwars\game\team\upgrade\presets\HealPool;
use sergittos\bedwars\game\team\upgrade\presets\IronForge;
use sergittos\bedwars\game\team\upgrade\presets\ManiacMiner;
use sergittos\bedwars\game\team\upgrade\presets\SharpenedSwords;
use sergittos\bedwars\game\team\upgrade\trap\Trap;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\session\Session;
use function array_key_exists;
use function array_shift;
use function count;
use function time;

class Upgrades {

    private ArmorProtection $armorProtection;
    private ManiacMiner $maniacMiner;
    private IronForge $ironForge;
    private SharpenedSwords $sharpenedSwords;
    private HealPool $healPool;

    private int $trapTriggerTime = 0;

    /** @var Trap[] */
    private array $traps = [];

    public function __construct() {
        $this->armorProtection = new ArmorProtection();
        $this->maniacMiner = new ManiacMiner();
        $this->ironForge = new IronForge();
        $this->sharpenedSwords = new SharpenedSwords();
        $this->healPool = new HealPool();
    }

    /**
     * @return Upgrade[]
     */
    public function getAll(): array {
        return [$this->armorProtection, $this->maniacMiner, $this->ironForge, $this->sharpenedSwords, $this->healPool];
    }

    public function getArmorProtection(): ArmorProtection {
        return $this->armorProtection;
    }

    public function getManiacMiner(): ManiacMiner {
        return $this->maniacMiner;
    }

    public function getIronForge(): IronForge {
        return $this->ironForge;
    }

    public function getSharpenedSwords(): SharpenedSwords {
        return $this->sharpenedSwords;
    }

    public function getHealPool(): HealPool {
        return $this->healPool;
    }

    public function getUpgrade(string $name): ?Upgrade {
        foreach($this->getAll() as $upgrade) {
            if($upgrade->getName() === $name) {
                return $upgrade;
            }
        }
        return null;
    }

    public function canTriggerTrap(): bool {
        if(time() - $this->trapTriggerTime >= 30 and !empty($this->traps)) {
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