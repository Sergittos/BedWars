<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use JsonSerializable;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\team\Team;
use function array_map;
use function uniqid;

class Map implements JsonSerializable {
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

    public function jsonSerialize(): array {
        return [
            "name" => $this->name,
            "waiting_world" => $this->waiting_world->getFolderName(),
            "spectator_spawn_position" => [
                "x" => $this->spectator_spawn_position->getX(),
                "y" => $this->spectator_spawn_position->getY(),
                "z" => $this->spectator_spawn_position->getZ()
            ],
            "players_per_team" => $this->players_per_team,
            "max_capacity" => $this->max_capacity,
            "generators" => [
                "diamond" => $this->jsonSerializePositions($this->getGeneratorPositions(GeneratorType::DIAMOND)),
                "emerald" => $this->jsonSerializePositions($this->getGeneratorPositions(GeneratorType::EMERALD))
            ],
            "teams" => array_map(function(Team $team) {
                return $team->jsonSerialize();
            }, $this->teams),
            "shop_positions" => $this->jsonSerializePositions($this->shop_positions),
            "upgrades_positions" => $this->jsonSerializePositions($this->upgrades_positions)
        ];
    }

    private function getGeneratorPositions(GeneratorType $type): array {
        $positions = [];
        foreach($this->generators as $generator) {
            if($generator->getType() === $type) {
                $positions[] = $generator->getPosition();
            }
        }
        return $positions;
    }

    private function jsonSerializePositions(array $positions): array {
        return array_map(function(Vector3 $position) {
            return [
                "x" => $position->getX(),
                "y" => $position->getY(),
                "z" => $position->getZ()
            ];
        }, $positions);
    }

}