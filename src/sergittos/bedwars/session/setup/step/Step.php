<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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

    public function onBlockInteract(Vector3 $touchVector, int $action, Cancellable $event, BedwarsItem $item): void {}

    public function onInteract(BedwarsItem $item): void {}



}