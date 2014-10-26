<?php
namespace battlekits\kit;

use battlekits\BattleKits;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;

class KitHistoryStore implements Listener{
    /** @var  BattleKits */
    private $plugin;
    private $players;
    public function __construct(BattleKits $plugin){
        $this->plugin = $plugin;
        $this->players = [];
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this, $this->getPlugin());
    }
    public function kitUsed(Player $p){
        $this->players[$p->getName()] = true;
    }
    public function canUse(Player $p){
        return (!isset($this->players[$p->getName()]) || $this->getPlugin()->getConfig()->get('once-per-life') === false);
    }
    public function onPlayerRespawn(PlayerRespawnEvent $event){
        //TODO send kit info
        if(isset($this->players[$event->getPlayer()->getName()])){
            unset($this->players[$event->getPlayer()->getName()]);
        }
    }
    /**
     * @return \battlekits\BattleKits
     */
    public function getPlugin(){
        return $this->plugin;
    }
}