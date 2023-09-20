<?php

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
use function count;

class Upgrades {

    private ArmorProtection $armor_protection;
    private ManiacMiner $maniac_miner;
    private IronForge $iron_forge;
    private SharpenedSwords $sharpened_swords;
    private HealPool $heal_pool;

    /** @var Trap[] */
    private array $traps = [];

    public function __construct() {
        $this->armor_protection = new ArmorProtection();
        $this->maniac_miner = new ManiacMiner();
        $this->iron_forge = new IronForge();
        $this->sharpened_swords = new SharpenedSwords();
        $this->heal_pool = new HealPool();
    }

    /**
     * @return Upgrade[]
     */
    public function getAll(): array {
        return [$this->armor_protection, $this->maniac_miner, $this->iron_forge, $this->sharpened_swords, $this->heal_pool];
    }

    public function getArmorProtection(): ArmorProtection {
        return $this->armor_protection;
    }

    public function getManiacMiner(): ManiacMiner {
        return $this->maniac_miner;
    }

    public function getIronForge(): IronForge {
        return $this->iron_forge;
    }

    public function getSharpenedSwords(): SharpenedSwords {
        return $this->sharpened_swords;
    }

    public function getHealPool(): HealPool {
        return $this->heal_pool;
    }

    public function getUpgrade(string $name): ?Upgrade {
        foreach($this->getAll() as $upgrade) {
            if($upgrade->getName() === $name) {
                return $upgrade;
            }
        }
        return null;
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

    public function triggerTraps(Session $session, Team $team): void {
        foreach($this->traps as $trap) {
            $trap->trigger($session, $team);
        }
        $this->traps = [];
    }

    public function __clone(): void {
        $this->traps = Utils::cloneObjectArray($this->traps);
    }

}