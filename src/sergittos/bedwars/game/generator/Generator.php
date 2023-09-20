<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\generator;


use pocketmine\item\Item;
use pocketmine\math\Vector3;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\utils\ColorUtils;
use sergittos\bedwars\utils\GameUtils;
use function ucfirst;

class Generator {

    public const IRON = "iron";
    public const GOLD = "gold";
    public const DIAMOND = "diamond";
    public const EMERALD = "emerald";

    private string $id;
    private string $name;
    private string $tier = TierIds::I;

    private int $initial_speed;
    private int $speed;
    private int $countdown = 0;
    private int $time = 0;

    private Vector3 $position;
    private Item $item;

    private ?GeneratorText $text = null;

    public function __construct(string $id, string $name, int $speed, Vector3 $position, Item $item, bool $spawn_text = true) {
        $this->id = $id;
        $this->name = ColorUtils::translate($name);
        $this->initial_speed = $speed;
        $this->setSpeed($speed);
        $this->position = $position;
        $this->item = $item;

        if($spawn_text) {
            $this->text = new GeneratorText($this);
        }
    }

    static public function fromData(string $id, string $name, int $speed, Item $item, bool $spawn_text, array $data): Generator {
        return new Generator(
            $id, GameUtils::getGeneratorColor($name) . ucfirst($name), $speed, new Vector3($data["x"] + 0.5, $data["y"], $data["z"] + 0.5), $item, $spawn_text
        );
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPosition(): Vector3 {
        return $this->position;
    }

    public function getTier(): string {
        return $this->tier;
    }

    public function getInitialSpeed(): int {
        return $this->initial_speed;
    }

    public function getTime(): int {
        return $this->time;
    }

    /**
     * @return Item
     */
    public function getItem(): Item {
        return $this->item;
    }

    public function getText(): ?GeneratorText {
        return $this->text;
    }

    public function setTier(string $tier): void {
        $this->tier = $tier;

        if($this->text !== null) {
            $this->setSpeed($this->speed - 15);
        }
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
        $world = $game->getWorld();

        $this->time--;
        if($this->time <= 0) {
            $this->resetTime();

            $entity = $world->dropItem($this->position->add(0, 0.1, 0), clone $this->item, Vector3::zero());
            if($this->text === null) {
                $entity->setOwner("generator");
                return;
            }

            $this->text->update($this, $world);
        } elseif($this->time % 20 === 0) {
            $this->text?->update($this, $world);
        }
    }

    public function __clone(): void {
        if($this->text !== null) {
            $this->text = clone $this->text;
        }
    }

}