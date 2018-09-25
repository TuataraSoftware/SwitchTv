<?php

namespace SwitchTv\Challenges\MovieRecommendations\Model;

use DateTime, stdClass;

final class MovieParser {

	private $stdClass;
	private $movieFilter;

	public function __construct( stdClass $stdClass, MovieFilter $movieFilter ) {
		$this->stdClass = $stdClass;
		$this->movieFilter = $movieFilter;
	}

	/**
	 * Generates movie Recommendation whenever stdClass satisfies RecommenderFilter's genre and time conditions.
	 *
	 * @return Recommendation|null
	 */
	public function maybeGetRecommendation() : ?Recommendation {
		// validating stdClass
		$isValid = $this->isValid();

		if( ! $isValid ) {
			return null;
		}

		// filtering by genre
		$isFilteredGenre = $this->hasGenre();

		if( ! $isFilteredGenre ) {
			return null;
		}

		// filtering by showing time
		$minShowingTime = $this->maybeGetMinShowingTime();

		if( empty( $minShowingTime ) ) {
			return null;
		}

		// building recommendation
		$recommendation = new Recommendation( $this->stdClass->name, $minShowingTime, $this->stdClass->rating );
		return $recommendation;
	}

	/**
	 * Finds genre name satisfying $recommenderFilter conditions in case insensitive fashion.
	 *
	 * @return bool
	 */
	private function hasGenre() : bool {
		$filterGenreName = $this->movieFilter->getGenre();

		foreach( $this->stdClass->genres as $genre ) {
			// some genres might have invalid format but we still can provide a recommendation
			if( ! is_string( $genre ) ) {
				continue;
			}

			// case insensitive comparison
			if( strcasecmp( $genre, $filterGenreName ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Finds minimum showing time satisfying $recommenderFilter conditions.
	 *
	 * @return DateTime|null
	 */
	private function maybeGetMinShowingTime() : ?DateTime {
		$filterTime = $this->movieFilter->getAdjustedDateTime();

		$minShowingTime = null;

		foreach( $this->stdClass->showings as $showing ) {
			$showingTime = new DateTime( $showing );

			// some dates might have invalid format but we still can provide a recommendation
			if( $showingTime === false ) {
				continue;
			}

			$isFilteredShowing = ( $showingTime >= $filterTime );

			if( ! $isFilteredShowing ) {
				continue;
			}

			$minShowingTime = $minShowingTime
				? min( $minShowingTime, $showingTime )
				: $showingTime;
		}

		return $minShowingTime;
	}

	/**
	 * Validates $stdClass fields against the following format:
	 *
	 * name: string
	 * rating: integer
	 * genres: array
	 * showings: array
	 *
	 * NOTE:
	 * - Values of genres & showings arrays are not validated. Some values might have invalid format
	 * but we still can provide a recommendation. Another reason is performance as multiple array loops degrade it.
	 * - Empty fields are considered invalid as such cases cannot result in a valid recommendation anyway
	 *
	 * @return bool
	 */
	private function isValid() : bool {
		$stdClass = $this->stdClass;

		if( empty( $stdClass->name ) || ! is_string( $stdClass->name ) ) {
			return false;
		}

		if( empty( $stdClass->rating ) || ! is_int( $stdClass->rating ) ) {
			return false;
		}

		if( empty( $stdClass->genres ) || ! is_array( $stdClass->genres ) ) {
			return false;
		}

		if( empty( $stdClass->showings ) || ! is_array( $stdClass->showings ) ) {
			return false;
		}

		return true;
	}
}
