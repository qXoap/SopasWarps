<?php

namespace xoapp\warps\data;

use pocketmine\entity\Location;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;
use xoapp\warps\Loader;
use xoapp\warps\WarpUtils;

class DataManager {
    use SingletonTrait;

    private Config $config;

    public function __construct()
    {
        self::setInstance($this);
        $this->config = new Config(Loader::getInstance()->getDataFolder() . "warps.json", Config::JSON);
    }

    public function getWarps(): array
    {
        return $this->config->getAll(true);
    }

    public function getWarp(string $name): string
    {
        return $this->config->get($name);
    }

    public function getWarpData(string $name, string $array): string
    {
        return $this->config->get($name)[$array];
    }


    public function register(string $name, Location $location, string $permission = null): void
    {
        $this->config->set($name, [
            "x" => $location->getX(),
            "y" => $location->getY(),
            "z" => $location->getZ(),
            "world" => $location->getWorld()->getFolderName(),
            "yaw" => $location->getYaw(),
            "pitch" => $location->getPitch(),
            "permission" => $permission
        ]);
        $this->config->save();
    }

    public function unregister(string $name): void
    {
        $this->config->remove($name);
        $this->config->save();
    }

    public function isRegistered(string $name): bool
    {
        return $this->config->exists($name);
    }

    public function getWarpPosition(string $name): Location|Position
    {
        $x = $this->config->get($name)["x"];
        $y = $this->config->get($name)["y"];
        $z = $this->config->get($name)["z"];
        $world = WarpUtils::getWorld($this->config->get($name)["world"]);
        $yaw = $this->config->get($name)["yaw"];
        $pitch = $this->config->get($name)["pitch"];

        if (!(is_null($yaw) || is_null($pitch))) {
            return new Location($x, $y, $z, $world, $yaw, $pitch);
        }

        return new Position($x, $y, $z, $world);
    }
}