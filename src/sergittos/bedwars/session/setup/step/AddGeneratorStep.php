<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


use pocketmine\block\Block;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItemRegistry;
use sergittos\bedwars\item\setup\AddGeneratorItem;

class AddGeneratorStep extends Step {

    private GeneratorType $type;

    public function __construct(GeneratorType $type) {
        $this->type = $type;
    }

    protected function onStart(): void {
        $item = match($this->type) {
            GeneratorType::DIAMOND => BedwarsItemRegistry::DIAMOND_GENERATOR(),
            GeneratorType::EMERALD => BedwarsItemRegistry::EMERALD_GENERATOR()
        };

        $this->session->clearAllInventories();
        $this->session->message("{YELLOW}Break the block below the generator with the item you have received in your inventory to set the generator.");

        $inventory = $this->session->getPlayer()->getInventory();
        $inventory->setItem(0, $item);
        $inventory->setItem(8, BedwarsItemRegistry::CANCEL());
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