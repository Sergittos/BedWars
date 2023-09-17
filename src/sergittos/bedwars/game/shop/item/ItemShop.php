<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item;


use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\category\ArmorCategory;
use sergittos\bedwars\game\shop\item\category\BlocksCategory;
use sergittos\bedwars\game\shop\item\category\MeleeCategory;
use sergittos\bedwars\game\shop\item\category\MiscCategory;
use sergittos\bedwars\game\shop\item\category\PotionsCategory;
use sergittos\bedwars\game\shop\item\category\RangedCategory;
use sergittos\bedwars\game\shop\item\category\ToolsCategory;
use sergittos\bedwars\game\shop\Shop;

class ItemShop extends Shop {

    public function getId(): string {
        return Shop::ITEM;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array {
        return [
            new BlocksCategory(),
            new MeleeCategory(),
            new ArmorCategory(),
            new ToolsCategory(),
            new RangedCategory(),
            new PotionsCategory(),
            new MiscCategory()
        ];
    }

}