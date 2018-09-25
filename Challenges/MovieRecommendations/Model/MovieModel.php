<?php

namespace SwitchTv\Challenges\MovieRecommendations\Model;

use stdClass, Exception;

final class MovieModel extends RecommenderModel {

	const JSON_ENDPOINT_URL = 'https://pastebin.com/raw/cVyp3McN';

	/**
	 * Builds a priority queue or Recommendation objects based on external JSON service and filters it against RecommenderFilter.
	 */
	public function fetchRecommendations() : void {
		$jsonArray = self::getJsonArray();
		$this->addRecommendationsToQueue( $jsonArray );
	}

	/**
	 * Generates recommendations, filters and adds them to priority queue.
	 *
	 * @param array $stdClasses
	 */
	private function addRecommendationsToQueue( array $stdClasses ) : void {
		foreach( $stdClasses as $stdClass ) {
			if( ! $stdClass instanceof stdClass ) {
				continue;
			}

			$movieParser = new MovieParser( $stdClass, $this->recommenderFilter );
			$recommendation = $movieParser->maybeGetRecommendation();

			if( empty( $recommendation ) ) {
				continue;
			}

			$this->splPriorityQueue->insert( $recommendation, $recommendation->getPriority() );
		}
	}

	/**
	 * Fetches and decodes JSON from JSON_ENDPOINT_URL.
	 *
	 * @return array
	 */
	private static function getJsonArray() : array {
		try {
			$json = file_get_contents( self::JSON_ENDPOINT_URL );
			$jsonArray = json_decode( $json );
		}
		catch( Exception $exception ) {
			error_log( $exception->getMessage() );
			return [];
		}

		if( empty( $jsonArray ) || ! is_array( $jsonArray ) ) {
			return [];
		}

		return $jsonArray;
	}
}
