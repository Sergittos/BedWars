<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element;


class Divider extends Element {

    public function __construct() {
        parent::__construct(null);
    }

    public function isReadOnly(): bool {
        return true;
    }

    public function getType(): string {
        return Element::TYPE_DIVIDER;
    }

    public function assignResult($result): void {
        // noop
    }

    public function serializeBody(): array {
        return [];
    }

}