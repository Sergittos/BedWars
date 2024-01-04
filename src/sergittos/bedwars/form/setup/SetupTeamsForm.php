<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use sergittos\bedwars\form\SimpleForm;
use sergittos\bedwars\session\Session;

class SetupTeamsForm extends SimpleForm {

    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Setup teams", "Select the team you want to setup");
    }

    protected function onCreation(): void {
        foreach($this->session->getMapSetup()->getMapBuilder()->getTeams() as $team) {
            $description = !$team->canBeBuilt() ? "\n[PENDING]" : "";
            $this->addRedirectFormButton($team->getName() . $description, new TeamOptionsForm($this->session, $team));
        }
    }

}