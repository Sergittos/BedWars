<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element;


class Slider extends Element {

    private float $minStep;
    private float $maxStep;

    private float $defaultStep;
    private float $stepLength;

    private ?float $submittedStep = null;

    public function __construct(?string $headerText, float $minStep, float $maxStep, float $defaultStep = 0, float $stepLength = 0) {
        $this->minStep = $minStep;
        $this->maxStep = $maxStep;
        $this->defaultStep = $defaultStep;
        $this->stepLength = $stepLength;
        parent::__construct($headerText);
    }

    public function getSubmittedStep(): ?float {
        return $this->submittedStep;
    }

    public function isReadOnly(): bool {
        return false;
    }

    public function getType(): string {
        return Element::TYPE_SLIDER;
    }

    public function getMinStep(): float {
        return $this->minStep;
    }

    public function setMinStep(float $minStep): void {
        $this->minStep = $minStep;
    }

    public function getMaxStep(): float {
        return $this->maxStep;
    }

    public function setMaxStep(float $maxStep): void {
        $this->maxStep = $maxStep;
    }

    public function getDefaultStep(): float {
        return $this->defaultStep;
    }

    public function setDefaultStep(float $defaultStep): void {
        $this->defaultStep = $defaultStep;
    }

    public function getStepLength(): float {
        return $this->stepLength;
    }

    public function setStepLength(float $stepLength): void {
        $this->stepLength = $stepLength;
    }

    public function assignResult($result): void {
        $this->submittedStep = $result;
    }

    public function serializeBody(): array {
        return [
            "min" => $this->minStep,
            "max" => $this->maxStep,
            "default" => $this->defaultStep,
            "step" => $this->stepLength
        ];
    }

}