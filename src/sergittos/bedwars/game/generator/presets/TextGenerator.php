<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\generator\presets;


use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\generator\GeneratorText;
use sergittos\bedwars\game\generator\Tier;

abstract class TextGenerator extends Generator {

    protected GeneratorText $text;

    public function __construct(Vector3 $position) {
        parent::__construct($position);

        $this->text = new GeneratorText();
    }

    public function getText(): GeneratorText {
        return $this->text;
    }

    public function setTier(Tier $tier): void {
        parent::setTier($tier);

        $this->setSpeed($this->speed - 15);
    }

    public function tick(Game $game): void {
        parent::tick($game);

        if($this->time % 20 === 0) {
            $this->updateText($game->getWorld());
        }
    }

    public function onDropItem(World $world): void {
        $this->updateText($world);
    }

    public function updateText(World $world): void {
        $this->text->update($this, $world);
    }

    public function __clone(): void {
        $this->text = clone $this->text;
    }

}