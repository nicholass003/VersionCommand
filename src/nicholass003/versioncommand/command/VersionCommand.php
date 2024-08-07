<?php

/*
 * Copyright (c) 2024 - present nicholass003
 *        _      _           _                ___   ___ ____
 *       (_)    | |         | |              / _ \ / _ \___ \
 *  _ __  _  ___| |__   ___ | | __ _ ___ ___| | | | | | |__) |
 * | '_ \| |/ __| '_ \ / _ \| |/ _` / __/ __| | | | | | |__ <
 * | | | | | (__| | | | (_) | | (_| \__ \__ \ |_| | |_| |__) |
 * |_| |_|_|\___|_| |_|\___/|_|\__,_|___/___/\___/ \___/____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  nicholass003
 * @link    https://github.com/nicholass003/
 *
 *
 */

declare(strict_types=1);

namespace nicholass003\versioncommand\command;

use nicholass003\versioncommand\Main;
use nicholass003\versioncommand\permission\PermissionNames;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;
use pocketmine\VersionInfo;
use function count;
use function implode;
use function sprintf;
use function stripos;
use function strtolower;
use const PHP_VERSION;

class VersionCommand extends Command implements PluginOwned{

	public function __construct(
		private Main $plugin
	){
		parent::__construct(
			"version",
			KnownTranslationFactory::pocketmine_command_version_description(),
			KnownTranslationFactory::pocketmine_command_version_usage(),
			["ver", "about"]
		);
		$this->setPermission(DefaultPermissionNames::COMMAND_VERSION);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(count($args) === 0){
			$sender->sendMessage(KnownTranslationFactory::pocketmine_command_version_serverSoftwareName(
				TextFormat::GREEN . VersionInfo::NAME . TextFormat::RESET
			));
			$versionColor = VersionInfo::IS_DEVELOPMENT_BUILD ? TextFormat::YELLOW : TextFormat::GREEN;
			$sender->sendMessage(KnownTranslationFactory::pocketmine_command_version_serverSoftwareVersion(
				$versionColor . VersionInfo::VERSION()->getFullVersion() . TextFormat::RESET,
				TextFormat::GREEN . VersionInfo::GIT_HASH() . TextFormat::RESET
			));
			$sender->sendMessage(KnownTranslationFactory::pocketmine_command_version_minecraftVersion(
				TextFormat::GREEN . ProtocolInfo::MINECRAFT_VERSION_NETWORK . TextFormat::RESET,
				TextFormat::GREEN . ProtocolInfo::CURRENT_PROTOCOL . TextFormat::RESET
			));
			$sender->sendMessage(KnownTranslationFactory::pocketmine_command_version_phpVersion(TextFormat::GREEN . PHP_VERSION . TextFormat::RESET));

			$jitMode = Utils::getOpcacheJitMode();
			if($jitMode !== null){
				if($jitMode !== 0){
					$jitStatus = KnownTranslationFactory::pocketmine_command_version_phpJitEnabled(sprintf("CRTO: %d", $jitMode));
				}else{
					$jitStatus = KnownTranslationFactory::pocketmine_command_version_phpJitDisabled();
				}
			}else{
				$jitStatus = KnownTranslationFactory::pocketmine_command_version_phpJitNotSupported();
			}
			$sender->sendMessage(KnownTranslationFactory::pocketmine_command_version_phpJitStatus($jitStatus->format(TextFormat::GREEN, TextFormat::RESET)));
			$sender->sendMessage(KnownTranslationFactory::pocketmine_command_version_operatingSystem(TextFormat::GREEN . Utils::getOS() . TextFormat::RESET));
		}else{
			if(!$this->testPermission($sender, PermissionNames::COMMAND_VERSION_DETAIL)){
				return true;
			}

			$pluginName = implode(" ", $args);
			$exactPlugin = $sender->getServer()->getPluginManager()->getPlugin($pluginName);

			if($exactPlugin instanceof Plugin){
				$this->describeToSender($exactPlugin, $sender);
				return true;
			}

			$found = false;
			$pluginName = strtolower($pluginName);
			foreach($sender->getServer()->getPluginManager()->getPlugins() as $plugin){
				if(stripos($plugin->getName(), $pluginName) !== false){
					$this->describeToSender($plugin, $sender);
					$found = true;
				}
			}

			if(!$found){
				$sender->sendMessage(KnownTranslationFactory::pocketmine_command_version_noSuchPlugin());
			}
		}

		return true;
	}

	private function describeToSender(Plugin $plugin, CommandSender $sender) : void{
		$desc = $plugin->getDescription();
		$sender->sendMessage(TextFormat::DARK_GREEN . $desc->getName() . TextFormat::RESET . " version " . TextFormat::DARK_GREEN . $desc->getVersion());

		if($desc->getDescription() !== ""){
			$sender->sendMessage($desc->getDescription());
		}

		if($desc->getWebsite() !== ""){
			$sender->sendMessage("Website: " . $desc->getWebsite());
		}

		if(count($authors = $desc->getAuthors()) > 0){
			if(count($authors) === 1){
				$sender->sendMessage("Author: " . implode(", ", $authors));
			}else{
				$sender->sendMessage("Authors: " . implode(", ", $authors));
			}
		}
	}

	public function getOwningPlugin() : Plugin{
		return $this->plugin;
	}
}
