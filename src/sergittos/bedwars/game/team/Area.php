<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\team;


use JsonSerializable;
use pocketmine\math\Vector3;
use function max;
use function min;

class Area implements JsonSerializable {


    private Vector3 $firstVector;
    private Vector3 $secondVector;

    public function __construct(Vector3 $firstVector, Vector3 $secondVector) {
        $this->firstVector = $firstVector->floor();
        $this->secondVector = $secondVector->floor();
    }

    static public function fromData(array $data): Area {
        return new Area(
            new Vector3($data["first_x"], $data["first_y"], $data["first_z"]),
            new Vector3($data["second_x"], $data["second_y"], $data["second_z"])
        );
    }

    public function getMinX(): int {
        return min($this->firstVector->getFloorX(), $this->secondVector->getFloorX());
    }

    public function getMinY(): int {
        return min($this->firstVector->getFloorY(), $this->secondVector->getFloorY());
    }

    public function getMinZ(): int {
        return min($this->firstVector->getFloorZ(), $this->secondVector->getFloorZ());
    }

    public function getMaxX(): int {
        return max($this->firstVector->getFloorX(), $this->secondVector->getFloorX());
    }

    public function getMaxY(): int {
        return max($this->firstVector->getFloorY(), $this->secondVector->getFloorY());
    }

    public function getMaxZ(): int {
        return max($this->firstVector->getFloorZ(), $this->secondVector->getFloorZ());
    }

    public function isInside(Vector3 $position): bool {
        return $position->x >= $this->getMinX() && $position->x <= $this->getMaxX() and
            $position->z >= $this->getMinZ() && $position->z <= $this->getMaxZ();
    }

    public function jsonSerialize(): array {
        return [
            "first_x" => $this->firstVector->getFloorX(),
            "first_y" => $this->firstVector->getFloorY(),
            "first_z" => $this->firstVector->getFloorZ(),
            "second_x" => $this->secondVector->getFloorX(),
            "second_y" => $this->secondVector->getFloorY(),
            "second_z" => $this->secondVector->getFloorZ()
        ];
    }

}