<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\icon;


use JsonSerializable;

class ButtonIcon implements JsonSerializable {

    private string $address;
    private string $type;

    public const TYPE_PATH = "path";
    public const TYPE_URL = "url";

    public function __construct(string $address, string $type = self::TYPE_URL) {
        $this->address = $address;
        $this->type = $type;
    }

    public function getAddress(): string {
        return $this->address;
    }

    public function getType(): string {
        return $this->type;
    }

    public function jsonSerialize(): array {
        return [
            "type" => $this->type,
            "data" => $this->address
        ];
    }
}