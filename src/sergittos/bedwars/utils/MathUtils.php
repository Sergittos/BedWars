<?php

declare(strict_types=1);


namespace sergittos\bedwars\utils;


use pocketmine\math\Vector2;
use pocketmine\world\Position;
use function atan2;

class MathUtils {

    static public function calculateYaw(Position $player_position, Position $entity_position): float {
        return self::toDegrees(atan2(
            $player_position->getZ() - $entity_position->getZ(),
            $player_position->getX() - $entity_position->getX()
        ));
    }

    static public function calculatePitch(Position $player_position, Position $entity_position): float {
        $player_vector = new Vector2($player_position->getX(), $player_position->getZ());
        $entity_vector = new Vector2($entity_position->getX(), $entity_position->getZ());

        return self::toDegrees(atan2(
            $player_vector->distance($entity_vector),
            $player_position->getY() - $entity_position->getY()
        ));
    }

    static private function toDegrees(float $radians): float {
        return $radians * 180 / M_PI - 90;
    }

}