<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\queue\element;


use EasyUI\element\Button;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\session\SessionFactory;

class PlayGameButton extends Button {

    public function __construct(string $text, ?Game $game) {
        parent::__construct($text, null, function(Player $player) use ($game) {
            if($game === null) {
                $player->sendMessage(TextFormat::RED . "There are no games for this map! Try again in a few seconds");
                return;
            }

            $session = SessionFactory::getSession($player);
            if($session->isSpectator()) {
                $session->getGame()->removeSpectator($session);
            } elseif($session->isPlaying()) {
                return;
            }
            $game->addPlayer($session);
        });
    }

}