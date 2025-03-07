<?php

namespace platz1de\EasyEdit\command\defaults\generation;

use platz1de\EasyEdit\command\EasyEditCommand;
use platz1de\EasyEdit\command\KnownPermissions;
use platz1de\EasyEdit\pattern\logic\selection\SidesPattern;
use platz1de\EasyEdit\pattern\parser\ParseError;
use platz1de\EasyEdit\pattern\parser\PatternParser;
use platz1de\EasyEdit\pattern\PatternArgumentData;
use platz1de\EasyEdit\selection\Sphere;
use platz1de\EasyEdit\task\editing\selection\pattern\SetTask;
use pocketmine\player\Player;

class HollowSphereCommand extends EasyEditCommand
{
	public function __construct()
	{
		parent::__construct("/hsphere", "Set a hollow sphere", [KnownPermissions::PERMISSION_GENERATE, KnownPermissions::PERMISSION_EDIT], "//hsphere <radius> <pattern> [thickness]", ["/hsph", "/hollowsphere"]);
	}

	/**
	 * @param Player   $player
	 * @param string[] $args
	 */
	public function process(Player $player, array $args): void
	{
		if (($args[1] ?? "") === "") {
			$player->sendMessage($this->getUsage());
			return;
		}

		try {
			$pattern = SidesPattern::from([PatternParser::parseInput($args[1], $player)], PatternArgumentData::create()->setFloat("thickness", (float) ($args[2] ?? 1.0)));
		} catch (ParseError $exception) {
			$player->sendMessage($exception->getMessage());
			return;
		}

		SetTask::queue(Sphere::aroundPoint($player->getName(), $player->getWorld()->getFolderName(), $player->getPosition(), (float) $args[0]), $pattern, $player->getPosition());
	}
}