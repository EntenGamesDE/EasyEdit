<?php

namespace platz1de\EasyEdit\command\defaults\selection;

use platz1de\EasyEdit\command\EasyEditCommand;
use platz1de\EasyEdit\command\KnownPermissions;
use platz1de\EasyEdit\selection\Cube;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class FirstPositionCommand extends EasyEditCommand
{
	public function __construct()
	{
		parent::__construct("/pos1", "Set the first Position", [KnownPermissions::PERMISSION_SELECT], "//pos1 [x] [y] [z]", ["/1"]);
	}

	/**
	 * @param Player   $player
	 * @param string[] $args
	 */
	public function process(Player $player, array $args): void
	{
		if (count($args) > 2) {
			Cube::selectPos1($player, new Vector3((int) $args[0], (int) $args[1], (int) $args[2]));
		} else {
			Cube::selectPos1($player, $player->getPosition()->floor());
		}
	}
}