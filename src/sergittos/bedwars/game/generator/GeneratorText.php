<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\generator;


use pocketmine\utils\TextFormat;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\World;

class GeneratorText {

    private Generator $generator;

    /** @var FloatingTextParticle[] */
    private array $particles;

    public function __construct(Generator $generator) {
        $this->generator = $generator;

        $this->particles[0] = new FloatingTextParticle("");
        $this->particles[1] = new FloatingTextParticle("");
        $this->particles[2] = new FloatingTextParticle("");
    }

    public function update(World $world): void {
        $this->particles[0]->setText(TextFormat::YELLOW . "Tier " . TextFormat::RED . $this->generator->getTier());
        $this->particles[1]->setText($this->generator->getName());
        $this->particles[2]->setText(TextFormat::YELLOW . "Spawns in " . TextFormat::RED . ($time = ((int) ($this->generator->getTime() / 20))) . TextFormat::YELLOW . ($time === 1 ? " second" : " seconds"));

        foreach($this->particles as $index => $line) {
            $world->addParticle($this->generator->getPosition()->add(0, (3 - $index) / 2 + 1.25, 0), $line);
        }
    }

}