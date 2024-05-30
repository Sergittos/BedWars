<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop;


abstract class Shop {

    abstract public function getName(): string;

    /**
     * @return Category[]
     */
    abstract public function getCategories(): array;

}