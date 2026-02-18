<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\utils;


use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Dropdown;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Element;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Input;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Selector;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Slider;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\StepSlider;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Toggle;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class FormResponse {

    /** @var Element[] */
    private array $elements;

    /**
     * FormResponse constructor.
     * @param Element[] $elements
     */
    public function __construct(array $elements) {
        $this->elements = $elements;
    }

    public function getInputSubmittedText(string $inputId): string {
        return $this->getElement($inputId, Input::class)->getSubmittedText();
    }

    public function getToggleSubmittedChoice(string $toggleId): bool {
        return $this->getElement($toggleId, Toggle::class)->getSubmittedChoice();
    }

    public function getSliderSubmittedStep(string $sliderId): float {
        return $this->getElement($sliderId, Slider::class)->getSubmittedStep();
    }

    public function getStepSliderSubmittedOptionId(string $sliderId): string {
        return $this->getElement($sliderId, StepSlider::class)->getSubmittedOptionId();
    }

    public function getDropdownSubmittedOptionId(string $dropdownId): string {
        return $this->getElement($dropdownId, Dropdown::class)->getSubmittedOptionId();
    }

    /**
     * @param string $id
     * @param string $expectedClass
     * @return Element|Input|Toggle|Slider|Selector
     */
    private function getElement(string $id, string $expectedClass): Element {
        $element = $this->elements[$id] ?? null;
        if(!$element instanceof Element) {
            throw new InvalidArgumentException("$id is not a valid element identifier");
        } elseif(!is_a($element, $expectedClass)) {
            try {
                throw new InvalidArgumentException("The element with $id is not a " . (new ReflectionClass($expectedClass))->getShortName());
            } catch(ReflectionException $exception) {
                throw new InvalidArgumentException($expectedClass . " doesn't use a valid... namespace?");
            }
        }
        return $element;
    }

}