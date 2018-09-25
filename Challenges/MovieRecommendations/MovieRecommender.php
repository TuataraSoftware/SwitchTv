<?php

namespace SwitchTv\Challenges\MovieRecommendations;

use SwitchTv\Challenges\MovieRecommendations\Controller\MovieController;
use SwitchTv\Challenges\MovieRecommendations\Model\MovieFilter;
use DateTime, Exception;

final class MovieRecommender {

	const SUPPORTED_ARGUMENTS_COUNT = 3;
	const DATETIME_FORMAT = 'G:i:s';

	const ERROR_MESSAGE_ARGUMENTS_COUNT = '[Error] Invalid arguments count: %d. Please provide strictly 3 arguments.';
	const ERROR_MESSAGE_DATETIME_FORMAT = '[Error] Invalid first argument: %s. Please provide time in 12:34:56 format.';
	const ERROR_MESSAGE_STRING_FORMAT = '[Error] Invalid second argument. Please provide valid string.';

	/**
	 * Provides filtered movie recommendations based on command line arguments.
	 *
	 * @param array $arguments in format:
	 * [
	 *    0 => script name (ignored)
	 *    1 => valid time format (i.e. 12:00, 12:00:00+10:00 etc.)
	 *    2 => genre (i.e. animation)
	 * ]
	 *
	 * @return string
	 */
	public static function getRecommendations( array $arguments ) : string {
		// making sure all potential exceptions are handled
		try {
			if( ! self::validate( $arguments ) ) {
				return '';
			}

			$dateTime = self::getDateTime( $arguments );
			$genre = self::getGenre( $arguments );

			$movieFilter = new MovieFilter( $dateTime, $genre );

			return MovieController::getRecommendations( $movieFilter );
		}
		catch( Exception $exception ) {
			self::processException( $exception );
		}

		return '';
	}

	private static function getDateTime( array $arguments ) : DateTime {
		return new DateTime( $arguments[ 1 ] );
	}

	private static function getGenre( array $arguments ) : string {
		return $arguments[ 2 ];
	}

	private static function validate( array $arguments ) : bool {
		$argumentsCount = count( $arguments );

		if( $argumentsCount !== self::SUPPORTED_ARGUMENTS_COUNT ) {
			self::processError( self::ERROR_MESSAGE_ARGUMENTS_COUNT, $argumentsCount );
			return false;
		}

		if( new DateTime( $arguments[ 1 ] ) === false ) {
			self::processError( self::ERROR_MESSAGE_DATETIME_FORMAT, $arguments[ 1 ] );
			return false;
		}

		if( ! is_string( $arguments[ 2 ] ) ) {
			self::processError( self::ERROR_MESSAGE_STRING_FORMAT, $arguments[ 2 ] );
			return false;
		}

		return true;
	}

	private static function processError( string $format, $argument ) {
		$errorMessage = sprintf( $format, $argument );
		error_log( $errorMessage );
	}

	private static function processException( Exception $exception ) {
		$errorMessage = $exception->getMessage();
		error_log( $errorMessage );
	}
}
