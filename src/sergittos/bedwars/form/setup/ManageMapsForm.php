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
use sergittos\bedwars\game\map\MapFactory;

class ManageMapsForm extends SimpleForm {

    public function __construct() {
        parent::__construct("Manage maps");
    }

    protected function onCreation(): void {
        foreach(MapFactory::getMaps() as $map) {
            $this->addRedirectFormButton($map->getName(), new MapOptionsForm($map));
        }
    }

}