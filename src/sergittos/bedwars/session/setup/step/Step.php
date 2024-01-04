<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\session\Session;

abstract class Step {

    protected Session $session;

    public function start(Session $session): void {
        $this->session = $session;
        $this->onStart();
    }

    abstract protected function onStart(): void;

    public function onBlockBreak(Block $block, BedwarsItem $item): void {}

    public function onBlockInteract(Vector3 $touch_vector, int $action, Cancellable $event, BedwarsItem $item): void {}

    public function onInteract(BedwarsItem $item): void {}



}