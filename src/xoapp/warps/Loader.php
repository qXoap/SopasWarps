<?php

namespace xoapp\warps;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use xoapp\warps\command\WarpCommand;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onEnable(): void
    {
        self::setInstance($this);

        $this->getServer()->getCommandMap()->register("warp", new WarpCommand());

        $this->saveResource("messages.yml");
    }
}