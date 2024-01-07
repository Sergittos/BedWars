<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\generator;


use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\utils\ColorUtils;
use sergittos\bedwars\utils\GameUtils;

abstract class Generator {

    protected Vector3 $position;
    protected Tier $tier;

    protected int $speed;
    protected int $countdown = 0;
    protected int $time = 0;

    public function __construct(Vector3 $position) {
        $this->position = $position;
        $this->tier = Tier::I;
        $this->setSpeed($this->getInitialSpeed());
    }

    public function getName(): string {
        $name = $this->getType()->toString();
        return ColorUtils::translate(GameUtils::getGeneratorColor($name) . $name);
    }

    public function getPosition(): Vector3 {
        return $this->position;
    }

    public function getTier(): Tier {
        return $this->tier;
    }

    public function getSpeed(): int {
        return $this->speed;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function setTier(Tier $tier): void {
        $this->tier = $tier;
    }

    public function setSpeed(int $speed): void {
        $this->speed = $speed;
        $this->setCountdown(1 / $speed);
    }

    public function setCountdown(float $countdown): void { // drops per second
        $this->countdown = (int) (20 / $countdown);
        $this->resetTime();
    }

    private function resetTime(): void {
        $this->time = $this->countdown;
    }

    public function tick(Game $game): void {
        $this->time--;
        if($this->time <= 0) {
            $this->resetTime();
            $this->dropItem($game->getWorld());
        }
    }

    private function dropItem(World $world): void {
        $entity = $world->dropItem($this->position->add(0, 0.1, 0), clone $this->getItem(), Vector3::zero());
        if($entity !== null) {
            $entity->setOwner("generator");

            $this->onDropItem($world);
        }
    }

    public function reset(): void {
        $this->tier = Tier::I;
        $this->setSpeed($this->getInitialSpeed());
    }

    public function onDropItem(World $world): void {}

    abstract public function getType(): GeneratorType;

    abstract public function getInitialSpeed(): int;

    abstract protected function getItem(): Item;

}