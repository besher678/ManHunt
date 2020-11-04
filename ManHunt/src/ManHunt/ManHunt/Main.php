<?php

namespace ManHunt\ManHunt;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemFactory;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\math\Vector3;

use pocketmine\event\Listener;

use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    public function PName()
    {
        $list = array();
        foreach ($this->getServer()->getOnlinePlayers() as $players) {
            $list[] = $players->getName();
        }
        return $list;
    }

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
        $this->getResource("config.yml");
        $config->set("GS", "false");
        $config->save();
    }
    public function onDeath(PlayerDeathEvent $event){
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        if ($config->get("GS") == "true"){
            if (!$e = $config->get("SR")){
                $player = $event->getPlayer();
                if (!$config->get("SR") == $player->getName()){
                    foreach ($this->getServer()->getOnlinePlayers() as $p){
                        $p->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                        $p->sendMessage("§aCongratulations Hunters, you won the game!");
                        $config->set("GS", "false");
                        $config->save();
                    }  
                } 
            } else {
                $player = $event->getPlayer();
                if ($config->get("SR") == $player->getName()){
                    foreach ($this->getServer()->getOnlinePlayers() as $p){
                        $p->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                        $p->sendMessage("§cUnfortunately you where killed, there for you lost the game sorry!");
                        $config->set("GS", "false");
                        $config->save();
                    }  
                } 
            }
        }
    }
    
    public function onRespawn(PlayerRespawnEvent $event){
        $player = $event->getPlayer();
        if (!$config->get("SR") = $player){
            if ($config->get("GS") = "true"){
                $compass = ItemFactory::get(Item::COMPASS);
                $compass->setCustomName("§r§f§9SpeedRunner §2Tracker");
                $unbreaking = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 10);
                $compass->addEnchantment($unbreaking);
                $player->getInventory()->setItem(8, $compass);
            }
        }
    }
    public function onCompassDrop(PlayerDropItemEvent $devent, PlayerItemHeldEvent $ievent){
        $player = $devent->getPlayer();
        $item = $player->getItem();
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        if($item->getId("345")){
            if($player->getLevel() === $this->getServer()->getLevelByName($config->get("World")){
                $devent->setCancelled(true);
            }
        }
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {

		switch($cmd->getName()){
			case "manhunt":
			if($sender instanceof Player){
                if($sender->hasPermission("manhunt.use")){
					$this->openHvS($sender);
				} else {
					$sender->sendMessage("§cYou do not have permission to execute this command!");
				}
			} else {
				$sender->sendMessage("§cPlease use this command in-game!");
            } 
		}
	return true;
    }
    public function openHvS($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
            }
            $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
			switch($result){
                case 0:
                    if($config->get("GS") == "false"){
                        $this->openHunter($player);
                        $config->set("World", "");
                    } else {
                        $player->sendMessage("§cThe game has allready started!");
                    }
                break;
                case 1:
                    if($config->get("GS") == "false"){
                        $this->openWorld($player);
                    } else {
                        $player->sendMessage("§cThe game has allready started!");
                    }
                break;
                case 2:
                    if($config->get("GS") == "false"){
                        if(!$this->getConfig()->get('SR') == ""){
                            $config->set("GS", "true");
                            $config->save();
                            $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                            $speedrunner = $config->get('SR');
                            $world = $config->get("World");
                            $world = $world;
                            foreach($this->getServer()->getOnlinePlayers() as $p){
                                if ($config->get("SR") == $player->getName()){
                                    if ($config->get("World") == ""){
                                        $tp = $p->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                                    } else {
                                        $tp = $p->teleport($this->getServer()->getLevelByName($world)->getSafeSpawn());
                                    }
                                    sleep (1);
                                    $player->sendTitle("§c5");
                                    sleep (1);
                                    $player->sendTitle("§c4");
                                    sleep (1);
                                    $player->sendTitle("§c3");
                                    sleep (1);
                                    $player->sendTitle("§e2");
                                    sleep (1);
                                    $player->sendTitle("§a1");
                                    sleep (1);
                                    $player->sendTitle("§bYou are a \n§aSpeedRunner.");
                                    $player->sendTip("§3Mission: §aSurvive and beat the game!");
                                    $name = $player->getName();
                                    $player->sendMessage("§aGood luck " . $name);
                                } else {
                                    if ($config->get("World") == ""){
                                        $tp = $p->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                                    } else {
                                        $tp = $p->teleport($this->getServer()->getLevelByName($world)->getSafeSpawn());
                                    }
                                    sleep (1);
                                    $player->sendTitle("§c5");
                                    sleep (1);
                                    $player->sendTitle("§c4");
                                    sleep (1);
                                    $player->sendTitle("§c3");
                                    sleep (1);
                                    $player->sendTitle("§e2");
                                    sleep (1);
                                    $player->sendTitle("§a1");
                                    sleep (1);
                                    $player->sendTitle("§bYou are a \n§cHunter");
                                    $player->sendTip("§3Mission: §cKill §c". $config->get("SR"));
                                    $name = $player->getName();
                                    $player->sendMessage("§aGood luck " . $name);
                                    $compass = ItemFactory::get(Item::COMPASS);
                                    $compass->setCustomName("§9SpeedRunner §2Tracker");
                                    $unbreaking = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 10);
                                    $compass->addEnchantment($unbreaking);
                                    $player->getInventory()->setItem(8, $compass);
                                } 
                            }
                        }else{
                            $player->sendMessage("§cThere is no SpeedRunner set!");
                        }
                    } else {
                        $player->sendMessage("§cThe game has allready started!");
                    }
                break;
                case 3:
                    if ($config->get("GS") == "true"){
                        foreach ($this->getServer()->getOnlinePlayers() as $p){
                            $p->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                        }
                        $config->set("GS", "false");
                        $config->save();
                    } else {
                        $player->sendMessage("§cGame hasent started yet!");
                    }
                break;
                case 4:
                break;
			}
		});
		$form->setTitle("§l§bHvSUI");
		$dname = $player->getDisplayName();
		$form->setContent("§ePlease chose a functon!");
        $form->addButton("§l§9Set Hunter!\n§r§eChoose the hunter!", 0, "textures/items/diamond_sword");
        $form->addButton("§l§9Set World!\n§r§eChoose the world!", 0, "textures/ui/world_glyph_color");
        $form->addButton("§l§aStart!\n§r§eStarts the game!", 0, "textures/ui/realms_green_check");
        $form->addButton("§l§aStop!\n§r§eStop the game!", 0, "textures/ui/crossout");
		$form->addButton("§cClose", 0, "textures/ui/realms_red_x");
		$form->sendToPlayer($player);
		return $form;		
    }
    public function openHunter($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $player, array $data = null){
			$result = $data;
			if($result === null){
				return true;
            }
            $ps = $this->PName();
            $p = $ps[$data[0]];
            $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
            $config->set("SR", $p);
            $config->save();
            $player-> sendMessage("§eSet Hunter to: §b" . $p);
            $this->openHvS($player);
        });
        $list = [];
        foreach ($this->getServer()->getOnlinePlayers() as $players){
            $list[] = $players->getName();
        }
        $this->playerList[$player->getName()] = $list;
		$form->setTitle("§l§bHunterUI");
        $form->addDropdown("§ePick a hunter!", $list);
		$form->sendToPlayer($player);
		return $form;
    }
    public function openWorld($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $player, array $data = null){
			$result = $data;
			if($result === null){
				return true;
            }
            $list = array();
            foreach($this->getServer()->getLevels() as $level){
                $list[] = $level->getName();
            }
            $level = $list[$data[0]];
            $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
            $config->set("World", $level);
            $config->save();
            $player-> sendMessage("§eSet Level to: §b" . $level);
            $this->openHvS($player);
        });
        $list = [];
        foreach($this->getServer()->getLevels() as $level){
            $list[] = $level->getName();
        }
		$form->setTitle("§l§bLevelUI");
        $form->addDropdown("§ePick a Level!", $list);
		$form->sendToPlayer($player);
		return $form;
	}
}
