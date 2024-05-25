<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\generator;


enum Tier {

    case I;
    case II;
    case III;

    public function toString(): string {
        return match($this) {
            self::I => "I",
            self::II => "II",
            self::III => "III"
        };
    }

}