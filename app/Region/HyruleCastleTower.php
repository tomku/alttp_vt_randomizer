<?php namespace ALttP\Region;

use ALttP\Item;
use ALttP\Location;
use ALttP\Region;
use ALttP\Support\LocationCollection;
use ALttP\World;

/**
 * Hyrule Castle Tower Region and it's Locations contained within
 */
class HyruleCastleTower extends Region {
	protected $name = 'Castle Tower';

	/**
	 * Create a new Hyrule Castle Tower Region and initalize it's locations
	 *
	 * @param World $world World this Region is part of
	 *
	 * @return void
	 */
	public function __construct(World $world) {
		parent::__construct($world);

		$this->locations = new LocationCollection([
			new Location\Chest("[dungeon-A1-2F] Hyrule Castle Tower - 2 knife guys room", 0xEAB5, null, $this),
			new Location\Chest("[dungeon-A1-3F] Hyrule Castle Tower - maze room", 0xEAB2, null, $this),
		]);
	}

	/**
	 * Place Keys, Map, and Compass in Region. Hyrule Castle Tower has: 2 Keys
	 *
	 * @param ItemCollection $my_items full list of items for placement
	 *
	 * @return $this
	 */
	public function fillBaseItems($my_items) {
		$this->locations->each(function($location) {
			$location->setItem(Item::get('Key'));
		});

		return $this;
	}

	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for No Major Glitches
	 *
	 * @return $this
	 */
	public function initNoMajorGlitches() {
		$this->can_complete = function($locations, $items) {
			return $this->canEnter($locations, $items) && $items->hasSword();
		};

		$this->can_enter = function($locations, $items) {
			return $items->has('Cape')
				|| $items->hasUpgradedSword();
		};

		return $this;
	}

	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Glitched Mode.
	 *
	 * @return $this
	 */
	public function initGlitched() {
		$this->can_complete = function($locations, $items) {
			return $items->hasSword();
		};

		return $this;
	}
}
