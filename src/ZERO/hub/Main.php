<?php

namespace ZERO\hub;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class Main extends PluginBase implements Listener {
    /** @var string[] */
    private $config;
    
    public function onEnable() : void {
  	    $this->config = $this->getConfig()->getAll();
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
	    
    public function onCommand(CommandSender $sender, Command $cmd,$label, array $args) : bool {
        $spawn = $this->getServer()->getDefaultLevel()->getSpawnLocation();
        switch($cmd->getName()) {
            case "hub":
                if($sender instanceof Player){
                    $sender->teleport($spawn);
			 if($this->config["title"] === "on"){
			        $sender->addTitle($this->config["titlemessage"]);
		         }
			 if($this->config["message"] === "on"){
                         	$sender->sendMessage($this->config["sendmessage"]);
			 }
			 if($this->config["actionbar"] === "on"){
                        	$sender->sendTip($this->config["actionbarmessage"]);
			 }
			 if($this->config["clearinventory"] === "on"){
				$removed = 0;
        		        foreach ($sender->getInventory()->getContents() as $index => $item) {
            	            		$sender->getInventory()->setItem($index, Item::get(Item::AIR));
            		       		$removed++;
        		        }
			 }
			 if($this->config["cleararmor"] === "on"){
			        $sender->getArmorInventory()->clearAll();
			 }
			 if($this->config["cleareffect"] === "on"){
                         	foreach($sender->getEffects() as $effect) {
			               $sender->removeEffect($effect->getId());
                         	}
			 }
			 if($this->config["clearbadeffect"] === "on"){
			        foreach($sender->getEffects() as $effect) {
                                	if($effect->getType()->isBad()) {
                                	$sender->removeEffect($effect->getId());
                            	}
			 }
		  }
		if($this->config["gamemode"] === "on"){
			$sender->setGamemode(0);
			}
  	      } else {
                  $sender->sendMessage($this->config["useingame"]);
  	     }
        }
        return true;
    }
    public function forceSpawn(PlayerLoginEvent $event){
        if ($this->config["forcespawn"] === "true")
        	$event->getPlayer()->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
   	 }
}
