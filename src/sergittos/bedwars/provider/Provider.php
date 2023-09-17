<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider;


use sergittos\bedwars\session\Session;

abstract class Provider {

    abstract public function loadSession(Session $session): void;

    abstract public function saveSession(Session $session): void;

}