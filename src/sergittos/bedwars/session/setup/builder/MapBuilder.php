<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\builder;


use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\MapProperties;
use sergittos\bedwars\game\team\Team;
use function array_map;

class MapBuilder {
    use MapProperties;

    private string $playingWorld;

    /** @var TeamBuilder[] */
    private array $teams;

    public function __construct(string $name, World $waitingWorld, string $playingWorld, int $playersPerTeam, int $maxCapacity) {
        $this->name = $name;
        $this->waitingWorld = $waitingWorld;
        $this->playingWorld = $playingWorld;
        $this->playersPerTeam = $playersPerTeam;
        $this->maxCapacity = $maxCapacity;

        $this->setDefaultTeams();
    }

    public function getPlayingWorld(): string {
        return $this->playingWorld;
    }

    public function setSpectatorSpawnPosition(Vector3 $spectatorSpawnPosition): void {
        $this->spectatorSpawnPosition = $spectatorSpawnPosition;
    }

    public function addShopPosition(Vector3 $position): void {
        $this->shopPositions[] = $position;
    }

    public function addUpgradesPosition(Vector3 $position): void {
        $this->upgradesPositions[] = $position;
    }

    public function addGenerator(Generator $generator): void {
        $this->generators[] = $generator;
    }

    public function removeGenerator(Generator $generator): void {
        unset($this->generators[array_search($generator, $this->generators, true)]);
    }

    private function addTeam(string $name): void {
        $this->teams[] = new TeamBuilder($name);
    }

    private function setDefaultTeams(): void {
        $teams = ["Red", "Blue", "Yellow", "Green", "Cyan", "Gray", "Orange", "Magenta"];
        for($i = 0; $i < $this->getTeamsCount(); $i++) {
            $this->addTeam($teams[$i]);
        }
    }

    private function getTeamsCount(): int {
        return (int) ($this->maxCapacity / $this->playersPerTeam);
    }

    /**
     * @return TeamBuilder[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    public function canBeBuilt(): bool {
        $can_build = true;

        foreach($this->teams ?? [] as $team) {
            if(!$team->canBeBuilt()) {
                $can_build = false;
                break;
            }
        }

        $is_set = isset(
            $this->spectatorSpawnPosition,
            $this->generators,
            $this->teams,
            $this->shopPositions,
            $this->upgradesPositions
        );

        return $is_set and $can_build;
    }

    public function build(): Map {
        return new Map(
            $this->name,
            $this->spectatorSpawnPosition,
            $this->playersPerTeam,
            $this->maxCapacity,
            $this->waitingWorld,
            $this->generators,
            array_map(function(TeamBuilder $teamBuilder): Team {
                return $teamBuilder->build($this);
            }, $this->teams),
            $this->shopPositions,
            $this->upgradesPositions
        );
    }

}