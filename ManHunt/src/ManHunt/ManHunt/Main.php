<?php

namespace ManHunt\ManHunt;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

class Main extends PluginBase
{
    public $speedrunner = [];
    public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args): bool
    {

        switch ($cmd->getName()) {
            case "hvs":
                if ($sender instanceof Player) {
                    if ($sender->hasPermission("HvS.use")) {
                        $this->openHvS($sender);
                    } else {
                        $sender->sendMessage("§cYou do not have permission to execute this command!");
                    }
                } else {
                    $sender->sendMessage("§cPlease use this command in-game!");
                    break;
                }
        }
        return true;
    }
    public function openHvS($player)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->openHunter($player);
                    break;
                case 1:
                    break;
            }
        });
        $form->setTitle("§l§bHvSUI");
        $dname = $player->getDisplayName();
        $form->setContent("§ePlease chose a functon!");
        $form->addButton("§l§aSet Hunter!", 0, "textures/items/diamond_sword");
        $form->addButton("§cClose", 0, "textures/ui/realms_red_x");
        $form->sendToPlayer($player);
        return $form;
    }
    public function openHunter($player)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            $list = [];
            foreach ($this->getServer()->getOnlinePlayers() as $players) {
                $list[] = $players->getName();
            }
            $target = $this->playerList[$player->getName()][$data[0]];
            $this->speedrunner[$target] = $target;
            $player->sendMessage("§eSet Hunter to §b" . $target);
        });
        $list = [];
        foreach ($this->getServer()->getOnlinePlayers() as $players) {
            $list[] = $players->getName();
        }
        $this->playerList[$player->getName()] = $list;
        $form->setTitle("§l§bHunterUI");
        $form->addDropdown("Pick a hunter!", $list);
        $form->sendToPlayer($player);
        return $form;
    }
}
