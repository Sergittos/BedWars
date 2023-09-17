<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\queue;


use EasyUI\element\Button;
use EasyUI\Form;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\utils\ColorUtils;
use sergittos\bedwars\utils\GameUtils;

class PlayBedwarsForm extends SimpleForm {

    private int $players_per_team;

    public function __construct(int $players_per_team) {
        $this->players_per_team = $players_per_team;
        parent::__construct("Play bedwars " . GameUtils::getMode($players_per_team));
    }

    protected function onCreation(): void {
        $this->addRandomMapButton();
        $this->addRedirectFormButton("Select a map", new SelectMapForm($this->players_per_team));
    }

    private function addRandomMapButton(): void {
        $button = new Button("Random map");
        $button->setSubmitListener(function(Player $player) {
            $game = BedWars::getInstance()->getGameManager()->findRandomGame($this->players_per_team);
            if($game !== null) {
                $session = SessionFactory::getSession($player);
                if($session->isSpectator()) {
                    $session->getGame()->removeSpectator($session);
                }
                $game->addPlayer($session);
            } else {
                $player->sendMessage(TextFormat::RED . "There are no games for this map! Try again in a few seconds");
            }
        });
        $this->addButton($button);
    }

    private function addRedirectFormButton(string $name, Form $form): void {
        $button = new Button(ColorUtils::translate($name));
        $button->setSubmitListener(function(Player $player) use ($form) {
            $player->sendForm($form);
        });
        $this->addButton($button);
    }

}