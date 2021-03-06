<?php namespace ALttP\Region;

use ALttP\Location\Prize;
use ALttP\Region;
use ALttP\Support\LocationCollection;
use ALttP\World;

/**
 * Region to hold the Pendant prizes in the world
 *
 * @TODO: consider actually putting these locations directly on the regions they belong to.
 */
class Pendants extends Region {
	protected $name = 'Prize';

	/**
	 * Create a new Pendants Region to hold pendant drop locations.
	 *
	 * @param World $world World this Region is part of
	 *
	 * @return void
	 */
	public function __construct(World $world) {
		parent::__construct($world);

		$this->locations = new LocationCollection([
			new Prize("Eastern Palace Pendant", [null, 0x1209D, 0x53EF8, 0x53EF9, 0x180052, 0x18007C, 0xC6FE], [0xC8], $this),
			new Prize("Desert Palace Pendant", [null, 0x1209E, 0x53F1C, 0x53F1D, 0x180053, 0x180078, 0xC6FF], [0x33], $this),
			new Prize("Tower of Hera Pendant", [null, 0x120A5, 0x53F0A, 0x53F0B, 0x18005A, 0x18007A, 0xC706], [0x07], $this),
		]);
	}
}
