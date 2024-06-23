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

namespace nicholass003\versioncommand;

use nicholass003\versioncommand\command\VersionCommand;
use nicholass003\versioncommand\permission\Permissions;
use pocketmine\command\SimpleCommandMap;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	public function onEnable() : void{
		Permissions::registerPermissions();

		$this->unregisterCommands($this->getServer()->getCommandMap());
		$this->registerCommands($this->getServer()->getCommandMap());
	}

	private function registerCommands(SimpleCommandMap $commandMap) : void{
		$commandMap->register("pocketmine", new VersionCommand($this));
	}

	private function unregisterCommands(SimpleCommandMap $commandMap) : void{
		$commandMap->unregister($commandMap->getCommand("version"));
	}
}
