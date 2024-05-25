<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team;


use JsonSerializable;
use pocketmine\block\Air;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\GameMode;
use pocketmine\utils\Utils;
use pocketmine\world\format\Chunk;
use pocketmine\world\Position;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\team\upgrade\trap\AlarmTrap;
use sergittos\bedwars\game\team\upgrade\trap\Trap;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;
use function array_search;
use function count;
use function in_array;
use function strtoupper;

class Team implements JsonSerializable {
    use TeamProperties;

    private int $capacity;

    private Upgrades $upgrades;

    private bool $bedDestroyed = false;

    /** @var Generator[] */
    private array $generators;

    /** @var Session[] */
    private array $members = [];

    /**
     * @param Generator[] $generators
     */
    public function __construct(string $name, int $capacity, Vector3 $spawnPoint, Vector3 $bedPosition, Area $zone, Area $claim, array $generators) {
        $this->name = $name;
        $this->color = ColorUtils::translate("{" . strtoupper($name) . "}");
        $this->capacity = $capacity;
        $this->spawnPoint = $spawnPoint;
        $this->bedPosition = $bedPosition;
        $this->zone = $zone;
        $this->claim = $claim;
        $this->generators = $generators;
        $this->upgrades = new Upgrades();
    }

    public function getColoredName(): string {
        return $this->color . $this->name;
    }

    public function getFirstLetter(): string {
        return $this->name[0];
    }

    public function getUpgrades(): Upgrades {
        return $this->upgrades;
    }

    public function isBedDestroyed(): bool {
        return $this->bedDestroyed;
    }

    /**
     * @return Generator[]
     */
    public function getGenerators(): array {
        return $this->generators;
    }

    /**
     * @return Session[]
     */
    public function getMembers(): array {
        return $this->members;
    }

    public function getMembersCount(): int {
        return count($this->members);
    }

    public function isFull(): bool {
        return $this->getMembersCount() >= $this->capacity;
    }

    public function isEmpty(): bool {
        return empty($this->members);
    }

    public function isAlive(): bool {
        return !$this->isEmpty();
    }

    public function hasMember(Session $session): bool {
        return in_array($session, $this->members, true);
    }

    public function destroyBed(Game $game, bool $breakBlock = true, bool $silent = false): void {
        $this->bedDestroyed = true;

        if($breakBlock) {
            $this->breakBedBlock($game);
        }

        if(!$silent) {
            foreach($game->getPlayersAndSpectators() as $session) {
                if(!$session->hasTeam() or $session->getTeam()->getName() !== $this->name) {
                    $session->playSound("mob.enderdragon.growl");
                }
            }

            foreach($this->members as $member) {
                $member->title("{RED}BED DESTROYED!", "{WHITE}You will no longer respawn!", 7, 30, 15);
                $member->playSound("mob.wither.death");
            }
        }
    }

    private function breakBedBlock(Game $game): void {
        $world = $game->getWorld();
        $position = Position::fromObject($this->bedPosition, $world);

        $world->requestChunkPopulation($position->getX() >> Chunk::COORD_BIT_SIZE, $position->getZ() >> Chunk::COORD_BIT_SIZE, null)->onCompletion(
            function() use ($world, $position) {
                $blocks = $world->getBlock($position)->getAffectedBlocks();
                foreach($blocks as $block) {
                    if($block instanceof Air) {
                        continue;
                    }

                    $block->onBreak(VanillaItems::AIR());
                }
            },
            function() {}
        );
    }

    public function tickGenerators(Game $game): void {
        if($this->isAlive()) {
            foreach($this->generators as $generator) {
                $generator->tick($game);
            }
        }
    }

    public function addGenerator(Generator $generator): void {
        $this->generators[] = $generator;
    }

    public function addMember(Session $session): void {
        $this->members[] = $session;

        $session->setTeam($this);
        $session->clearAllInventories();
        $session->getGameSettings()->apply();

        $player = $session->getPlayer();
        $player->setNameTag($this->color . $this->getFirstLetter() . " " . $player->getName());
        $player->setGamemode(GameMode::SURVIVAL());
        $player->teleport(Position::fromObject($this->spawnPoint, $session->getGame()->getWorld()));
    }

    public function removeMember(Session $session): void {
        unset($this->members[array_search($session, $this->members, true)]);

        if($this->isEmpty()) {
            $this->destroyBed($session->getGame());
        }

        $session->setTeam(null);
    }

    public function notifyTrap(Trap $trap, Team $team): void {
        $name = $trap->getName();
        if($trap instanceof AlarmTrap) {
            $title = "{BOLD}{RED}ALARM!!!";
            $subtitle = "{WHITE}" . $name . " set off by " . $team->getColoredName() . "{WHITE} team!";
            $message = "{BOLD}{RED}" . $name . " set off by " . $team->getColoredName() . "{RED} team!";
        } else {
            $title = "{RED}TRAP TRIGGERED!";
            $subtitle = "{WHITE}Your $name has been set off!";
            $message = "{BOLD}{RED}" . $name . " was set off!";
        }

        foreach($this->members as $member) {
            $member->title($title, $subtitle);
            $member->message($message);
            // TODO: Play sound
        }
    }

    public function jsonSerialize(): array {
        return [
            "name" => $this->name,
            "spawn_point" => [
                "x" => $this->spawnPoint->getX(),
                "y" => $this->spawnPoint->getY(),
                "z" => $this->spawnPoint->getZ()
            ],
            "bed" => [
                "x" => $this->bedPosition->getX(),
                "y" => $this->bedPosition->getY(),
                "z" => $this->bedPosition->getZ()
            ],
            "generator" => [
                "x" => $this->generators[0]->getPosition()->getX(),
                "y" => $this->generators[0]->getPosition()->getY(),
                "z" => $this->generators[0]->getPosition()->getZ()
            ],
            "areas" => [
                "zone" => $this->zone->jsonSerialize(),
                "claim" => $this->claim->jsonSerialize()
            ]
        ];
    }

    public function __clone(): void {
        $this->upgrades = clone $this->upgrades;
        $this->generators = Utils::cloneObjectArray($this->generators);
    }

}