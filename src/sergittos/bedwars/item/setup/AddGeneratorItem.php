<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;
use function strtolower;

class AddGeneratorItem extends SetupItem {

    private GeneratorType $type;

    public function __construct(GeneratorType $type) {
        $this->type = $type;
        parent::__construct(GameUtils::getGeneratorColor($name = $type->toString()) . $name . " generator");
    }

    protected function realItem(): Item {
        return StringToItemParser::getInstance()->parse(strtolower($this->type->toString()) . "_block");
    }

    public function onInteract(Session $session): void {}

}