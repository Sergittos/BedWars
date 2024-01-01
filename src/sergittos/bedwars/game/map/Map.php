<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\team\Team;
use function uniqid;

class Map {
    use MapProperties;

    /** @var Team[] */
    private array $teams;

    /**
     * @param Generator[] $generators
     * @param Team[] $teams
     */
    public function __construct(string $name, Vector3 $spectator_spawn_position, int $players_per_team, int $max_capacity, World $waiting_world, array $generators, array $teams, array $shop_locations, array $upgrades_locations) {
        $this->id = uniqid("map-");
        $this->name = $name;
        $this->spectator_spawn_position = $spectator_spawn_position;
        $this->players_per_team = $players_per_team;
        $this->max_capacity = $max_capacity;
        $this->waiting_world = $waiting_world;
        $this->generators = $generators;
        $this->teams = $teams;
        $this->shop_positions = $shop_locations;
        $this->upgrades_positions = $upgrades_locations;
    }

    /**
     * @return Team[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

}