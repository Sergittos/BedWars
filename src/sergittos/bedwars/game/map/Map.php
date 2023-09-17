<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\team\Team;
use function uniqid;

class Map {

    private string $id;
    private string $name;

    private Vector3 $spectator_spawn_position;

    private int $players_per_team;
    private int $max_capacity; // slots?

    private World $waiting_world;

    /** @var Vector3[] */
    private array $shop_positions;

    /** @var Vector3[] */
    private array $upgrades_positions;

    /** @var Generator[] */
    private array $generators;

    /** @var Team[] */
    private array $teams;

    /**
     * @param Generator[] $generators
     * @param Team[] $teams
     */
    public function __construct(string $name, Vector3 $spectator_spawn_position, int $player_team_capacity, int $max_capacity, World $waiting_world, array $generators, array $teams, array $shop_locations, array $upgrades_locations) {
        $this->id = uniqid("map-");
        $this->name = $name;
        $this->spectator_spawn_position = $spectator_spawn_position;
        $this->players_per_team = $player_team_capacity;
        $this->max_capacity = $max_capacity;
        $this->waiting_world = $waiting_world;
        $this->generators = $generators;
        $this->teams = $teams;
        $this->shop_positions = $shop_locations;
        $this->upgrades_positions = $upgrades_locations;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSpectatorSpawnPosition(): Vector3 {
        return $this->spectator_spawn_position;
    }

    public function getPlayersPerTeam(): int {
        return $this->players_per_team;
    }

    public function getMaxCapacity(): int {
        return $this->max_capacity;
    }

    public function getWaitingWorld(): World {
        return $this->waiting_world;
    }

    /**
     * @return Generator[]
     */
    public function getGenerators(): array {
        return $this->generators;
    }

    /**
     * @return Team[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    /**
     * @return Vector3[]
     */
    public function getShopPositions(): array {
        return $this->shop_positions;
    }

    /**
     * @return Vector3[]
     */
    public function getUpgradesPositions(): array {
        return $this->upgrades_positions;
    }

}