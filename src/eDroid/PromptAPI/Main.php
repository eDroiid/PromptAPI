<?php
namespace eDroid\PromptAPI;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

use eDroid\PromptAPI\Prompt;

class Main extends PluginBase implements Listener {
    /** @var array<string => Prompt> */
    private $prompts = [];

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Enabled!");
    }

    private function isPlayerPrompted(Player $player): bool {
        return isset($this->prompts[$player->getName()]);
    }

    private function getPromptByPlayer(Player $player, bool $removePrompt = false): Prompt {
        if($this->isPlayerPrompted($player)){
            $prompt = $removePrompt ? array_shift($this->prompts[$player->getName()]) : $this->prompts[$player->getName()][0];
            
            if(count($this->prompts[$player->getName()]) === 0){
                unset($this->prompts[$player->getName()]);
            }

            return $prompt;
        }

        return null;
    }

    public function prompt(Plugin $plugin, Player $player, string $prompt, callable $callable){
        if($this->isPlayerPrompted($player)){
            $this->prompts[$player->getName()][] = new Prompt($plugin, $player, $prompt, $callable);
        }else{
            $player->sendMessage($prompt);
            $this->prompts[$player->getName()] = [new Prompt($plugin, $player, $prompt, $callable)];
        }
    }

    public function onChat(PlayerChatEvent $ev){
        if($ev->isCancelled()) return;

        if($this->isPlayerPrompted($ev->getPlayer())){
            $ev->setCancelled();

            $prompt = $this->getPromptByPlayer($ev->getPlayer(), true);
            $callable = $prompt->getCallable()->bindTo($prompt);
            $callable($ev->getMessage());

            if($this->isPlayerPrompted($ev->getPlayer())){
                $prompt = $this->getPromptByPlayer($ev->getPlayer());
                $prompt->getPlayer()->sendMessage($prompt->getPrompt());
            }
        }
    }
}