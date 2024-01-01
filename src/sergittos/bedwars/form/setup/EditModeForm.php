<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use sergittos\bedwars\form\SimpleForm;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\MapFactory;
use function count;

class EditModeForm extends SimpleForm {

    private Map $map;

    public function __construct(Map $map) {
        $this->map = $map;
        parent::__construct("Edit mode");
    }

    protected function onCreation(): void {
        $count = count(MapFactory::getMapsByName($this->map->getName()));
        if($count < 4) {
            $this->addRedirectFormButton("Add mode", new AddModeForm($this->map));
        }
        if($count > 1) {
            $this->addRedirectFormButton("Remove mode", new RemoveModeForm($this->map));
        }
    }

}