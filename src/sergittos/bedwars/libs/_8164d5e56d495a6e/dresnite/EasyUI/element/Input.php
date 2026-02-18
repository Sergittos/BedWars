<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element;


class Input extends Element {

    private ?string $defaultText;
    private ?string $placeholder;
    private ?String $submittedText = null;

    public function __construct(?string $headerText, ?string $defaultText = null, ?string $placeholder = null) {
        $this->defaultText = $defaultText;
        $this->placeholder = $placeholder;
        parent::__construct($headerText);
    }

    public function getSubmittedText(): ?string {
        return $this->submittedText;
    }

    public function isReadOnly(): bool {
        return false;
    }

    public function getType(): string {
        return Element::TYPE_INPUT;
    }

    public function assignResult($result): void {
        $this->submittedText = $result;
    }

    public function serializeBody(): array {
        return [
            "default" => $this->defaultText ?? "",
            "placeholder" => $this->placeholder ?? ""
        ];
    }

}