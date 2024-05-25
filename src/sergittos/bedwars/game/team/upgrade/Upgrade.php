<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade;


use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;

abstract class Upgrade {

    protected int $level = 0;

    public function getLevel(): int {
        return $this->level;
    }

    public function canLevelUp(): bool {
        return $this->level < $this->getLevels();
    }

    public function levelUp(Team $team): void {
        $this->level++;

        $this->internalLevelUp($team);
        foreach($team->getMembers() as $session) {
            $this->applySession($session);
        }
    }

    public function applySession(Session $session): void {
        if($this->level > 0) {
            $this->internalApplySession($session);
        }
    }

    protected function internalLevelUp(Team $team): void {}

    protected function internalApplySession(Session $session): void {}

    abstract public function getName(): string;

    abstract public function getLevels(): int;

}