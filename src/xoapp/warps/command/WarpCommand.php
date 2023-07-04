<?php

namespace xoapp\warps\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xoapp\warps\data\DataManager;
use xoapp\warps\forms\WarpForm;

class WarpCommand extends Command {

    public function __construct()
    {
        parent::__construct("warp");

        $this->setDescription("Warp Command");

        $this->setPermission("sopas.warps.command");

        $this->setAliases(["swarps", "warps"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$player instanceof Player) return;

        if ($this->testPermission($player)) {
            $player->sendForm(new WarpForm());
            return;
        }

        if (!isset($args[0])) {
            $player->sendForm(new WarpForm());
            return;
        }

        switch ($args[0]) {
            case "create":
                if (!isset($args[1])) {
                    $player->sendMessage(TextFormat::colorize("&cUsage /warps create (name) (permission = null)"));
                    return;
                }

                if (DataManager::getInstance()->isRegistered($args[1])) {
                    $player->sendMessage(TextFormat::colorize("&cThis warp is already registered!"));
                    return;
                }

                if (!isset($args[2])) {
                    DataManager::getInstance()->register($args[1], $player->getLocation());
                    $player->sendMessage(TextFormat::colorize("&cThis warp has been successfully registered"));
                    return;
                }

                DataManager::getInstance()->register($args[1], $player->getLocation(), $args[2]);
                $player->sendMessage(TextFormat::colorize("&cThis warp has been successfully registered"));
                break;
            case "remove":
                if (!isset($args[1])) {
                    $player->sendMessage(TextFormat::colorize("&cUsage /warps remove (name)"));
                    return;
                }

                if (!DataManager::getInstance()->isRegistered($args[1])) {
                    $player->sendMessage(TextFormat::colorize("&cThis warp is not registered!"));
                    return;
                }

                DataManager::getInstance()->unregister($args[1]);
                $player->sendMessage(TextFormat::colorize("&cThis warp has successfully deleted"));
                break;

            default:
                $player->sendMessage(TextFormat::colorize("&cUsage /warps create (name) (permission = null)"));
                $player->sendMessage(TextFormat::colorize("&cUsage /warps remove (name)"));
        }
    }
}