<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item\category;


use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\session\Session;

class BlocksCategory extends Category {

    public function __construct() {
        parent::__construct("Blocks");
    }

    /**
     * @return ItemProduct[]
     */
    public function getProducts(Session $session): array {
        $color = $session->getTeam()->getDyeColor();
        return [
            new ItemProduct("Wool", 4, 16, VanillaBlocks::WOOL()->setColor($color), VanillaItems::IRON_INGOT()),
            new ItemProduct("Hardened Clay", 12, 16, VanillaBlocks::STAINED_CLAY()->setColor($color), VanillaItems::IRON_INGOT()),
            new ItemProduct("Blast-Proof Glass", 12, 4, VanillaBlocks::STAINED_GLASS()->setColor($color), VanillaItems::IRON_INGOT()),
            new ItemProduct("End Stone", 24, 12, VanillaBlocks::END_STONE(), VanillaItems::IRON_INGOT()),
            new ItemProduct("Ladder", 4, 8, VanillaBlocks::LADDER(), VanillaItems::IRON_INGOT()),
            new ItemProduct("Oak Wood Planks", 4, 16, VanillaBlocks::OAK_WOOD(), VanillaItems::GOLD_INGOT()),
            new ItemProduct("Obsidian", 4, 4, VanillaBlocks::OBSIDIAN(), VanillaItems::EMERALD())
        ];
    }

}