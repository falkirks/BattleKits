<?php
namespace battlekits\kit;

use battlekits\BattleKits;
use pocketmine\event\Listener;
use pocketmine\permission\Permission;

class KitStore implements Listener{
    /** @var BattleKits*/
    private $plugin;
    /** @var  Kit[] */
    private $kits;
    public function __construct(BattleKits $plugin){
        $this->plugin = $plugin;
        foreach($this->getPlugin()->getConfig()->get('kits') as $name => $kitData){
            $perm = new Permission("battlekits.use.$name", "Apply $name kit");
            $this->getPlugin()->getServer()->getPluginManager()->addPermission($perm);
            $perm->addParent("battlekits.use", true);
            $this->kits[$name] = new Kit($kitData, $this->getPlugin());
        }
        $this->getPlugin()->getLogger()->info("Loaded " . count($this->kits) . " kits.");
    }
    /**
     * @return \battlekits\kit\Kit[]
     */
    public function getKits(){
        return $this->kits;
    }
    /**
     * @return \battlekits\BattleKits
     */
    public function getPlugin(){
        return $this->plugin;
    }
    /**
     * @param $name
     * @return bool
     */
    public function kitExists($name){
        return isset($this->kits[$name]);
    }

    /**
     * @param $name
     * @return Kit|bool
     */
    public function getKit($name){
        if($this->kitExists($name)){
            return $this->kits[$name];
        }
        else{
            return false;
        }
    }
}