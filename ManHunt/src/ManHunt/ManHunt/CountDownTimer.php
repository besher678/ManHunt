<?php

namespace Task;

use Task\Main;
use pocketmine\scheduler\Task;
use pocketmine\Player;

class CountDownTimer extends Task{
    public $timer = 5;

    public function __construct(Main $plugin, $player){
        $this->plugin = $plugin;
        $this->player = $player;
    }
    public function onRun($tick){
        $player = $this->plugin->getServer()->getPlayerExact($this->player);
        if ($player instanceof Player){
            if ($this->timer == 5){
                $player->sendMessage("§bGame starting in: §c5");
                $player->sendTitle("§c5");
            }
            if ($this->timer == 4){
                $player->sendMessage("§bGame starting in: §c4");
                $player->sendTitle("§c4");
            }
            if ($this->timer == 3){
                $player->sendMessage("§bGame starting in: §c3");
                $player->sendTitle("§c3");
            }
            if ($this->timer == 2){
                $player->sendMessage("§bGame starting in: §25");
                $player->sendTitle("§e2");
            }
            if ($this->timer == 1){
                $player->sendMessage("§bGame starting in: §a1");
                $player->sendTitle("§a1");
            }
            if ($this->timer == 0){
                $player->sendTitle("§bYou are a ");
                $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                if ($config->get("SR") == $player){
                    $this->plugin->getScheduler()->cancelTask($this->getTaskId());
                    $player->sendSubtitle("§aSpeedRunner");
                    $player->sendTip("§aStay alive and beat the game");
                } else {
                    $this->plugin->getScheduler()->cancelTask($this->getTaskId());
                    $$player->sendSubtitle("§aSpeedRunner");
                    $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                    $player->sendTip("§ckill: " . $config->get("SR"));
                }
            }
        }
        $this->timer--;
    }

}