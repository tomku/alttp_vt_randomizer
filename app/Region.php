<?php namespace ALttP;

/**
 * A logical collection of Locations. Can have special can_enter function that will apply to all locaitons contained,
 * and can_complete function set to validate that the region prize (if set) can be obtained.
 */
class Region {
	protected $locations;
	protected $can_enter;
	protected $can_complete;
	protected $name = 'Unknown';
	protected $boss_location_in_base = true;
	protected $prize_location;
	protected $world;

	/**
	 * Create a new Region.
	 *
	 * @param World $world World this Region is part of
	 *
	 * @return void
	 */
	public function __construct(World $world) {
		$this->world = $world;

		$this->boss_location_in_base = $this->world->config('region.bossNormalLocation', true);
	}

	/**
	 * Get the World associated with this Region.
	 *
	 * @return World
	 */
	public function getWorld() {
		return $this->world;
	}

	/**
	 * Get the name of this Region.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the Prize Location for completeing this Region and set it's rules for access to completing the region.
	 *
	 * @param Location\Prize $location location to put item that will be the prize
	 *
	 * @return $this
	 */
	public function setPrizeLocation(Location\Prize $location) {
		$this->prize_location = $location;

		$this->prize_location->setRegion($this);
		if ($this->can_complete) {
			$this->prize_location->setRequirements($this->can_complete);
		}

		return $this;
	}

	/**
	 * Get the Prize Location for completeing this Region.
	 *
	 * @return Location\Prize|null
	 */
	public function getPrizeLocation() {
		return $this->prize_location;
	}

	/**
	 * Get the Prize for completeing this Region.
	 *
	 * @return Item|null
	 */
	public function getPrize() {
		if (!isset($this->prize_location) || !$this->prize_location->hasItem()) {
			return null;
		}

		return $this->prize_location->getItem();
	}

	/**
	 * Determine if a (particular) Prize Item is set for the Region
	 *
	 * @param Item|null $item Item to check against
	 *
	 * @return bool
	 */
	public function hasPrize(Item $item = null) {
		if (!isset($this->prize_location) || !$this->prize_location->hasItem()) {
			return false;
		}

		return $this->prize_location->hasItem($item);
	}

	/**
	 * Fill the normal required Items for a Region, this usually includes Keys/Maps/Compasses
	 *
	 * @param Support\ItemColletion $my_items set of Items available when filling base Items in the Region
	 *
	 * @return $this
	 */
	public function fillBaseItems($my_items) {
		return $this;
	}

	/**
	 * Initalize the logic for the Region
	 *
	 * @param string $type the ruleset to apply to Locations
	 *
	 * @return $this
	 */
	public function init(string $type = 'NoMajorGlitches') {
		return call_user_func([$this, 'init' . $type]);
	}

	/**
	 * Initalize the No Major Glitches logic for the Region
	 *
	 * @return $this
	 */
	public function initNoMajorGlitches() {
		return $this;
	}

	/**
	 * Initalize the Glitched logic for the Region
	 *
	 * @return $this
	 */
	public function initGlitched() {
		return $this;
	}

	/**
	 * Initalize the SpeedRunner logic for the Region
	 *
	 * @return $this
	 */
	public function initSpeedRunner() {
		return $this->initNoMajorGlitches();
	}


	/**
	 * Determine if the Region is completable given the locations and items available
	 *
	 * @param Support\LocationCollection $locations current list of Locations
	 * @param ItemsCollection $items current list of Items collected
	 *
	 * @return bool
	 */
	public function canComplete($locations, $items) {
		if ($this->can_complete) {
			return call_user_func($this->can_complete, $locations, $items);
		}
		return true;
	}

	/**
	 * Determine if the Region can be entered given the locations and items available
	 *
	 * @param Support\LocationCollection $locations current list of Locations
	 * @param ItemsCollection $items current list of Items collected
	 *
	 * @return bool
	 */
	public function canEnter($locations, $items) {
		if ($this->can_enter) {
			return call_user_func($this->can_enter, $locations, $items);
		}
		return true;
	}

	/**
	 * Get all the Locations in this Region
	 *
	 * @return Support\LocationCollection
	 */
	public function getLocations() {
		return $this->locations;
	}

	/**
	 * Get Location in this Region by name
	 *
	 * @param string $name name of the Location
	 *
	 * @return Location
	 */
	public function getLocation(string $name) {
		return $this->locations[$name];
	}

	/**
	 * Get all the Locations in this Region that do not have an Item assigned
	 *
	 * @return Support\LocationCollection
	 */
	public function getEmptyLocations() {
		return $this->locations->filter(function($location) {
			return !$location->hasItem();
		});
	}

	/**
	 * Get all the Locations in this Region that have (a particular) Item assigned to them
	 *
	 * @param Item $item item to search locations for
	 *
	 * @return Support\LocationCollection
	 */
	public function locationsWithItem(Item $item = null) {
		return $this->locations->locationsWithItem($item);
	}
}
