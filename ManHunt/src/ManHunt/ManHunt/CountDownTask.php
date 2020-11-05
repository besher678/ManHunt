<?php

namespace ManHunt\ManHunt;

use pocketmine\scheduler\Task;
use ManHunt\ManHunt\Main;
use pocketmine\Player;
use pocketmine\utils\Config;

class CountDownTask extends Task{
    public $timer = 6;

    public function __construct(Main $plugin, Player $player){
        $this->plugin = $plugin;
        $this->player = $player;
    }
    public function onRun($tick){
        @mkdir($this->plugin->getDataFolder());
        $this->plugin->getResource("config.yml");
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
            if ($player instanceof Player){
                if ($this->timer == 5){
                    $player->sendMessage("§aGame starting in: §45");
                    $player->sendTitle("§45");
                }
                if ($this->timer == 4){
                    $player->sendMessage("§aGame starting in: §44");
                    $player->sendTitle("§44");
                }
                if ($this->timer == 3){
                    $player->sendMessage("§aGame starting in: §c3");
                    $player->sendTitle("§c3");
                }
                if ($this->timer == 2){
                    $player->sendMessage("§aGame starting in: §65");
                    $player->sendTitle("§62");
                }
                if ($this->timer == 1){
                    $player->sendMessage("§aGame starting in: §21");
                    $player->sendTitle("§21");
                }
                if ($this->timer == 0){
                    $player->sendTitle("§bYou are a ");
                    $config = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
                    if ($config->get("SR") == $player){
                        $this->plugin->getScheduler()->cancelTask($this->getTaskId());
                        $player->sendSubtitle("§aSpeedRunner");
                        $player->sendTip("§aStay alive and beat the game");
                        $name = $player->getName();
                        $player->sendMessage("§aThe game has started!\n Good luck " . $name);
                    } else {
                        $this->plugin->getScheduler()->cancelTask($this->getTaskId());
                        $player->sendSubtitle("§cHunter");
                        $config = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
                        $player->sendTip("§ckill: " . $config->get("SR"));

                        $name = $player->getName();
                        $player->sendMessage("§aThe game has started!\n Good luck " . $name);
                    }
                }
            }
        }
        $this->timer--;
    }
}
