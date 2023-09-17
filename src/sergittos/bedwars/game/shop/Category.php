<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop;


use sergittos\bedwars\session\Session;

abstract class Category {

    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    /**
     * @return Product[]
     */
    abstract public function getProducts(Session $session): array;

}