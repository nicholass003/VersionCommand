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

namespace nicholass003\versioncommand\permission;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

class Permissions{

	private function __construct(){}

	public static function registerPermissions() : void{
		$operatorRoot = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
		DefaultPermissions::registerPermission(new Permission(PermissionNames::COMMAND_VERSION_DETAIL, PermissionDescriptions::POCKETMINE_PERMISSION_COMMAND_VERSION_DETAIL), [$operatorRoot]);
	}
}
