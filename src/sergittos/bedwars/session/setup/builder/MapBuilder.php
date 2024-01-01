<?php

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

    private string $playing_world;

    /** @var TeamBuilder[] */
    private array $teams;

    public function __construct(string $name, World $waiting_world, string $playing_world, int $players_per_team, int $max_capacity) {
        $this->name = $name;
        $this->waiting_world = $waiting_world;
        $this->playing_world = $playing_world;
        $this->players_per_team = $players_per_team;
        $this->max_capacity = $max_capacity;

        $this->setDefaultTeams();
    }

    public function setSpectatorSpawnPosition(Vector3 $spectator_spawn_position): void {
        $this->spectator_spawn_position = $spectator_spawn_position;
    }

    public function setShopPositions(array $shop_positions): void {
        $this->shop_positions = $shop_positions;
    }

    public function setUpgradesPositions(array $upgrades_positions): void {
        $this->upgrades_positions = $upgrades_positions;
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
        $this->addTeam("Red");
        $this->addTeam("Blue");
        $this->addTeam("Yellow");
        $this->addTeam("Green");
        $this->addTeam("Cyan");
        $this->addTeam("Gray");
        $this->addTeam("Orange");
        $this->addTeam("Magenta");
    }

    /**
     * @return TeamBuilder[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    public function build(): Map {
        return new Map(
            $this->name,
            $this->spectator_spawn_position,
            $this->players_per_team,
            $this->max_capacity,
            $this->waiting_world,
            $this->generators,
            array_map(function(TeamBuilder $team_builder): Team {
                return $team_builder->build($this);
            }, $this->teams),
            $this->shop_positions,
            $this->upgrades_positions
        );
    }

}