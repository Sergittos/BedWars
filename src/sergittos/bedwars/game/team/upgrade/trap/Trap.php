<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\trap;


use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;

abstract class Trap {

    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    abstract public function trigger(Session $session, Team $team): void;

}