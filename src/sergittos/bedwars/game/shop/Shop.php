<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\shop;


abstract class Shop {

    public const ITEM = "item";
    public const UPGRADES = "upgrades";
    public const TRACKER = "tracker";

    abstract public function getId(): string;

    /**
     * @return Category[]
     */
    abstract public function getCategories(): array;

}