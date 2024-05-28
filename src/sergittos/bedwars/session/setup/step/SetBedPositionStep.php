<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


use pocketmine\block\VanillaBlocks;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\world\BlockTransaction;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItemRegistry;
use sergittos\bedwars\item\setup\SetBedPositionItem;
use sergittos\bedwars\session\setup\builder\TeamBuilder;

class SetBedPositionStep extends Step {

    private TeamBuilder $team;

    public function __construct(TeamBuilder $team) {
        $this->team = $team;
    }

    protected function onStart(): void {
        $this->session->clearAllInventories();
        $this->session->message("{YELLOW}Place the bed you received in your inventory to set the position.");

        $inventory = $this->session->getPlayer()->getInventory();
        $inventory->setItem(0, BedwarsItemRegistry::BED_POSITION()->setColor($this->team->getDyeColor())->asItem());
        $inventory->setItem(8, BedwarsItemRegistry::CANCEL());
    }

    public function onBlockInteract(Vector3 $touchVector, int $action, Cancellable $event, BedwarsItem $item): void {
        if($item instanceof SetBedPositionItem and $action === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            $this->team->setBedPosition($touchVector);
            
            $this->session->getMapSetup()->setStep(new PreparingMapStep());
            $this->session->message("{GREEN}You have successfully set the bed position.");

            $event->uncancel();
        }
    }

}