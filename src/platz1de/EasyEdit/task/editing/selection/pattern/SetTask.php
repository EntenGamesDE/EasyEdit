<?php

namespace platz1de\EasyEdit\task\editing\selection\pattern;

use platz1de\EasyEdit\pattern\Pattern;
use platz1de\EasyEdit\selection\Selection;
use platz1de\EasyEdit\task\editing\EditTaskHandler;
use platz1de\EasyEdit\task\editing\selection\cubic\CubicStaticUndo;
use platz1de\EasyEdit\task\editing\type\SettingNotifier;
use platz1de\EasyEdit\thread\input\TaskInputData;
use platz1de\EasyEdit\utils\AdditionalDataManager;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class SetTask extends PatternedEditTask
{
	use CubicStaticUndo;
	use SettingNotifier;

	/**
	 * @param string                $owner
	 * @param string                $world
	 * @param AdditionalDataManager $data
	 * @param Selection             $selection
	 * @param Vector3               $position
	 * @param Vector3               $splitOffset
	 * @param Pattern               $pattern
	 * @return SetTask
	 */
	public static function from(string $owner, string $world, AdditionalDataManager $data, Selection $selection, Vector3 $position, Vector3 $splitOffset, Pattern $pattern): SetTask
	{
		$instance = new self($owner);
		PatternedEditTask::initPattern($instance, $world, $data, $selection, $position, $splitOffset, $pattern);
		return $instance;
	}

	/**
	 * @param Selection $selection
	 * @param Pattern   $pattern
	 * @param Position  $place
	 */
	public static function queue(Selection $selection, Pattern $pattern, Position $place): void
	{
		TaskInputData::fromTask(self::from($selection->getPlayer(), $selection->getWorldName(), new AdditionalDataManager(true, true), $selection, $place->asVector3(), new Vector3(0, 0, 0), $pattern));
	}

	/**
	 * @return string
	 */
	public function getTaskName(): string
	{
		return "set";
	}

	/**
	 * @param EditTaskHandler $handler
	 */
	public function executeEdit(EditTaskHandler $handler): void
	{
		$selection = $this->getCurrentSelection();
		$pattern = $this->getPattern();
		$selection->useOnBlocks($this->getPosition(), function (int $x, int $y, int $z) use ($handler, $pattern, $selection): void {
			if ($pattern->isValidAt($x, $y, $z, $handler->getOrigin(), $selection, $this->getTotalSelection())) {
				$block = $pattern->getFor($x, $y, $z, $handler->getOrigin(), $selection, $this->getTotalSelection());
				if ($block !== -1) {
					$handler->changeBlock($x, $y, $z, $block);
				}
			}
		}, $pattern->getSelectionContext(), $this->getTotalSelection());
	}
}