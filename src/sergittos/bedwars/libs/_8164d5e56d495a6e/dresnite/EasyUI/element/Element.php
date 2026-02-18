<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element;


use JsonSerializable;

/**
 * Element is exclusive for the elements of CustomForms
 */
abstract class Element implements JsonSerializable {

    private ?string $headerText;

    public const TYPE_DROPDOWN = "dropdown";
    public const TYPE_INPUT = "input";
    public const TYPE_LABEL = "label";
    public const TYPE_SLIDER = "slider";
    public const TYPE_STEP_SLIDER = "step_slider";
    public const TYPE_TOGGLE = "toggle";
    public const TYPE_HEADER = "header";
    public const TYPE_DIVIDER = "divider";

    public function __construct(?string $headerText) {
        $this->headerText = $headerText;
    }

    public function getHeaderText(): ?string {
        return $this->headerText;
    }

    public function setHeaderText(?string $headerText): void {
        $this->headerText = $headerText;
    }

    abstract public function isReadOnly(): bool;

    abstract public function getType(): string;

    abstract public function serializeBody(): array;

    abstract public function assignResult($result): void;

    public function jsonSerialize(): array {
        return array_merge($this->serializeBody(), [
            "type" => $this->getType(),
            "text" => $this->headerText ?? ""
        ]);
    }

}