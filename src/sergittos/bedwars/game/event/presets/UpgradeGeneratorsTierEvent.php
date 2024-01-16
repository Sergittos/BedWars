<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\event\presets;


use sergittos\bedwars\game\event\Event;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\generator\presets\TextGenerator;
use sergittos\bedwars\game\generator\Tier;
use sergittos\bedwars\utils\GameUtils;

class UpgradeGeneratorsTierEvent extends Event {

    private GeneratorType $type;
    private Tier $tier;

    public function __construct(GeneratorType $type, Tier $tier) {
        $this->type = $type;
        $this->tier = $tier;
        parent::__construct($type->toString() . " " . $tier->name, 6);
    }

    public function end(): void {
        foreach($this->game->getGenerators() as $generator) {
            if($generator->getType() !== $this->type) {
                continue;
            }

            $generator->setTier($this->tier);

            if($generator instanceof TextGenerator) {
                $generator->updateText($this->game->getWorld());
            }
        }
        $this->game->broadcastMessage(GameUtils::getGeneratorColor($name = $this->type->toString()) . $name . " Generators {YELLOW}have been upgraded to Tier {RED}" . $this->tier->name);
    }

    public function getNextEvent(): ?Event {
        $next_type = match($this->type) {
            GeneratorType::DIAMOND => GeneratorType::EMERALD,
            GeneratorType::EMERALD => GeneratorType::DIAMOND
        };

        $next_tier = $this->getNextTier($next_type);

        if($next_tier === null) {
            return new BedDestructionEvent();
        }

        return new UpgradeGeneratorsTierEvent($next_type, $next_tier);
    }

    private function getNextTier(GeneratorType $type): ?Tier {
        if($type === GeneratorType::DIAMOND) {
            return match($this->tier) {
                Tier::I => Tier::II,
                Tier::II => Tier::III,
                Tier::III => null
            };
        }

        return $this->tier;
    }

}