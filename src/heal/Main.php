<?php

namespace healpm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class main extends PluginBase
{
    public function onEnable()
    {
        $this->getServer()->getLogger()->info("For PM Shop - By Pralexkid");
        @mkdir($this->getDataFolder());
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
            "healMessageCible" => "§4[Heal] §aVous avez ete heal par _op_ !",
            "healMessageCibleConsole" => "§4[Heal] §aVous avez ete heal par la console !",
            "healMessageOp" => "§4[Heal] §aVous avez heal _cible_ !",
            "healSelfMessage" => "§4[Heal] §aVous venez de vous heal !"
        ));
        $this->saveResource("config.yml");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command == "heal") {
            if ($sender->isOp() or $sender->hasPermission("heal.op")) {
                if ($sender instanceof Player) {
                    if (isset($args[0])) {
                        if ($this->getServer()->getPlayer($args[0])) {
                            $player = $this->getServer()->getPlayer($args[0]);
                            if ($player !== $sender) {
                                str_replace("_cible_", $player, $this->getConfig()->get("healMessageOp"));
                                str_replace("_op_", $player, $this->getConfig()->get("healMessageCible"));
                                $player->setHealth(20);
                                $player->sendPopup($this->getConfig()->get("healMessageCible"));
                                $sender->sendPopup($this->getConfig()->get("healMessageOp"));
                            }
                        } else {
                            $sender->sendMessage("§4[Heal] §cVeuillez indiquer un nom de joueur valide !");
                        }
                    }  else {
                        if ($sender->hasPermission("heal.player")) {
                            $sender->setHealth(20);
                            $sender->sendPopup($this->getConfig()->get("healSelfMessage"));
                        } else {
                            $sender->sendMessage("§cVous n'avez pas la permission !");
                        }
                    }
                        } elseif ($sender instanceof ConsoleCommandSender) {
                    if (isset($args[0])) {
                        if ($this->getServer()->getPlayer($args[0])) {
                            $p = $this->getServer()->getPlayer($args[0]);
                            $pname = $p->getDisplayName();
                            $p->setHealth(20);
                            $p->sendPopup($this->getConfig()->get("healMessageCibleConsole"));
                            $this->getServer()->getLogger()->info("$pname à été heal !");
                        } else {
                            $this->getServer()->getLogger()->info("Veuillez indiquer un nom de joueur valide !");
                        }
                    } else {
                        $this->getServer()->getLogger()->info("Veuillez indiquer un nom de joueur valide !");
                    }
                }
            } else {
                $sender->sendMessage("§cVous n'avez pas la permission !");
            }
        } return true;
    }
}
