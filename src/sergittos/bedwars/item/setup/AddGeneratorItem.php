<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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

    protected function getMaterial(): Item {
        return StringToItemParser::getInstance()->parse(strtolower($this->type->toString()) . "_block");
    }

    public function onInteract(Session $session): void {}

}