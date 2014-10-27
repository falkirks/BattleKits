<?php
namespace battlekits;

use battlekits\command\BattleKitCommand;
use battlekits\economy\EconomyLoader;
use battlekits\economy\BaseEconomy;
use battlekits\kit\KitHistoryStore;
use battlekits\kit\KitPaymentController;
use battlekits\kit\KitStore;
use battlekits\sign\SignListener;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;

class BattleKits extends PluginBase{
    /** @var  EconomyLoader */
    private $economyLoader;
    /** @var  BaseEconomy */
    private $economy = false;
    /** @var  KitStore */
    private $kitStore;
    /** @var  KitPaymentController */
    private $kitPaymentController;
    /** @var  BattleKitCommand */
    private $mainCommand;
    /** @var  SignListener */
    private $signListener;
    /** @var  KitHistoryStore */
    private $kitHistoryStore;
    public function onEnable(){
        $this->saveDefaultConfig();

        $this->economyLoader = new EconomyLoader($this);
        $this->economyLoader->load();

        $this->kitStore = new KitStore($this);

        $this->kitHistoryStore = new KitHistoryStore($this);

        $this->kitPaymentController = new KitPaymentController($this);
        
        $this->mainCommand = new BattleKitCommand($this);
        $this->getServer()->getCommandMap()->register("battlekits", $this->mainCommand);
        
        $this->signListener = new SignListener($this);
    }

    /**
     * @param \battlekits\economy\BaseEconomy $economy
     */
    public function setEconomy(BaseEconomy $economy){
        $this->economy = $economy;
    }

    /**
     * @return \battlekits\economy\BaseEconomy
     */
    public function getEconomy(){
        return $this->economy;
    }
    /**
     * @return bool
     */
    public function isLinkedToEconomy(){
        return $this->economy instanceof BaseEconomy;
    }
    /**
     * @return \battlekits\kit\KitStore
     */
    public function getKitStore(){
        return $this->kitStore;
    }
    /**
     * @return \battlekits\command\BattleKitCommand
     */
    public function getMainCommand(){
        return $this->mainCommand;
    }
    /**
     * @return \battlekits\economy\EconomyLoader
     */
    public function getEconomyLoader(){
        return $this->economyLoader;
    }
    /**
     * @return \battlekits\sign\SignListener
     */
    public function getSignListener(){
        return $this->signListener;
    }

    /**
     * @return \battlekits\kit\KitPaymentController
     */
    public function getKitPaymentController(){
        return $this->kitPaymentController;
    }

    /**
     * @return \battlekits\kit\KitHistoryStore
     */
    public function getKitHistoryStore(){
        return $this->kitHistoryStore;
    }
    public function reportEconomyLinkError(){
        $this->getLogger()->critical("The link to " . $this->economy->getName() . " has been lost. Paid kits are no longer available.");
        $this->economy = false;
    }

}
