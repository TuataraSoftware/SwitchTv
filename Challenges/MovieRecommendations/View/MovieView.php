<?php

namespace SwitchTv\Challenges\MovieRecommendations\View;

use SwitchTv\Challenges\MovieRecommendations\Model\Recommendation;

use DateTime;

final class MovieView extends RecommenderView {

	const RECOMMENDATION_FORMAT = '%s, showing at %s';
	const EMPTY_QUEUE_MESSAGE = 'no movie recommendations';
	const GLUE = "\n";

	const DATETIME_FORMAT_LONG = 'g:ia';
	const DATETIME_FORMAT_SHORT = 'ga';
	const DATETIME_FORMAT_MINUTES = 'i';

	/**
	 * Formats $recommendation to 'Zootopia, showing at 7pm' or 'Zootopia, showing at 7:30pm' string.
	 *
	 * @param Recommendation $recommendation
	 *
	 * @return string
	 */
	protected static function formatRecommendation( Recommendation $recommendation ) : string {
		$name = $recommendation->getName();
		$dateTime = $recommendation->getValue();

		// by design Recommendation class allows values of any type
		// while this view only supports DateTime
		if( ! $dateTime instanceof DateTime ) {
			return '';
		}

		$formattedDateTime = self::formatDateTime( $dateTime );

		return sprintf( self::RECOMMENDATION_FORMAT, $name, $formattedDateTime );
	}

	protected static function getEmptyQueueMessage() : string {
		return self::EMPTY_QUEUE_MESSAGE;
	}

	protected static function getGlue() : string {
		return self::GLUE;
	}

	/**
	 * Formats date to '7pm' or '7:30pm' format depending on minutes in the date.
	 * It ignores seconds under assumption that real world showings are rounded to minutes.
	 *
	 * @param DateTime $dateTime
	 *
	 * @return string
	 */
	private static function formatDateTime( DateTime $dateTime ) : string {
		$format = self::DATETIME_FORMAT_SHORT;

		if( self::hasMinutes( $dateTime ) ) {
			$format = self::DATETIME_FORMAT_LONG;
		}

		return $dateTime->format( $format );
	}

	/**
	 * Checks if the date has minutes in it.
	 *
	 * @param DateTime $dateTime
	 *
	 * @return bool
	 */
	private static function hasMinutes( DateTime $dateTime ) : bool {
		$minutes = $dateTime->format( self::DATETIME_FORMAT_MINUTES );

		if( $minutes == 0 ) {
			return false;
		}

		return true;
	}
}
