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

    private Vector3 $generator_position;

    public function __construct(string $name) {
        $this->name = $name;
        $this->color = ColorUtils::translate("{" . strtoupper($name) . "}");
    }

    public function setSpawnPoint(Vector3 $spawn_point): void {
        $this->spawn_point = $spawn_point;
    }

    public function setBedPosition(Vector3 $bed_position): void {
        $this->bed_position = $bed_position;
    }

    public function setZone(Area $zone): void {
        $this->zone = $zone;
    }

    public function setClaim(Area $claim): void {
        $this->claim = $claim;
    }

    public function setGeneratorPosition(Vector3 $generator_position): void {
        $this->generator_position = $generator_position;
    }

    public function canBeBuilt(): bool {
        return isset(
            $this->spawn_point,
            $this->bed_position,
            $this->zone,
            $this->claim,
            $this->generator_position
        );
    }

    public function build(MapBuilder $map_builder): Team {
        return new Team(
            $this->name,
            $map_builder->getPlayersPerTeam(),
            $this->spawn_point,
            $this->bed_position,
            $this->zone,
            $this->claim,
            [
                new IronGenerator($this->generator_position),
                new GoldGenerator($this->generator_position)
            ]
        );
    }

}