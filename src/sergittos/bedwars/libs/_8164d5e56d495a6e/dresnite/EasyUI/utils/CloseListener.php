<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\utils;


use Closure;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

trait CloseListener {

    private ?Closure $closeListener = null;

    public function getCloseListener(): ?Closure {
        return $this->closeListener;
    }

    public function setCloseListener(?Closure $listener): void {
        if($listener !== null) {
            Utils::validateCallableSignature(function(Player $player) {}, $listener);
        }
        $this->closeListener = $listener;
    }

    public function executeCloseListener(Player $player): void {
        if($this->closeListener !== null) {
            ($this->closeListener)($player);
        }
    }

}