<?php

/* 
 * Copyright (C) 2017 RTG
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace RTG\ChatterBot;

/* Essentials */
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Server;
use pocketmine\Player;

class Main extends PluginBase implements Listener {
    
    public $enable;
    public $cfg;
    
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->cfg = new Config($this->getDataFolder() . "config.yml");
        $int = count($this->cfg->get("interactions"));
        $vote = count($this->cfg->get("voting"));
        $vlink = count($this->cfg->get("votesite"));
        $this->getLogger()->warning($int . " messages has been collected!"); // DEBUG line
        $this->getLogger()->warning($vote . " voting messages has been collected!");
        $this->getLogger()->warning($vlink . " number of vote messages/links were collected!");
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        switch(strtolower($command->getName())) {
            
            case "bot":
                
                if(isset($args[0])) {
                    switch(strtolower($args[0])) {
                        
                        case "toggle":
                            
                            if(isset($this->enable[strtolower($sender->getName())])) {
                                
                                unset($this->enable[strtolower($sender->getName())]);
                                $sender->sendMessage("[BOT] I can't talk to you now :(");
                                
                            }
                            else {
                                
                                $this->enable[strtolower($sender->getName())] = strtolower($sender->getName());
                                $sender->sendMessage("[BOT] I've been enabled! We can talk now :D. Just use the word 'bot' in front of your message or questiom!");
                                
                            }
                            
                            return true;
                        break;
                         
                    } 
                }
                else {
                    $sender->sendMessage("Usage: /bot toggle");
                }
                
                return true;
            break;
 
        }
    }
    
    public function onChat(PlayerChatEvent $e) {
        
        $msg = strtolower($e->getMessage());
        $p = $e->getPlayer();
        $n = $p->getName();
        $in = strtolower($this->cfg->get("interactions"));
            
            if(in_array($msg, $in)) {
                
                switch(mt_rand(1, 3)) {
                    
                    case "1":
                        $p->sendMessage("Hey, " . $n . "!");
                    break;
                    case "2":
                        $p->sendMessage("Holla, " . $n . "!");
                    break;
                    case "3":
                        $p->sendMessage("Bonjour, " . $n . "!");
                    break;
                
                }
                   
            }
            else {
                $this->getLogger()->warning("Error, not found!"); // DEBUG line for testing
            }
            
    }
    
}