<?php
namespace eDroid\PromptAPI;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class Main extends PluginBase implements Listener {
    /** @var array<string => callable> */
    private $prompts = [];

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Enabled!");
    }

    private function isPlayerPrompted(Player $player): bool {
        return isset($this->prompts[$player->getName()]);
    }

    private function getCallableByPlayer(Player $player): callable {
        if($this->isPlayerPrompted($player)){
            return $this->prompts[$player->getName()];
        }

        return null;
    }

    private function removeCallableByPlayer(Player $player){
        unset($this->prompts[$player->getName()]);
    }

    public function prompt(Player $player, string $prompt, callable $callable){
        $player->sendMessage($prompt);
        $this->prompts[$player->getName()] = $callable;
    }

    public function onChat(PlayerChatEvent $ev){
        if($ev->isCancelled()) return;

        if($this->isPlayerPrompted($ev->getPlayer())){
            $ev->setCancelled();

            $callable = $this->getCallableByPlayer($ev->getPlayer());
            $callable($ev->getMessage());

            $this->removeCallableByPlayer($ev->getPlayer());
        }
    }
}