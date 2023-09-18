<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\team;


use pocketmine\block\Air;
use pocketmine\block\utils\DyeColor;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\GameMode;
use pocketmine\world\Position;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;
use function array_search;
use function count;
use function in_array;

class Team {

    private string $name;
    private string $color;
    private int $capacity;

    private Upgrades $upgrades;

    private Vector3 $spawn_point;
    private Vector3 $bed_position;

    private Area $zone;
    private Area $claim;

    private bool $bed_destroyed = false;

    /** @var Generator[] */
    private array $generators;

    /** @var Session[] */
    private array $members = [];

    /**
     * @param Generator[] $generators
     */
    public function __construct(string $name, string $color, int $capacity, Vector3 $spawn_point, Vector3 $bed_position, Area $zone, Area $claim, array $generators) {
        $this->name = $name;
        $this->color = ColorUtils::translate($color);
        $this->capacity = $capacity;
        $this->spawn_point = $spawn_point;
        $this->bed_position = $bed_position;
        $this->zone = $zone;
        $this->claim = $claim;
        $this->generators = $generators;
        $this->upgrades = new Upgrades();
    }

    public function getName(): string {
        return $this->name;
    }

    public function getColoredName(): string {
        return $this->color . $this->name;
    }

    public function getFirstLetter(): string {
        return $this->name[0];
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getDyeColor(): DyeColor {
        return ColorUtils::getDye($this->color);
    }

    public function getSpawnPoint(): Vector3 {
        return $this->spawn_point;
    }

    public function getBedPosition(): Vector3 {
        return $this->bed_position;
    }

    public function getZone(): Area {
        return $this->zone;
    }

    public function getClaim(): Area {
        return $this->claim;
    }

    public function getUpgrades(): Upgrades {
        return $this->upgrades;
    }

    public function isBedDestroyed(): bool {
        return $this->bed_destroyed;
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

    public function destroyBed(): void {
        $this->bed_destroyed = true;

        foreach($this->members as $member) {
            $member->title("{RED}BED DESTROYED!", "{WHITE}You will no longer respawn!", 7, 30, 15);
            $member->playSound("mob.enderdragon.growl");
        }
    }

    public function breakBedBlock(Game $game): void {
        $world = $game->getWorld();
        $position = Position::fromObject($this->bed_position, $world);

        $blocks = $world->getBlock($position)->getAffectedBlocks();
        foreach($blocks as $block) {
            if($block instanceof Air) {
                continue;
            }

            $block->onBreak(VanillaItems::AIR());
        }
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
        $session->clearInventories();
        $session->getGameSettings()->apply();

        $player = $session->getPlayer();
        $player->setNameTag($this->color . $this->getFirstLetter() . " " . $player->getName());
        $player->setGamemode(GameMode::SURVIVAL());
        $player->teleport(Position::fromObject($this->spawn_point, $session->getGame()->getWorld()));
    }

    public function removeMember(Session $session): void {
        unset($this->members[array_search($session, $this->members, true)]);

        if($this->isEmpty()) {
            $this->bed_destroyed = true;
            $this->breakBedBlock($session->getGame());
        }

        $session->setTeam(null);
    }

    public function reset(): void {
        $this->bed_destroyed = false;
        $this->upgrades = new Upgrades();
        $this->members = [];
    }

}