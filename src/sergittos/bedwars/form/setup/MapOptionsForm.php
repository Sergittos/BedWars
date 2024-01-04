<?php

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