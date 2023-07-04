<?php

namespace xoapp\warps;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\World;

class WarpUtils {

    public static function getPlayersCount(string $worldName): int
    {
        return self::isWorldLoaded($worldName) ? count(self::getWorld($worldName)->getPlayers()) : 0;
    }

    public static function isWorldLoaded(string $worldName): bool
    {
        return Server::getInstance()->getWorldManager()->isWorldLoaded($worldName);
    }

    public static function getWorld(string $worldName): World
    {
        return Server::getInstance()->getWorldManager()->getWorldByName($worldName);
    }

    public static function getMessageManager(string $message): string
    {
        $data = new Config(Loader::getInstance()->getDataFolder() . "messages.yml", Config::YAML);
        return $data->get($message);
    }
}