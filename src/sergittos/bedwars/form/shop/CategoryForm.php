<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\shop;


use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\utils\ShopUtils;

class CategoryForm extends SimpleForm {

    private Session $session;
    private Category $category;

    private bool $resend_form;

    public function __construct(Session $session, Category $category, bool $resend_form) {
        $this->session = $session;
        $this->category = $category;
        $this->resend_form = $resend_form;
        parent::__construct($category->getName());
    }

    protected function onCreation(): void {
        foreach($this->category->getProducts($this->session) as $product) {
            $button = new Button(ShopUtils::getName($product) . "\n" . ShopUtils::getCost($product));
            $button->setSubmitListener(function(Player $player) use ($product) {
                $session = SessionFactory::getSession($player);
                if($session === null or !$session->isPlaying() or !$session->hasTeam()) {
                    return;
                }

                ShopUtils::purchaseProduct($session, $product);

                if($this->resend_form) {
                    $player->sendForm($this);
                }
            });
            $this->addButton($button);
        }
    }

}