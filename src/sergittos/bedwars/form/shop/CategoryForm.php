<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\shop;


use EasyUI\element\Button;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\form\SimpleForm;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\Product;
use sergittos\bedwars\game\shop\upgrades\category\TrapsCategory;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\SessionFactory;
use function array_shift;

class CategoryForm extends SimpleForm {

    private Session $session;
    private Category $category;

    public function __construct(Session $session, Category $category) {
        $this->session = $session;
        $this->category = $category;
        parent::__construct($category->getName(), $this->getTrapsHeaderText());
    }

    protected function onCreation(): void {
        if(!$this->session->isPlaying() or !$this->session->hasTeam()) {
            // This should never happen, but just in case
            return;
        }

        foreach($this->category->getProducts($this->session) as $product) {
            $button = new Button($product->getDisplayName($this->session) . "\n" . $product->getDescription($this->session));
            $button->setSubmitListener(function(Player $player) use ($product) {
                $session = SessionFactory::getSession($player);
                if($session !== null and $session->isPlaying()) {
                    $this->purchaseProduct($product);
                }
            });
            $this->addButton($button);
        }
    }

    private function purchaseProduct(Product $product): void {
        if($this->canPurchaseProduct($product) and $product->onPurchase($this->session)) {
            $player = $this->session->getPlayer();
            $player->getInventory()->removeItem($product->getOre());
            $player->sendForm(new CategoryForm($this->session, $this->category));

            $this->session->message("{GREEN}You purchased {GOLD}" . $product->getName());
        }
    }

    private function canPurchaseProduct(Product $product): bool {
        if(!$product->canBePurchased($this->session)) {
            $this->session->message("{RED}You already have this product!");
            return false;
        }
        $ore = $product->getOre();

        $inventory = $this->session->getPlayer()->getInventory();
        if(!$inventory->contains($ore)) {
            $count = 0;
            foreach($inventory->all($ore) as $item) {
                $count += $item->getCount();
            }
            $count = $ore->getCount() - $count;

            $this->session->message(TextFormat::RED . "You don't have enough " . $ore->getName() . "! Need $count more!");
            return false;
        }
        return true;
    }

    private function getTrapsHeaderText(): string {
        if(!$this->category instanceof TrapsCategory or !$this->session->hasTeam()) {
            return "";
        }
        $traps = $this->session->getTeam()->getUpgrades()->getTraps();

        $header_text = "";
        for($i = 1; $i <= 3; $i++) {
            $trap = array_shift($traps);
            $header_text .= "Trap #" . $i . " - " . ($trap !== null ? $trap->getName() : "Empty") . "\n";
        }
        return $header_text;
    }

}