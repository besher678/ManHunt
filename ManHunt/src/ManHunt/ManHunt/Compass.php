<?php

namespace ManHunt\ManHunt;

use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\ItemIds;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;

class Compass implements Listener{

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onItemHeld(PlayerItemHeldEvent $e)
    {
        $player = $e->getPlayer();
      $itemcompass = ItemFactory::get(Item::COMPASS);
                if($itemcompass->getCustomName() === "§r§f§9SpeedRunner §2Tracker"){
          $nearPlayer = $this->calculateNearestPlayer($player);
				if($nearPlayer instanceof Player){
					$myVector = $player->asVector3();
                    $nearVector = $nearPlayer->asVector3();
                    $this->setSpawnPositionPacket($player, $nearVector);
				}else{
                    $player->sendMessage("No nearby player");
                }
            }
        }

        private function setSpawnPositionPacket(Player $player, Vector3 $pos) : void{
            $pk = new SetSpawnPositionPacket();
            $pk->x = $pos->getFloorX();
            $pk->y = $pos->getFloorY();
            $pk->z = $pos->getFloorZ();
            $pk->x2 = $pos->getFloorX();
            $pk->y2 = $pos->getFloorY();
            $pk->z2 = $pos->getFloorZ();
            $pk->dimension = DimensionIds::OVERWORLD;
            $pk->spawnType = SetSpawnPositionPacket::TYPE_WORLD_SPAWN;
            $player->dataPacket($pk);
        }

        private function calculateNearestPlayer(Player $player) : ?Player{
            $closest = null;
            if($player instanceof Position){
                $lastSquare = -1;
                $onLevelPlayer = $player->getLevel()->getPlayers();
                unset($onLevelPlayer[array_search($player, $onLevelPlayer)]);
                foreach($onLevelPlayer as $p){
                    $square = $player->distanceSquared($p);
                    if($lastSquare === -1 || $lastSquare > $square){
                        $closest = $p;
                        $lastSquare = $square;
                    }
                }
            }
            return $closest;
        }
    }
