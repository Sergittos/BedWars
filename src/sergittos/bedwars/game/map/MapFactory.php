<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\ServerException;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\generator\presets\GoldGenerator;
use sergittos\bedwars\game\generator\presets\IronGenerator;
use sergittos\bedwars\game\team\Area;
use sergittos\bedwars\game\team\Team;
use function array_filter;
use function array_map;
use function array_rand;
use function file_get_contents;
use function json_decode;
use function strtolower;
use function strtoupper;
use function ucfirst;

class MapFactory {

    /** @var Map[] */
    static private array $maps = [];

    static public function init(): void {
        foreach(json_decode(file_get_contents(BedWars::getInstance()->getDataFolder() . "maps.json"), true) as $mapData) {
            $name = $mapData["name"];
            $worldName = $mapData["waiting_world"];
            $positionData = $mapData["spectator_spawn_position"];
            $playersPerTeam = $mapData["players_per_team"];
            $capacity = $mapData["max_capacity"];

            $worldManager = Server::getInstance()->getWorldManager();
            if(!$worldManager->loadWorld($worldName)) {
                throw new ServerException("Couldn't load " . $name . " map because the world doesn't exist. (" . $worldName . ")");
            }

            $waitingWorld = $worldManager->getWorldByName($worldName);
            $spectatorSpawnPosition = new Vector3($positionData["x"], $positionData["y"], $positionData["z"]);

            $generators = [];
            foreach($mapData["generators"] as $type => $generatorData) {
                foreach($generatorData as $position) {
                    $generators[] = GeneratorType::toGenerator(self::createVector($position), GeneratorType::fromString($type));
                }
            }

            $shopLocations = self::createPositions($mapData["shop_positions"]);
            $upgradesLocations = self::createPositions($mapData["upgrades_positions"]);

            $teams = [];
            foreach($mapData["teams"] as $data) {
                $generatorData = $data["generator"];
                $areasData = $data["areas"];
                $bedData = $data["bed"];

                $teamGenerators = [];
                $teamGenerators[] = new IronGenerator(self::createVector($generatorData));
                $teamGenerators[] = new GoldGenerator(self::createVector($generatorData));

                $teams[] = new Team(
                    ucfirst($data["name"]), $playersPerTeam,
                    self::createVector($data["spawn_point"]), new Vector3($bedData["x"], $bedData["y"] + 1, $bedData["z"]),
                    Area::fromData($areasData["zone"]), Area::fromData($areasData["claim"]), $teamGenerators
                );
            }

            self::addMap(new Map(
                $name, $spectatorSpawnPosition, $playersPerTeam,
                $capacity, $waitingWorld, $generators, $teams,
                $shopLocations, $upgradesLocations
            ));
        }
    }

    /**
     * @return Map[]
     */
    static public function getMaps(): array {
        return self::$maps;
    }

    /**
     * @return Map[]
     */
    static public function getMapsByPlayers(int $playersPerTeam): array {
        $maps = [];
        foreach(self::$maps as $map) {
            if($map->getPlayersPerTeam() === $playersPerTeam) {
                $maps[] = $map;
            }
        }
        return $maps;
    }

    static public function getRandomMap(int $playersPerTeam): ?Map {
        $maps = self::getMapsByPlayers($playersPerTeam);
        return $maps[array_rand($maps)];
    }

    /**
     * @return Map[]
     */
    static public function getMapsByName(string $name): array {
        $maps = [];
        foreach(self::$maps as $map) {
            if(strtolower($map->getName()) === strtolower($name)) {
                $maps[] = $map;
            }
        }
        return $maps;
    }

    static public function getMapByName(string $name): ?Map {
        foreach(self::$maps as $map) {
            if(strtolower($map->getName()) === strtolower($name)) {
                return $map;
            }
        }
        return null;
    }

    static public function getMapById(string $id): ?Map {
        return self::$maps[$id] ?? null;
    }

    static public function addMap(Map $map): void {
        self::$maps[$map->getId()] = $map;
    }

    static public function removeMap(string $id): void {
        unset(self::$maps[$id]);
    }

    static private function createVector(array $data): Vector3 {
        return new Vector3($data["x"] + 0.5, $data["y"] + 1.5, $data["z"] + 0.5);
    }

    static private function createPositions(array $positions): array {
        return array_map(function(array $data) {
            return self::createVector($data);
        }, $positions);
    }

}