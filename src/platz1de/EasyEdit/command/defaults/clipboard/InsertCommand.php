<?php

namespace platz1de\EasyEdit\command\defaults\clipboard;

use platz1de\EasyEdit\command\EasyEditCommand;
use platz1de\EasyEdit\command\KnownPermissions;
use platz1de\EasyEdit\Messages;
use platz1de\EasyEdit\selection\ClipBoardManager;
use platz1de\EasyEdit\task\DynamicStoredPasteTask;
use pocketmine\player\Player;
use Throwable;

class InsertCommand extends EasyEditCommand
{
	public function __construct()
	{
		parent::__construct("/insert", "Insert the Clipboard", [KnownPermissions::PERMISSION_CLIPBOARD, KnownPermissions::PERMISSION_EDIT]);
	}

	/**
	 * @param Player   $player
	 * @param string[] $args
	 */
	public function process(Player $player, array $args): void
	{
		try {
			$selection = ClipBoardManager::getFromPlayer($player->getName());
		} catch (Throwable) {
			Messages::send($player, "no-clipboard");
			return;
		}

		DynamicStoredPasteTask::queue($player->getName(), $selection, $player->getPosition(), true, true);
	}
}