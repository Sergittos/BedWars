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

class BedwarsForm extends SimpleForm {

    public function __construct() {
        parent::__construct("BedWars");
    }

    protected function onCreation(): void {
        $this->addRedirectFormButton("Create a map", new CreateMapForm());
        $this->addRedirectFormButton("Manage maps", new ManageMapsForm());
        $this->addRedirectFormButton("Spawn join game entity", new SpawnEntityForm());
    }

}