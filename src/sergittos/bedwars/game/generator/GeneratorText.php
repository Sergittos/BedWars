<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\generator;


use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\World;
use sergittos\bedwars\session\Session;

class GeneratorText {

    /** @var FloatingTextParticle[] */
    private array $particles;

    public function __construct() {
        $this->particles[0] = new FloatingTextParticle("");
        $this->particles[1] = new FloatingTextParticle("");
        $this->particles[2] = new FloatingTextParticle("");
    }

    public function update(Generator $generator, World $world): void {
        $this->particles[0]->setText(TextFormat::YELLOW . "Tier " . TextFormat::RED . $generator->getTier()->name);
        $this->particles[1]->setText($generator->getName());
        $this->particles[2]->setText(TextFormat::YELLOW . "Spawns in " . TextFormat::RED . ($time = ((int) ($generator->getTime() / 20))) . TextFormat::YELLOW . ($time === 1 ? " second" : " seconds"));

        foreach($this->particles as $index => $line) {
            $world->addParticle($generator->getPosition()->add(0, (3 - $index) / 2 + 1.25, 0), $line);
        }
    }

    public function despawnFrom(Session $session): void {
        foreach($this->particles as $line) {
            $line->setInvisible();
            foreach($line->encode(Vector3::zero()) as $packet) {
                $session->sendDataPacket($packet);
            }
            $line->setInvisible(false);
        }
    }

}