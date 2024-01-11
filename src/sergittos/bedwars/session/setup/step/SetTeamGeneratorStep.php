<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


use pocketmine\block\Block;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItems;
use sergittos\bedwars\item\setup\SetTeamGeneratorItem;
use sergittos\bedwars\session\setup\builder\TeamBuilder;

class SetTeamGeneratorStep extends Step {

    private TeamBuilder $team;

    public function __construct(TeamBuilder $team) {
        $this->team = $team;
    }

    protected function onStart(): void {
        $this->session->clearAllInventories();
        $this->session->message("{YELLOW}Break the block below the generator with the item you have received in your inventory to set the generator.");

        $inventory = $this->session->getPlayer()->getInventory();
        $inventory->setItem(0, BedwarsItems::TEAM_GENERATOR()->asItem());
        $inventory->setItem(8, BedwarsItems::CANCEL()->asItem());
    }

    public function onBlockBreak(Block $block, BedwarsItem $item): void {
        if($item instanceof SetTeamGeneratorItem) {
            $this->team->setGeneratorPosition($block->getPosition());

            $this->session->getMapSetup()->setStep(new PreparingMapStep());
            $this->session->message("{GREEN}You have successfully set the generator.");
        }
    }

}