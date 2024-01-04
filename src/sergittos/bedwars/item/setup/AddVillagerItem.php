<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\session\Session;

class AddVillagerItem extends SetupItem {

    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
        parent::__construct($name . " villager");
    }

    public function getName(): string {
        return $this->name;
    }

    public function onInteract(Session $session): void {}

    protected function realItem(): Item {
        return VanillaItems::VILLAGER_SPAWN_EGG();
    }

}