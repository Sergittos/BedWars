<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element;


class Toggle extends Element {

    private bool $defaultChoice;
    private ?bool $submittedChoice = null;

    public function __construct(?string $headerText, bool $defaultChoice = false) {
        $this->defaultChoice = $defaultChoice;
        parent::__construct($headerText);
    }

    public function getSubmittedChoice(): ?bool {
        return $this->submittedChoice;
    }

    public function isReadOnly(): bool {
        return false;
    }

    public function getType(): string {
        return Element::TYPE_TOGGLE;
    }

    public function getDefaultChoice(): bool {
        return $this->defaultChoice;
    }

    public function setDefaultChoice(bool $defaultChoice): void {
        $this->defaultChoice = $defaultChoice;
    }

    public function assignResult($result): void {
        $this->submittedChoice = $result;
    }

    public function serializeBody(): array {
        return [
            "default" => $this->defaultChoice
        ];
    }

}