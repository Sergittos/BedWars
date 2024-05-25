<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use JsonSerializable;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\World;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\team\Team;
use Symfony\Component\Filesystem\Path;
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
        $this->spectatorSpawnPosition = $spectator_spawn_position;
        $this->playersPerTeam = $players_per_team;
        $this->maxCapacity = $max_capacity;
        $this->waitingWorld = $waiting_world;
        $this->generators = $generators;
        $this->teams = $teams;
        $this->shopPositions = $shop_locations;
        $this->upgradesPositions = $upgrades_locations;
    }

    /**
     * @return Team[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    public function getWorldPath(): string {
        return Path::join(BedWars::getInstance()->getDataFolder(), "worlds", $this->name);
    }

    public function createWorldPath(int $id): string {
        return Path::join(Server::getInstance()->getDataPath(), "worlds", $this->name . "-" . $id);
    }

    public function jsonSerialize(): array {
        return [
            "name" => $this->name,
            "waiting_world" => $this->waitingWorld->getFolderName(),
            "spectator_spawn_position" => [
                "x" => $this->spectatorSpawnPosition->getX(),
                "y" => $this->spectatorSpawnPosition->getY(),
                "z" => $this->spectatorSpawnPosition->getZ()
            ],
            "players_per_team" => $this->playersPerTeam,
            "max_capacity" => $this->maxCapacity,
            "generators" => [
                "diamond" => $this->jsonSerializePositions($this->getGeneratorPositions(GeneratorType::DIAMOND)),
                "emerald" => $this->jsonSerializePositions($this->getGeneratorPositions(GeneratorType::EMERALD))
            ],
            "teams" => array_map(function(Team $team) {
                return $team->jsonSerialize();
            }, $this->teams),
            "shop_positions" => $this->jsonSerializePositions($this->shopPositions),
            "upgrades_positions" => $this->jsonSerializePositions($this->upgradesPositions)
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