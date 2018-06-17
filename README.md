# PromptAPI
---

PromptAPI that let's you get input from a player.

## How Does It Work?
PromptAPI waits for a player to send a message and then cancels the event and calls the callable provided.
The callable is called in a Prompt class that has the methods, `getPlugin()`, `getPlayer()`, `getPrompt()`, and `getCallable()`.

## Example
```php
$api = $this->getServer()->getPluginManager()->getPlugin("PromptAPI");
if($api !== null){
    $api->prompt(Plugin $this, Player $player, "What's your name?", function(string $input){
        $this->getPlayer()->sendMessage("Hey, $input!");
        $this->getPlugin()->getLogger()->info("Said hello to, $input.");
    });
}else{
    $this->getLogger()->warning("This plugin needs the plugin \"PromptAPI\".");
}
```