<?php

declare(strict_types=1);


namespace sergittos\bedwars\item;


use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;
use function str_replace;

abstract class BedwarsItem {

    private string $name;
    private bool $disable_transactions;

    public function __construct(string $name, bool $disable_transactions = true) {
        $this->name = ColorUtils::translate($name);
        $this->disable_transactions = $disable_transactions;
    }

    public function asItem(): Item {
        $item = $this->realItem();
        $item->setCustomName($this->name);

        $nbt = $item->getNamedTag();
        $nbt->setString("bedwars_name", str_replace(" ", "_", TextFormat::clean($this->name)));

        if($this->disable_transactions) {
            $nbt->setByte("bedwars_item", 1);
        }

        return $item;
    }

    abstract public function onInteract(Session $session): void;

    abstract protected function realItem(): Item;

}