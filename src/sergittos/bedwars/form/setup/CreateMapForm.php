<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\Dropdown;
use EasyUI\element\Input;
use EasyUI\element\Option;
use EasyUI\element\Slider;
use EasyUI\utils\FormResponse;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\form\CustomForm;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\session\setup\builder\MapBuilder;
use sergittos\bedwars\session\setup\MapSetup;
use Symfony\Component\Filesystem\Path;

class CreateMapForm extends CustomForm {

    public function __construct() {
        parent::__construct("Create a map");
    }

    protected function onCreation(): void {
        $this->addElement("name", new Input("Set a name:"));
        $this->addElement("teams", new Slider("Set the teams", 2, 8, 8, 1));
        $this->addWorldsDropdown("waiting_world", "Set the waiting world:");
        $this->addWorldsDropdown("playing_world", "Set the playing world:");
        $this->addSelectModeDropdown();
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $name = $response->getInputSubmittedText("name");
        $waiting_world = $response->getDropdownSubmittedOptionId("waiting_world");
        $playing_world = $response->getDropdownSubmittedOptionId("playing_world");
        $teams = $response->getSliderSubmittedStep("teams");
        $players_per_team = (int) $response->getDropdownSubmittedOptionId("players_per_team");

        if($name === "") {
            $player->sendMessage(TextFormat::RED . "You must set a name!");
            return;
        } elseif(MapFactory::getMapByName($name) !== null) {
            $player->sendMessage(TextFormat::RED . "A map with that name already exists!");
            return;
        } elseif($this->checkIfDefaultWorld($waiting_world) or $this->checkIfDefaultWorld($playing_world)) {
            $player->sendMessage(TextFormat::RED ."You world must not be the default world!");
            return;
        } elseif($waiting_world === $playing_world) {
            $player->sendMessage(TextFormat::RED . "Your waiting world cannot be the same as the playing world!");
            return;
        }

        $world_manager = Server::getInstance()->getWorldManager();
        if(!$world_manager->loadWorld($playing_world)) {
            $player->sendMessage(TextFormat::RED . "Couldn't teleport to playing world ($playing_world) because the world has been unloaded.");
            return;
        } elseif(!$world_manager->loadWorld($waiting_world)) {
            $player->sendMessage(TextFormat::RED . "Couldn't load waiting world ($waiting_world)");
            return;
        }

        $player->setGamemode(GameMode::CREATIVE());
        $player->teleport($world_manager->getWorldByName($playing_world)->getSafeSpawn());

        $session = SessionFactory::getSession($player);
        $session->setMapSetup(new MapSetup($session, new MapBuilder(
            $name, $world_manager->getWorldByName($waiting_world), $playing_world, $players_per_team, (int) ($teams * $players_per_team)
        )));
    }

    private function addWorldsDropdown(string $id, string $name): void {
        $dropdown = new Dropdown($name);
        foreach(glob($this->getWorldsPath(), GLOB_ONLYDIR) as $world) {
            $dropdown->addOption(new Option($name = basename($world), $name));
        }
        $this->addElement($id, $dropdown);
    }

    private function checkIfDefaultWorld(string $world): bool {
        return Server::getInstance()->getWorldManager()->getDefaultWorld()->getFolderName() === $world;
    }

    private function getWorldsPath(): string {
        return Path::join(Server::getInstance()->getDataPath(), "worlds", "*");
    }

}