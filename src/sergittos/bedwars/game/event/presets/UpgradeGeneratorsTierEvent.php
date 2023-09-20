<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\event\presets;


use sergittos\bedwars\game\event\Event;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\utils\GameUtils;
use function ucfirst;

class UpgradeGeneratorsTierEvent extends Event {

    private string $id;
    private string $tier;

    public function __construct(string $id, string $tier) {
        $this->id = $id;
        $this->tier = $tier;
        parent::__construct(ucfirst($id) . " " . $tier, 6);
    }

    public function end(): void {
        foreach($this->game->getGenerators() as $generator) {
            if($generator->getId() === $this->id) {
                $generator->setTier($this->tier);
                $generator->getText()?->update($generator, $this->game->getWorld());
            }
        }
        $this->game->broadcastMessage(GameUtils::getGeneratorColor(ucfirst($this->id)) . ucfirst($this->id) . " Generators {YELLOW}have been upgraded to Tier {RED}" . $this->tier);
    }

    public function getNextEvent(): ?Event {
        $name = match($this->id) {
            Generator::DIAMOND => Generator::EMERALD,
            Generator::EMERALD => Generator::DIAMOND
        };
        if($name === Generator::DIAMOND) {
            $this->tier .= "I";
        }

        if($this->tier === "IIII") {
            return new BedDestructionEvent();
        }
        return new UpgradeGeneratorsTierEvent($name, $this->tier);
    }

}