<?php

namespace platz1de\EasyEdit\utils;

use platz1de\EasyEdit\pattern\parser\ParseError;
use pocketmine\block\Block;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;

class BlockParser
{
	/**
	 * @param string $string
	 * @return bool
	 */
	public static function isStatic(string $string): bool
	{
		if (isset(explode(":", str_replace([" ", "minecraft:"], ["_", ""], trim($string)))[1])) {
			return true; //given with meta value
		}
		try {
			LegacyStringToItemParser::getInstance()->parse($string);
			return false;
		} catch (LegacyStringToItemParserException) {
			return true; //given with prefix (or unknown)
		}
	}

	/**
	 * @param string $string
	 * @return Block
	 * @throws ParseError
	 */
	public static function getBlock(string $string): Block
	{
		try {
			$item = LegacyStringToItemParser::getInstance()->parse($string);
		} catch (LegacyStringToItemParserException) {
			//Also accept prefixed blocks
			if (($item = StringToItemParser::getInstance()->parse(explode(":", str_replace([" ", "minecraft:"], ["_", ""], trim($string)))[0])) === null) {
				throw new ParseError("Unknown Block " . $string);
			}
		}

		return $item->getBlock();
	}

	/**
	 * @param string $stringId Id in format id:meta
	 * @return array{int, int} id, meta
	 */
	public static function fromStringId(string $stringId): array
	{
		$data = explode(":", $stringId);
		if (!isset($data[1])) {
			throw new ParseError("Expected string block id, got " . $stringId);
		}
		return [(int) $data[0], (int) $data[1]];
	}
}