<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\shop;


use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\utils\ColorUtils;

class ShopForm extends SimpleForm {

    private Shop $shop;

    private bool $resend_form;

    public function __construct(string $name, Shop $shop, bool $resend_form) {
        $this->shop = $shop;
        $this->resend_form = $resend_form;
        parent::__construct($name);
    }

    protected function onCreation(): void {
        foreach($this->shop->getCategories() as $category) {
            $button = new Button(ColorUtils::translate("{GOLD}{BOLD}" . $category->getName() . "{RESET}\n{YELLOW}Click to view!"));
            $button->setSubmitListener(function(Player $player) use ($category) {
                $player->sendForm(new CategoryForm(SessionFactory::getSession($player), $category, $this->resend_form));
            });
            $this->addButton($button);
        }
    }

}