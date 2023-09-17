<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\form\queue;


use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\session\SessionFactory;

class SelectMapForm extends SimpleForm {

    private int $players_per_team;

    public function __construct(int $players_per_team) {
        $this->players_per_team = $players_per_team;
        parent::__construct("Select a map!");
    }

    protected function onCreation(): void {
        foreach(MapFactory::getMapsByPlayers($this->players_per_team) as $arena) {
            $button = new Button($arena->getName());
            $button->setSubmitListener(function(Player $player) use ($arena) {
                $game = BedWars::getInstance()->getGameManager()->findGame($arena);
                if($game !== null) {
                    $session = SessionFactory::getSession($player);
                    if($session->isSpectator()) {
                        $session->getGame()->removeSpectator($session);
                    }
                    $game->addPlayer($session);
                } else {
                    $player->sendMessage(TextFormat::RED . "There are no games for this map! Try again in a few minutes");
                }
            });
            $this->addButton($button);
        }
    }

}