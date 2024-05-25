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
use sergittos\bedwars\session\Session;

class SetupGeneratorsForm extends SimpleForm {

    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Setup generators");
    }

    protected function onCreation(): void {
        $this->addRedirectFormButton("Add generator", new AddGeneratorForm($this->session));
        $this->addRedirectFormButton("Remove generator", new RemoveGeneratorsForm($this->session));
    }

}