<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard\layout;


use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\message\MessageContainer;

interface Layout {

    public function getMessageContainer(Session $session): MessageContainer;

}