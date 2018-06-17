<?php
namespace eDroid\PromptAPI;

use pocketmine\plugin\Plugin;
use pocketmine\Player;

class Prompt {
    private $plugin;
    private $player;
    private $prompt;
    private $callable;

    public function __construct(Plugin $plugin, Player $player, string $prompt, callable $callable){
        $this->plugin = $plugin;
        $this->player = $player;
        $this->prompt = $prompt;
        $this->callable = $callable;
    }

    public function getPlugin(): Plugin {
        return $this->plugin;
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getPrompt(): string {
        return $this->prompt;
    }

    public function getCallable(): callable {
        return $this->callable;
    }
}