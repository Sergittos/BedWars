<?php

declare(strict_types=1);


namespace sergittos\bedwars\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use sergittos\bedwars\form\setup\BedwarsForm;
use sergittos\bedwars\form\setup\SetupMapForm;
use sergittos\bedwars\session\SessionFactory;

class BedWarsCommand extends Command {

    public function __construct() {
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
        parent::__construct("bedwars", "Setup your BedWars games!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof Player) {
            return;
        }

        if(($session = SessionFactory::getSession($sender))->isCreatingMap()) {
            $sender->sendForm(new SetupMapForm($session));
        } else {
            $sender->sendForm(new BedwarsForm());
        }
    }

}