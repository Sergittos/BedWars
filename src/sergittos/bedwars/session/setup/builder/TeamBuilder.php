<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\builder;


use pocketmine\math\Vector3;
use sergittos\bedwars\game\generator\presets\GoldGenerator;
use sergittos\bedwars\game\generator\presets\IronGenerator;
use sergittos\bedwars\game\team\Area;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\game\team\TeamProperties;
use sergittos\bedwars\utils\ColorUtils;
use function strtoupper;

class TeamBuilder {
    use TeamProperties;

    private Vector3 $generatorPosition;

    public function __construct(string $name) {
        $this->name = $name;
        $this->color = ColorUtils::translate("{" . strtoupper($name) . "}");
    }

    public function setSpawnPoint(Vector3 $spawnPoint): void {
        $this->spawn_point = $spawnPoint;
    }

    public function setBedPosition(Vector3 $bedPosition): void {
        $this->bed_position = $bedPosition;
    }

    public function setZone(Area $zone): void {
        $this->zone = $zone;
    }

    public function setClaim(Area $claim): void {
        $this->claim = $claim;
    }

    public function setGeneratorPosition(Vector3 $generatorPosition): void {
        $this->generatorPosition = $generatorPosition;
    }

    public function canBeBuilt(): bool {
        return isset(
            $this->spawn_point,
            $this->bed_position,
            $this->zone,
            $this->claim,
            $this->generatorPosition
        );
    }

    public function build(MapBuilder $mapBuilder): Team {
        return new Team(
            $this->name,
            $mapBuilder->getPlayersPerTeam(),
            $this->spawn_point,
            $this->bed_position,
            $this->zone,
            $this->claim,
            [
                new IronGenerator($this->generatorPosition),
                new GoldGenerator($this->generatorPosition)
            ]
        );
    }

}