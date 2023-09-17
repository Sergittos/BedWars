<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\event;


use sergittos\bedwars\game\Game;
use function time;

abstract class Event {

    protected Game $game;

    protected string $name;

    private int $duration;
    private int $start_time;

    public function __construct(string $name, int $duration) {
        $this->name = $name;
        $this->duration = $duration * 60;
    }

    public function getName(): string {
        return $this->name;
    }

    private function getTimeElapsed(): int {
        return time() - $this->start_time;
    }

    public function getTimeRemaining(): int {
        return $this->duration - $this->getTimeElapsed();
    }

    public function hasEnded(): bool {
        if($this->getTimeElapsed() >= $this->duration) {
            $this->end();
            return true;
        }
        return false;
    }

    public function start(Game $game): void {
        $this->game = $game;
        $this->start_time = time();
    }

    abstract protected function end(): void;

    abstract public function getNextEvent(): ?Event;

}