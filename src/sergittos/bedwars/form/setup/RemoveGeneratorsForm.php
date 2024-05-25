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
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\session\Session;

class RemoveGeneratorsForm extends SimpleForm {

    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Remove generators");
    }

    protected function onCreation(): void {
        if(!$this->session->isCreatingMap()) {
            return;
        }

        foreach($this->session->getMapSetup()->getMapBuilder()->getGenerators() as $generator) {
            $this->addRedirectFormButton(
                $generator->getName() . " Generator" . ($this->isNearestGenerator($generator) ? "\nNearest Generator" : ""),
                new RemoveGeneratorForm($generator)
            );
        }
    }

    private function isNearestGenerator(Generator $target): bool {
        $found = null;
        $lowest = PHP_INT_MAX;
        foreach($this->session->getMapSetup()->getMapBuilder()->getGenerators() as $generator) {
            $distance = $generator->getPosition()->distance($this->session->getPlayer()->getPosition());
            if($distance < $lowest) {
                $lowest = $distance;
                $found = $generator;
            }
        }

        return $found === $target;
    }

}