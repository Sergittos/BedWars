<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step\area;


use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use sergittos\bedwars\game\team\Area;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItems;
use sergittos\bedwars\item\setup\ClaimingWandItem;
use sergittos\bedwars\session\setup\builder\TeamBuilder;
use sergittos\bedwars\session\setup\step\PreparingMapStep;
use sergittos\bedwars\session\setup\step\Step;

abstract class SetAreaStep extends Step {

    protected TeamBuilder $team;

    private Vector3 $first_position;
    private Vector3 $second_position;

    public function __construct(TeamBuilder $team) {
        $this->team = $team;
    }

    protected function onStart(): void {
        $this->session->clearAllInventories();
        $this->session->message("{GOLD}Set team area started.");
        $this->session->message("{YELLOW}Left click at a corner of the area you'd like to set.");
        $this->session->message("{YELLOW}Right click on the second corner of the area you'd like to set.");
        $this->session->message("{YELLOW}Sneak and click to set your area.");
        $this->session->message("{GREEN}Gave you a claim wand.");

        $inventory = $this->session->getPlayer()->getInventory();
        $inventory->setItem(0, BedwarsItems::CLAIMING_WAND()->asItem());
        $inventory->setItem(8, BedwarsItems::CANCEL()->asItem());
    }

    public function onBlockInteract(Vector3 $touch_vector, int $action, Cancellable $event, BedwarsItem $item): void {
        if(!$item instanceof ClaimingWandItem) {
            return;
        }

        switch($action) {
            case PlayerInteractEvent::LEFT_CLICK_BLOCK:
                $this->first_position = $touch_vector;
                $this->session->message("{GREEN}First position set.");
                break;
            case PlayerInteractEvent::RIGHT_CLICK_BLOCK:
                $this->second_position = $touch_vector;
                $this->session->message("{GREEN}Second position set.");
                break;
        }
    }

    public function onInteract(BedwarsItem $item): void {
        if(!$this->session->getPlayer()->isSneaking() or !$item instanceof ClaimingWandItem) {
            return;
        }

        if(!isset($this->first_position) or !isset($this->second_position)) {
            $this->session->message("{RED}You must set both positions before setting the area.");
            return;
        }

        $this->setArea(new Area($this->first_position, $this->second_position));

        $this->session->getMapSetup()->setStep(new PreparingMapStep());
        $this->session->message("{GREEN}You have successfully set the area.");
    }

    abstract protected function setArea(Area $area): void;

}