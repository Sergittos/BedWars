<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\queue;


use EasyUI\variant\SimpleForm;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\form\queue\element\PlayGameButton;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\game\map\Mode;

class SelectMapForm extends SimpleForm {

    private Mode $mode;

    public function __construct(Mode $mode) {
        $this->mode = $mode;
        parent::__construct("Select a map!");
    }

    protected function onCreation(): void {
        foreach(MapFactory::getMapsByPlayers($this->mode) as $map) {
            $this->addButton(new PlayGameButton($map->getName(), $map, $this->mode));
        }
    }

}