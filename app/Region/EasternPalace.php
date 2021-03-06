<?php namespace ALttP\Region;

use ALttP\Item;
use ALttP\Location;
use ALttP\Region;
use ALttP\Support\LocationCollection;
use ALttP\World;

/**
 * Eastern Palace Region and it's Locations contained within
 */
class EasternPalace extends Region {
	protected $name = 'Eastern Palace';

	/**
	 * Create a new Eastern Palace Region and initalize it's locations
	 *
	 * @param World $world World this Region is part of
	 *
	 * @return void
	 */
	public function __construct(World $world) {
		parent::__construct($world);

		$this->locations = new LocationCollection([
			new Location\Chest("[dungeon-L1-1F] Eastern Palace - compass room", 0xE977, null, $this),
			new Location\BigChest("[dungeon-L1-1F] Eastern Palace - big chest", 0xE97D, null, $this),
			new Location\Chest("[dungeon-L1-1F] Eastern Palace - big ball room", 0xE9B3, null, $this),
			new Location\Chest("[dungeon-L1-1F] Eastern Palace - Big key", 0xE9B9, null, $this),
			new Location\Chest("[dungeon-L1-1F] Eastern Palace - map room", 0xE9F5, null, $this),
			new Location\Drop("Heart Container - Armos Knights", 0x180150, null, $this),
		]);
	}

	/**
	 * Place Keys, Map, and Compass in Region. Eastern Palace has: Big Key, Map, Compass
	 *
	 * @param ItemCollection $my_items full list of items for placement
	 *
	 * @return $this
	 */
	public function fillBaseItems($my_items) {
		$locations = $this->locations->filter(function($location) {
			return $this->boss_location_in_base || $location->getName() != "Heart Container - Armos Knights";
		});

		while(!$locations->getEmptyLocations()->random()->fill(Item::get("BigKey"), $my_items));

		if ($this->world->config('region.CompassesMaps', true)) {
			while(!$locations->getEmptyLocations()->random()->fill(Item::get("Map"), $my_items));

			while(!$locations->getEmptyLocations()->random()->fill(Item::get("Compass"), $my_items));
		}

		return $this;
	}

	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for No Major Glitches
	 *
	 * @return $this
	 */
	public function initNoMajorGlitches() {
		$this->locations["[dungeon-L1-1F] Eastern Palace - compass room"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["[dungeon-L1-1F] Eastern Palace - big chest"]->setRequirements(function($locations, $items) {
			return true;
		})->setFillRules(function($item, $locations, $items) {
			return $item != Item::get('BigKey');
		});

		$this->locations["[dungeon-L1-1F] Eastern Palace - big ball room"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["[dungeon-L1-1F] Eastern Palace - Big key"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["[dungeon-L1-1F] Eastern Palace - map room"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["Heart Container - Armos Knights"]->setRequirements(function($locations, $items) {
			return $items->canShootArrows();
		})->setFillRules(function($item, $locations, $items) {
			return $item != Item::get('BigKey');
		});

		$this->can_complete = function($locations, $items) {
			return $this->canEnter($locations, $items) && $items->canShootArrows();
		};

		return $this;
	}

	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Glitched Mode
	 *
	 * @return $this
	 */
	public function initGlitched() {
		$this->initNoMajorGlitches();

		return $this;
	}
}
