<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\team;


use JsonSerializable;
use pocketmine\math\Vector3;
use function max;
use function min;

class Area implements JsonSerializable {


    private Vector3 $first_vector;
    private Vector3 $second_vector;

    public function __construct(Vector3 $first_vector, Vector3 $second_vector) {
        $this->first_vector = $first_vector->floor();
        $this->second_vector = $second_vector->floor();
    }

    static public function fromData(array $data): Area {
        return new Area(
            new Vector3($data["first_x"], $data["first_y"], $data["first_z"]),
            new Vector3($data["second_x"], $data["second_y"], $data["second_z"])
        );
    }

    public function getMinX(): int {
        return min($this->first_vector->getFloorX(), $this->second_vector->getFloorX());
    }

    public function getMinY(): int {
        return min($this->first_vector->getFloorY(), $this->second_vector->getFloorY());
    }

    public function getMinZ(): int {
        return min($this->first_vector->getFloorZ(), $this->second_vector->getFloorZ());
    }

    public function getMaxX(): int {
        return max($this->first_vector->getFloorX(), $this->second_vector->getFloorX());
    }

    public function getMaxY(): int {
        return max($this->first_vector->getFloorY(), $this->second_vector->getFloorY());
    }

    public function getMaxZ(): int {
        return max($this->first_vector->getFloorZ(), $this->second_vector->getFloorZ());
    }

    public function isInside(Vector3 $position): bool {
        return $position->x >= $this->getMinX() && $position->x <= $this->getMaxX() and
            $position->z >= $this->getMinZ() && $position->z <= $this->getMaxZ();
    }

    public function jsonSerialize(): array {
        return [
            "first_x" => $this->first_vector->getFloorX(),
            "first_y" => $this->first_vector->getFloorY(),
            "first_z" => $this->first_vector->getFloorZ(),
            "second_x" => $this->second_vector->getFloorX(),
            "second_y" => $this->second_vector->getFloorY(),
            "second_z" => $this->second_vector->getFloorZ()
        ];
    }

}