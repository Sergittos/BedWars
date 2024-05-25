<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\provider;


use sergittos\bedwars\session\Session;

abstract class Provider {

    abstract public function loadSession(Session $session): void;

    abstract public function updateCoins(Session $session): void;

    abstract public function updateKills(Session $session): void;

    abstract public function updateWins(Session $session): void;

    abstract public function saveSession(Session $session): void;

}