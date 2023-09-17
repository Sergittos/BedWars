<?php

declare(strict_types=1);


namespace sergittos\bedwars\utils;


use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\game\shop\Product;
use sergittos\bedwars\session\Session;
use function strtok;
use function strtolower;
use function ucfirst;

class ShopUtils {

    static public function purchaseProduct(Session $session, Product $product): void {
        $player = $session->getPlayer();
        if(self::canPurchase($player, $product) and $product->onPurchase($session)) {
            $player->getInventory()->removeItem($product->getOre());

            $session->message("{GREEN}You purchased {GOLD}" . $product->getName());
        }
    }

    static public function canPurchase(Player $player, Product $product): bool {
        $price = $product->getPrice();
        $item = self::getItem($player->getInventory(), $ore = $product->getOre());
        if($item === null or ($count = $item->getCount()) < $product->getPrice()) {
            $player->sendMessage(
                TextFormat::RED . "You don't have enough " . $ore->getName() . "! Need " .
                ($item === null ? $price : ($price - $count)) . " more!"
            );
            return false;
        }
        return true;
    }

    /**
     * @return Item[]|null
     */
    static private function getItem(Inventory $inventory, Item $ore): ?Item {
        $count = 0;
        foreach($inventory->all($ore) as $item) {
            $count += $item->getCount();
        }
        return $count !== 0 ? clone $ore->setCount($count) : null;
    }

    static public function getName(Product $product): string {
        $name = TextFormat::GREEN . $product->getName();
        if($product instanceof ItemProduct and ($amount = $product->getAmount()) > 1) {
            $name .= " x" . $amount;
        }
        return $name;
    }

    static public function getCost(Product $product): string {
        $price = $product->getPrice();

        $name = strtok(strtolower($product->getOre()->getVanillaName()), " ");
        $color = GameUtils::getGeneratorColor($name);
        if($name !== "iron" and $name !== "gold" and $price !== 1) {
            $name .= "s";
        }

        return ColorUtils::translate("{GRAY}Cost: " . $color . $price . " " . ucfirst($name));
    }

}