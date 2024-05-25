<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use sergittos\bedwars\form\SimpleForm;
use sergittos\bedwars\game\map\Map;

class MapOptionsForm extends SimpleForm {

    private Map $map;

    public function __construct(Map $map) {
        $this->map = $map;
        parent::__construct("Manage " . $map->getName() . " map", "What do you want to do?");
    }

    protected function onCreation(): void {
        $this->addRedirectFormButton("Delete", new RemoveMapForm($this->map));
    }

}