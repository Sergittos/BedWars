<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\queue\element;


use EasyUI\element\Button;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\game\map\Mode;
use sergittos\bedwars\session\SessionFactory;

class PlayGameButton extends Button {

    public function __construct(string $name, ?Map $map, Mode $mode) {
        parent::__construct($name, null, function(Player $player) use ($map, $mode) {
            $map = $map !== null ? $map : MapFactory::getRandomMap($mode);
            if($map === null) {
                $player->sendMessage(TextFormat::RED . "There are no maps available at this moment, try again in a few minutes");
                return;
            }

            BedWars::getInstance()->getGameManager()->findGame($map, SessionFactory::getSession($player));
        });
    }

}