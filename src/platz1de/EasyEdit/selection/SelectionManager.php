<?php

namespace platz1de\EasyEdit\selection;

use Throwable;

class SelectionManager
{
	/**
	 * @var Selection[]
	 */
	private static array $selections = [];

	/**
	 * @param string $player
	 * @return Selection
	 * @throws Throwable
	 */
	public static function getFromPlayer(string $player): Selection
	{
		return self::$selections[$player];
	}

	/**
	 * @param string    $player
	 * @param Selection $selection
	 */
	public static function setForPlayer(string $player, Selection $selection): void
	{
		self::$selections[$player] = $selection;
	}

	/**
	 * @param string $player
	 */
	public static function clearForPlayer(string $player): void
	{
		if (isset(self::$selections[$player])) {
			self::$selections[$player]->close();
		}
		unset(self::$selections[$player]);
	}
}