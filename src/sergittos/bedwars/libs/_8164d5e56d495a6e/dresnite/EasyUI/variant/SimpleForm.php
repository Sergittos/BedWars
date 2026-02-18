<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\variant;


use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Button;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\Form;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\utils\Closable;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;

class SimpleForm extends Form {
    use Closable;

    /** @var Button[] */
    private array $buttons = [];

    private ?string $headerText;

    public function __construct(string $title, ?string $headerText = null) {
        $this->headerText = $headerText;
        parent::__construct($title);
    }

    /**
     * @return Button[]
     */
    public function getButtons(): array {
        return $this->buttons;
    }

    public function addButton(Button $button): void {
        $this->buttons[] = $button;
    }

    public function getHeaderText(): ?string {
        return $this->headerText;
    }

    public function setHeaderText(?string $headerText): void {
        $this->headerText = $headerText;
    }

    protected function getType(): string {
        return Form::TYPE_SIMPLE_FORM;
    }

    public function handleResponse(Player $player, $data): void {
        if($data === null) {
            $this->notifyClose($player);
        } elseif(!is_int($data) or !isset($this->buttons[$data])) {
            throw new FormValidationException( "Couldn't find the option $data");
        } else {
            $this->buttons[$data]->notifySubmit($player);
        }
    }

    public function serializeBody(): array {
        return [
            "buttons" => array_map(function(Button $button) {
                return $button->jsonSerialize();
            }, $this->buttons),
            "content" => $this->headerText ?? ""
        ];
    }

}