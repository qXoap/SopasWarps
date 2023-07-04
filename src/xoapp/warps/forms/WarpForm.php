<?php

namespace xoapp\warps\forms;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xoapp\warps\data\DataManager;
use xoapp\warps\libs\Forms\FormAPI\SimpleForm;
use xoapp\warps\WarpUtils;

class WarpForm extends SimpleForm {

    public function __construct()
    {
        parent::__construct(function (Player $player, $data = null): void {
            if (is_null($data)) return;

            if (count(DataManager::getInstance()->getWarps()) < 0) {
                return;
            }

            if ($data === "close") {
                return;
            }

            $manager = DataManager::getInstance();
            $permission = $manager->getWarpData($data, "permission");

            if (!$permission == null) {
                if (!$player->hasPermission($permission)) {
                    $player->sendMessage((TextFormat::colorize(WarpUtils::getMessageManager("no-permission-message"))));
                    return;
                }
            }

            $position = DataManager::getInstance()->getWarpPosition($data);

            $player->teleport($position);

            $message = WarpUtils::getMessageManager("teleport-message");
            $message = str_replace("{warp}", $data, $message);
            $player->sendMessage(TextFormat::colorize($message));

        });

        $this->setTitle(WarpUtils::getMessageManager("form-title"));

        if (count(DataManager::getInstance()->getWarps()) < 0) {

            $this->addButton("Close", 0, "textures/ui/redX1");

        } else {

            foreach (DataManager::getInstance()->getWarps() as $warp) {
                $world = DataManager::getInstance()->getWarpData($warp, "world");
                $message = WarpUtils::getMessageManager("form-button");
                $message = str_replace("{players}", WarpUtils::getPlayersCount($world), $message);
                $message = str_replace("{name}", $warp, $message);
                $this->addButton(TextFormat::colorize($message), 0, "", $warp);
            }

            $this->addButton("Close", 0, "textures/ui/redX1", "close");
        }
    }
}