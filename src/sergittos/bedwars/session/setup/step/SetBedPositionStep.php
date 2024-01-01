<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


use pocketmine\block\Block;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItems;
use sergittos\bedwars\item\setup\SetBedPositionItem;
use sergittos\bedwars\session\setup\builder\TeamBuilder;

class SetBedPositionStep extends Step {

    private TeamBuilder $team;

    public function __construct(TeamBuilder $team) {
        $this->team = $team;
    }

    protected function onStart(): void {
        $this->session->clearInventories();
        $this->session->message("{YELLOW}Place the bed you received in your inventory to set the position.");

        $inventory = $this->session->getPlayer()->getInventory();
        $inventory->setItem(1, BedwarsItems::BED_POSITION()->asItem());
        $inventory->setItem(8, BedwarsItems::CANCEL()->asItem());
    }

    public function onBlockPlace(Block $block, BedwarsItem $item): void {
        if($item instanceof SetBedPositionItem) {
            $position = $block->getPosition();
            $position->getWorld()->setBlock($position, $item->asItem()->getBlock());

            $this->team->setBedPosition($block->getPosition());

            $this->session->getMapSetup()->setStep(new PreparingMapStep());
            $this->session->message("{GREEN}You have successfully set the bed position.");
        }
    }

}