<?php

namespace platz1de\EasyEdit\pattern\logic\selection;

use platz1de\EasyEdit\pattern\Pattern;
use platz1de\EasyEdit\selection\Selection;
use platz1de\EasyEdit\selection\SelectionContext;
use platz1de\EasyEdit\utils\SafeSubChunkExplorer;
use platz1de\EasyEdit\utils\TaskCache;

class WallPattern extends Pattern
{
	/**
	 * @param int                  $x
	 * @param int                  $y
	 * @param int                  $z
	 * @param SafeSubChunkExplorer $iterator
	 * @param Selection            $selection
	 * @return bool
	 */
	public function isValidAt(int $x, int $y, int $z, SafeSubChunkExplorer $iterator, Selection $selection): bool
	{
		$min = TaskCache::getFullSelection()->getCubicStart();
		$max = TaskCache::getFullSelection()->getCubicEnd();
		//TODO: Non-Cubic Selections need unique checks
		return $x === $min->getX() || $x === $max->getX() || $z === $min->getZ() || $z === $max->getZ();
	}

	public function getSelectionContext(): int
	{
		return SelectionContext::WALLS;
	}
}