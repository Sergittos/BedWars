<?php

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