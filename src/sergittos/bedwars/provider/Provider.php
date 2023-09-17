<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\provider;


use sergittos\bedwars\session\Session;

abstract class Provider {

    abstract public function loadSession(Session $session): void;

    abstract public function saveSession(Session $session): void;

}