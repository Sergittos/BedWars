<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard;


use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use sergittos\bedwars\session\scoreboard\layout\Layout;
use sergittos\bedwars\session\scoreboard\layout\LobbyLayout;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;
use sergittos\bedwars\utils\message\MessageContainer;

class Scoreboard {

    private Session $session;
    private Layout $layout;

    private string $title;

    public function __construct(Session $session) {
        $this->session = $session;
        $this->layout = new LobbyLayout();
        $this->title = (new MessageContainer("SCOREBOARD_TITLE"))->getMessage();
    }

    public function setLayout(Layout $layout): void {
        $this->layout = $layout;
        $this->update();
    }

    public function update(): void {
        $this->hide();
        $this->display();
        $this->displayMessages();
    }

    private function hide(): void {
        $this->session->sendDataPacket(RemoveObjectivePacket::create($this->session->getUsername()));
    }

    private function display(): void {
        $this->session->sendDataPacket(SetDisplayObjectivePacket::create(
            SetDisplayObjectivePacket::DISPLAY_SLOT_SIDEBAR,
            $this->session->getUsername(),
            $this->title,
            "dummy",
            SetDisplayObjectivePacket::SORT_ORDER_DESCENDING
        ));
    }

    private function displayMessages(): void {
        $messages = $this->layout->getMessageContainer($this->session)->getMessage();
        foreach($messages as $index => $message) {
            $this->setLine(count($messages) - $index, $message);
        }
    }

    private function setLine(int $score, string $text): void {
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $this->session->getUsername();
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entry->customName = ColorUtils::translate($text);
        $entry->score = $score;
        $entry->scoreboardId = $score;

        $this->session->sendDataPacket(SetScorePacket::create(ScorePacketEntry::TYPE_FAKE_PLAYER, [$entry]));
    }

}