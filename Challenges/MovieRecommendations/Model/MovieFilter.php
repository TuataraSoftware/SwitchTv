<?php

namespace SwitchTv\Challenges\MovieRecommendations\Model;

use DateTime, DateInterval;

final class MovieFilter {

	// 30 minutes
	const DATE_INTERVAL_OFFSET = 'PT30M';

	private $dateTime;
	private $adjustedDateTime;
	private $genre;

	public function __construct( DateTime $dateTime, string $genre ) {
		$this->dateTime = $dateTime;
		$this->genre = $genre;
	}

	/**
	 * Generates DateTime object from $dateTime adjusted by DATE_INTERVAL_OFFSET.
	 *
	 * @return DateTime
	 */
	public function getAdjustedDateTime() : DateTime {
		if( ! isset( $this->adjustedDateTime ) ) {
			$adjustedDateTime = clone $this->dateTime;
			$adjustedDateTime->add( new DateInterval( self::DATE_INTERVAL_OFFSET ) );

			$this->adjustedDateTime = $adjustedDateTime;
		}

		return $this->adjustedDateTime;
	}

	public function getGenre() : string {
		return $this->genre;
	}
}
