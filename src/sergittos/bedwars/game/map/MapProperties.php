<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\generator\Generator;

trait MapProperties {

    protected string $id;
    protected string $name;

    protected Vector3 $spectator_spawn_position;

    protected int $players_per_team; // TODO: Make an enum for this
    protected int $max_capacity; // slots?

    protected World $waiting_world;

    /** @var Vector3[] */
    protected array $shop_positions;

    /** @var Vector3[] */
    protected array $upgrades_positions;

    /** @var Generator[] */
    protected array $generators;

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
        return $this->generators ?? [];
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