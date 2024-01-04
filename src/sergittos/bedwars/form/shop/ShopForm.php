<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\shop;


use sergittos\bedwars\form\SimpleForm;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;

class ShopForm extends SimpleForm {

    private Session $session;
    private Shop $shop;

    public function __construct(Session $session, string $name, Shop $shop) {
        $this->session = $session;
        $this->shop = $shop;
        parent::__construct($name);
    }

    protected function onCreation(): void {
        foreach($this->shop->getCategories() as $category) {
            $this->addRedirectFormButton(
                ColorUtils::translate("{GOLD}{BOLD}" . $category->getName() . "{RESET}\n{YELLOW}Click to view!"),
                new CategoryForm($this->session, $category)
            );
        }
    }

}