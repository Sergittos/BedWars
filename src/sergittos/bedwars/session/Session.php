<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\InvisibilityEffect;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\BossBarColor;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\game\stage\EndingStage;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\item\BedwarsItemRegistry;
use sergittos\bedwars\session\scoreboard\layout\Layout;
use sergittos\bedwars\session\scoreboard\layout\LobbyLayout;
use sergittos\bedwars\session\scoreboard\Scoreboard;
use sergittos\bedwars\session\settings\GameSettings;
use sergittos\bedwars\session\settings\SpectatorSettings;
use sergittos\bedwars\session\setup\MapSetup;
use sergittos\bedwars\utils\ColorUtils;
use sergittos\bedwars\utils\ConfigGetter;
use sergittos\bedwars\utils\message\MessageContainer;
use function in_array;
use function strtoupper;
use function time;

class Session {

    public const RESPAWN_DURATION = 5;

    private Player $player;
    private Scoreboard $scoreboard;

    private GameSettings $gameSettings;
    private SpectatorSettings $spectatorSettings;

    private ?Game $game = null;
    private ?Team $team = null;

    private ?MapSetup $mapSetup = null;

    private ?Session $lastSessionHit = null;
    private ?Session $trackingSession = null;

    private ?int $respawnTime = null;
    private ?int $lastSessionHitTime = null;

    private int $coins = 0;
    private int $kills = 0;
    private int $wins = 0;

    private bool $loadingData;

    public function __construct(Player $player) {
        $this->player = $player;
        $this->scoreboard = new Scoreboard($this);
        $this->gameSettings = new GameSettings($this);

        $this->load();
        $this->setEffectHooks();
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getUsername(): string {
        return $this->player->getName();
    }

    public function getColoredUsername(): string {
        $username = $this->getUsername();
        if($this->hasTeam()) {
            return $this->team->getColor() . $username;
        }
        return $username;
    }

    public function getGameSettings(): GameSettings {
        return $this->gameSettings;
    }

    public function getSpectatorSettings(): SpectatorSettings {
        return $this->spectatorSettings;
    }

    public function getGame(): ?Game {
        return $this->game;
    }

    public function getTeam(): ?Team {
        return $this->team;
    }

    public function getMapSetup(): ?MapSetup {
        return $this->mapSetup;
    }

    public function getLastSessionHit(): ?Session {
        if($this->lastSessionHitTime === null) {
            return null;
        }
        if(time() - $this->lastSessionHitTime <= 10) {
            return $this->lastSessionHit;
        }
        return null;
    }

    public function getTrackingSession(): ?Session {
        return $this->trackingSession;
    }

    public function getCoins(): int {
        return $this->coins;
    }

    public function getKills(): int {
        return $this->kills;
    }

    public function getWins(): int {
        return $this->wins;
    }

    public function resetSettings(): void {
        $this->gameSettings = new GameSettings($this);
        $this->respawnTime = null;
    }

    public function setSpectatorSettings(SpectatorSettings $spectatorSettings): void {
        $this->spectatorSettings = $spectatorSettings;
    }

    public function setScoreboardLayout(Layout $layout): void {
        $this->scoreboard->setLayout($layout);
    }

    public function setGame(?Game $game): void {
        $this->game = $game;
        if($this->game === null) {
            $this->team = null;
        }
    }

    public function setTeam(?Team $team): void {
        $this->team = $team;
    }

    public function setMapSetup(?MapSetup $mapSetup): void {
        $this->mapSetup = $mapSetup;
    }

    public function setLastSessionHit(?Session $lastSessionHit): void {
        $this->lastSessionHit = $lastSessionHit;
        $this->lastSessionHitTime = time();
    }

    public function setTrackingSession(?Session $trackingSession): void {
        $this->trackingSession = $trackingSession;
        $this->updateCompassDirection();
    }

    public function updateCompassDirection(): void {
        $this->player->getNetworkSession()->syncWorldSpawnPoint(
            $this->trackingSession !== null ? $this->trackingSession->getPlayer()->getPosition() : $this->player->getWorld()->getSpawnLocation()
        );
    }

    public function updateScoreboard(): void {
        $this->scoreboard->update();
    }

    public function attemptToRespawn(): void {
        if($this->respawnTime <= 0) {
            $this->respawnTime = null;
            $this->respawn();
            return;
        }

        if($this->respawnTime < 5) {
            $message = "{YELLOW}You will respawn in {RED}" . $this->respawnTime . " {YELLOW}" . ($this->respawnTime === 1 ? "second" : "seconds") . "!";
            $this->title("{RED}YOU DIED!", $message);
            $this->message($message);
        }

        $this->respawnTime--;
    }

    private function respawn(): void {
        $this->message("{YELLOW}You have respawned!");
        $this->title("{GREEN}RESPAWNED!", "", 7, 21, 7);

        $this->gameSettings->apply();
        $this->player->setGamemode(GameMode::SURVIVAL);
        $this->player->setHealth($this->player->getMaxHealth());
        $this->player->teleport($this->team->getSpawnPoint());
    }

    public function setCoins(int $coins): void {
        $this->coins = $coins;

        if(!$this->loadingData) {
            BedWars::getInstance()->getProvider()->updateCoins($this);
        }
    }

    public function addCoins(int $coins): void {
        $this->setCoins($this->coins + $coins);
    }

    public function setKills(int $kills): void {
        $this->kills = $kills;

        if(!$this->loadingData) {
            BedWars::getInstance()->getProvider()->updateKills($this);
        }
    }

    public function addKill(): void {
        $this->setKills($this->kills + 1);
    }

    public function setWins(int $wins): void {
        $this->wins = $wins;

        if(!$this->loadingData) {
            BedWars::getInstance()->getProvider()->updateWins($this);
        }
    }

    public function addWin(): void {
        $this->setWins($this->wins + 1);
    }

    public function isPlaying(): bool {
        return $this->hasGame() and $this->game->isPlaying($this);
    }

    public function isSpectator(): bool {
        return $this->hasGame() and $this->game->isSpectator($this);
    }

    public function hasGame(): bool {
        return $this->game !== null;
    }

    public function hasTeam(): bool {
        return $this->team !== null;
    }

    public function isCreatingMap(): bool {
        return $this->mapSetup !== null;
    }

    public function isRespawning(): bool {
        return $this->respawnTime !== null;
    }

    public function isOnline(): bool {
        return $this->player->isOnline();
    }

    public function showBossBar(string $title): void {
        $this->hideBossBar();
        $this->sendDataPacket(
            BossEventPacket::show($this->player->getId(), ColorUtils::translate($title), 10, false, 0, BossBarColor::BLUE)
        );
    }

    public function hideBossBar(): void {
        $this->sendDataPacket(BossEventPacket::hide($this->player->getId()));
    }

    public function sendDataPacket(ClientboundPacket $packet): void {
        $this->player->getNetworkSession()->sendDataPacket($packet);
    }

    public function playSound(string $sound, float $volume = 1.0, float $pitch = 1.0): void {
        $location = $this->player->getLocation();
        $this->sendDataPacket(PlaySoundPacket::create(
            $sound,
            $location->getX(),
            $location->getY(),
            $location->getZ(),
            $volume,
            $pitch
        ));
    }

    public function clearAllInventories(): void {
        $this->clearCommonInventories();
        $this->player->getEnderInventory()->clearAll();
    }

    public function clearCommonInventories(): void {
        $this->player->getCursorInventory()->clearAll();
        $this->player->getOffHandInventory()->clearAll();
        $this->player->getArmorInventory()->clearAll();
        $this->player->getInventory()->clearAll();
    }

    public function giveCreatingMapItems(): void {
        $this->clearAllInventories();

        $inventory = $this->player->getInventory();
        $inventory->setItem(0, BedwarsItemRegistry::CONFIGURATION());
        $inventory->setItem(4, BedwarsItemRegistry::CREATE_MAP());
        $inventory->setItem(8, BedwarsItemRegistry::EXIT_SETUP());
    }

    public function giveWaitingItems(): void {
        $this->clearAllInventories();
        $this->player->getInventory()->setItem(8, BedwarsItemRegistry::LEAVE_GAME());
    }

    public function giveSpectatorItems(): void {
        $this->clearAllInventories();

        $inventory = $this->player->getInventory();
        $inventory->setItem(0, BedwarsItemRegistry::TELEPORTER());
        $inventory->setItem(4, BedwarsItemRegistry::SPECTATOR_SETTINGS());
        $inventory->setItem(7, BedwarsItemRegistry::PLAY_AGAIN());
        $inventory->setItem(8, BedwarsItemRegistry::RETURN_TO_LOBBY());
    }

    public function addEffect(EffectInstance $effectInstance): void {
        $this->player->getEffects()->add($effectInstance);
    }

    public function teleportToWaitingWorld(): void {
        $this->player->teleport($this->game->getMap()->getWaitingWorld()->getSafeSpawn());
    }

    public function teleportToHub(): void {
        $this->player->getEffects()->clear();
        $this->player->setGamemode(GameMode::ADVENTURE);
        $this->player->setHealth($this->player->getMaxHealth());
        $this->player->setNameTag($this->player->getDisplayName());
        $this->player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());

        $this->clearAllInventories();
        $this->setTrackingSession(null);
        $this->setScoreboardLayout(new LobbyLayout());
        $this->showBossBar("{DARK_GREEN}You are playing on {AQUA}" . strtoupper(ConfigGetter::getIP()));
    }

    public function kill(int $cause): void {
        $killerSession = $this->getLastSessionHit();
        $sessionUsername = $this->getColoredUsername();

        if($killerSession !== null) {
            $killerSession->addCoins(8); // TODO: Check for final kill
            $killerSession->addKill();
            $killerSession->playSound("random.orb");

            $killerUsername = $killerSession->getColoredUsername();
            if($cause === EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
                $this->game->broadcastMessage($sessionUsername . " {GRAY}was killed by " . $killerUsername . "{GRAY}.");
            } elseif($cause === EntityDamageEvent::CAUSE_VOID) {
                $this->game->broadcastMessage($sessionUsername . " {GRAY}was knocked into the void by " . $killerUsername . "{GRAY}.");
            }

            if(!$killerSession->isSpectator()) {
                foreach($this->player->getInventory()->getContents() as $item) {
                    if(!in_array($item->getTypeId(), [ItemTypeIds::IRON_INGOT, ItemTypeIds::GOLD_INGOT, ItemTypeIds::DIAMOND, ItemTypeIds::EMERALD])) {
                        continue;
                    }
                    $killerSession->getPlayer()->getInventory()->addItem($item);
                }
            }
        }

        if($cause === EntityDamageEvent::CAUSE_VOID and $killerSession === null) {
            $this->game->broadcastMessage($sessionUsername . " {GRAY}fell to the void.");
        }

        $this->player->getEffects()->clear();
        $this->player->teleport($this->game->getMap()->getSpectatorSpawnPosition());
        $this->player->setGamemode(GameMode::SPECTATOR);

        $this->gameSettings->decreasePickaxeTier();
        $this->gameSettings->decreaseAxeTier();

        if($this->hasTeam() and $this->team->isBedDestroyed()) {
            $this->game->removePlayer($this, false, true);
            return;
        } elseif($this->game->getStage() instanceof EndingStage) {
            return;
        }
        $this->respawnTime = self::RESPAWN_DURATION;

        $this->clearCommonInventories();
        $this->title(
            "{RED}YOU DIED!",
            $message = "{YELLOW}You will respawn in {RED}" . self::RESPAWN_DURATION . " {YELLOW}seconds!", 0, 41
        );
        $this->message($message);
    }

    private function setEffectHooks(): void {
        $effects = $this->player->getEffects();

        $effects->getEffectAddHooks()->add(function(EffectInstance $effectInstance): void {
            if($effectInstance->getType() instanceof InvisibilityEffect and $this->isPlaying()) {
                $this->vanish();
            }
        });
        $effects->getEffectRemoveHooks()->add(function(EffectInstance $effectInstance): void {
            if($effectInstance->getType() instanceof InvisibilityEffect and $this->isPlaying()) {
                $this->unvanish();
            }
        });
    }

    private function vanish(): void {
        $id = $this->player->getId();
        $item = ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet(VanillaItems::AIR()));
        $slot = $this->player->getInventory()->getHeldItemIndex();

        foreach($this->game->getPlayers() as $session) {
            $session = $session->getPlayer()->getNetworkSession();
            $session->sendDataPacket(MobEquipmentPacket::create($id, $item, $slot, $slot, ContainerIds::INVENTORY));
            $session->sendDataPacket(MobArmorEquipmentPacket::create($id, $item, $item, $item, $item));
        }
    }

    private function unvanish(): void {
        foreach($this->game->getPlayers() as $session) {
            $networkSession = $session->getPlayer()->getNetworkSession();
            $broadcaster = $networkSession->getEntityEventBroadcaster();

            $broadcaster->onMobArmorChange([$networkSession], $this->player);
            $broadcaster->onMobMainHandItemChange([$networkSession], $this->player);
        }
    }

    public function load(): void {
        $this->loadingData = true;
        BedWars::getInstance()->getProvider()->loadSession($this);
        $this->loadingData = false;
    }

    public function save(): void {
        BedWars::getInstance()->getProvider()->saveSession($this);
    }

    public function title(string $title, ?string $subtitle = null, int $fadeIn = 0, int $stay = 21, int $fadeOut = 0): void {
        $this->player->sendTitle($title, $subtitle ?? "", $fadeIn, $stay, $fadeOut);
    }

    public function message(string $container): void {
        $this->player->sendMessage($container);
    }

}
