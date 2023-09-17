<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\entity\shop;


use EasyUI\Form;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\utils\ColorUtils;
use function strtoupper;

abstract class Villager extends Entity {

    public static function getNetworkTypeId(): string {
        return EntityIds::VILLAGER;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(1.8, 0.6);
    }

    protected function getInitialGravity(): float {
        return 0.08;
    }

    protected function getInitialDragMultiplier(): float {
        return 0.02;
    }

    protected function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);

        $this->setNameTag(ColorUtils::translate("{AQUA}" . strtoupper($this->getName()) . "{RESET}\n{YELLOW}{BOLD}RIGHT CLICK"));
        $this->setNameTagAlwaysVisible();
    }

    public function attack(EntityDamageEvent $source): void {
        if($source instanceof EntityDamageByChildEntityEvent or !$source instanceof EntityDamageByEntityEvent) {
            return;
        }

        $damager = $source->getDamager();
        if($damager instanceof Player and SessionFactory::hasSession($damager)) {
            $damager->sendForm($this->getForm(SessionFactory::getSession($damager)));
        }
    }

    public function onInteract(Player $player, Vector3 $clickPos): bool {
        $player->sendForm($this->getForm(SessionFactory::getSession($player)));
        return true;
    }

    abstract protected function getName(): string;

    abstract protected function getForm(Session $session): Form;

}