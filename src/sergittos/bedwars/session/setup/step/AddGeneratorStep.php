<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


use pocketmine\block\Block;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItems;
use sergittos\bedwars\item\setup\AddGeneratorItem;

class AddGeneratorStep extends Step {

    private GeneratorType $type;

    public function __construct(GeneratorType $type) {
        $this->type = $type;
    }

    protected function onStart(): void {
        $block = match($this->type) {
            GeneratorType::DIAMOND => BedwarsItems::DIAMOND_GENERATOR(),
            GeneratorType::EMERALD => BedwarsItems::EMERALD_GENERATOR()
        };

        $this->session->clearAllInventories();
        $this->session->message("{YELLOW}Break the block below the generator with the item you have received in your inventory to set the generator.");

        $inventory = $this->session->getPlayer()->getInventory();
        $inventory->setItem(0, $block->asItem());
        $inventory->setItem(8, BedwarsItems::CANCEL()->asItem());
    }

    public function onBlockBreak(Block $block, BedwarsItem $item): void {
        if($item instanceof AddGeneratorItem) {
            $position = $block->getPosition();
            $position->getWorld()->setBlock($position, $item->asItem()->getBlock());

            $setup = $this->session->getMapSetup();
            $setup->getMapBuilder()->addGenerator(GeneratorType::toGenerator($position, $this->type));
            $setup->setStep(new PreparingMapStep());

            $this->session->message("{GREEN}You have successfully set the generator.");
        }
    }

}