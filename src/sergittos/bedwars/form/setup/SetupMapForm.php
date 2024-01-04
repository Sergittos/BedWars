<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\Button;
use pocketmine\player\Player;
use sergittos\bedwars\form\SimpleForm;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\setup\step\SetShopAndUpgradesStep;

class SetupMapForm extends SimpleForm {

    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Setup map", "What do you want to do?");
    }

    protected function onCreation(): void {
        $this->addSetSpectatorSpawnButton();
        $this->addShopAndUpgradesButton();
        $this->addRedirectFormButton("Setup generators", new SetupGeneratorsForm($this->session));
        $this->addRedirectFormButton("Setup teams", new SetupTeamsForm($this->session));
    }

    private function addSetSpectatorSpawnButton(): void {
        $button = new Button("Set spectator spawn point");
        $button->setSubmitListener(function(Player $player) {
            if($this->session->isCreatingMap()) {
                $position = $player->getPosition()->asVector3();
                $this->session->getMapSetup()->getMapBuilder()->setSpectatorSpawnPosition($position);
                $this->session->message("{GREEN}Spectator spawn point set on: " . $this->vectorToString($position));
            }
        });
        $this->addButton($button);
    }

    private function addShopAndUpgradesButton(): void {
        $button = new Button("Set Shop & Upgrades");
        $button->setSubmitListener(function(Player $player) {
            $this->session->getMapSetup()->setStep(new SetShopAndUpgradesStep());
        });
        $this->addButton($button);
    }

}