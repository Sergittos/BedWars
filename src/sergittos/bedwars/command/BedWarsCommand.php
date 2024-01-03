<?php

declare(strict_types=1);


namespace sergittos\bedwars\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\form\setup\BedwarsForm;
use sergittos\bedwars\form\setup\SetupMapForm;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\session\setup\step\PreparingMapStep;

class BedWarsCommand extends Command implements PluginOwned {

    public function __construct() {
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
        parent::__construct("bedwars", "Setup your BedWars games!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof Player) {
            return;
        }

        $session = SessionFactory::getSession($sender);
        if($session->isCreatingMap()) {
            if($session->getMapSetup()->getStep() instanceof PreparingMapStep) {
                $sender->sendForm(new SetupMapForm($session));
            }
        } else {
            $sender->sendForm(new BedwarsForm());
        }
    }

    public function getOwningPlugin(): Plugin {
        return BedWars::getInstance();
    }

}