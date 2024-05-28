<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\utils\message;


use sergittos\bedwars\BedWars;

/**
 * @author dresnite
 */
class MessageContainer {

    private string $id;
    private array $arguments;

    public function __construct(string $id, array $arguments = []) {
        $this->id = $id;
        $this->arguments = $arguments;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getArguments(): array {
        return $this->arguments;
    }

    public function getMessage(): string|array {
        return BedWars::getInstance()->getMessageManager()->getMessage($this);
    }

}