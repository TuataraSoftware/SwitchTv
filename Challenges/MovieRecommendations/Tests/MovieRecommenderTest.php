<?php

use SwitchTv\Challenges\MovieRecommendations\MovieRecommender;
use PHPUnit\Framework\TestCase;

require_once( __DIR__ . '/../Utils/Autoloader.php' );

final class MovieRecommenderTest extends TestCase {

	public function testGetRecommendationsDefault() : void {
		$arguments = [ '', '12:00', 'animation' ];
		$output = 'Zootopia, showing at 7pm
Shaun The Sheep, showing at 7pm';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsGenreCase() : void {
		$arguments = [ '', '12:00', 'aniMation' ];
		$output = 'Zootopia, showing at 7pm
Shaun The Sheep, showing at 7pm';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsOneMinute() : void {
		$arguments = [ '', '12:31', 'animation' ];
		$output = 'Zootopia, showing at 7pm
Shaun The Sheep, showing at 7pm';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsMissingGenre() : void {
		$arguments = [ '', '12:00', 'thriller' ];
		$output = 'no movie recommendations';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsSingleMovie() : void {
		$arguments = [ '', '12:00', 'Science Fiction & Fantasy' ];
		$output = 'The Martian, showing at 5:30pm';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsTimeZone() : void {
		$arguments = [ '', '12:00+01:00', 'Science Fiction & Fantasy' ];
		$output = 'no movie recommendations';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsTimeZoneSingleMovie() : void {
		$arguments = [ '', '19:00:00+11:00', 'Science Fiction & Fantasy' ];
		$output = 'The Martian, showing at 7:30pm';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsTimeZoneSingleMovieEmptyResult() : void {
		$arguments = [ '', '19:00:01+11:00', 'Science Fiction & Fantasy' ];
		$output = 'no movie recommendations';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsTimeZoneBetweenSwoings() : void {
		$arguments = [ '', '19:00+11:00', 'Drama' ];
		$output = 'Moonlight, showing at 8:30pm';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsPmFormat() : void {
		$arguments = [ '', '5:30pm', 'cOmEdY' ];
		$output = 'Zootopia, showing at 7pm
Shaun The Sheep, showing at 7pm';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}

	public function testGetRecommendationsInvalidParameter() : void {
		$arguments = [ '', '5:30pm', new stdClass() ];
		$output = '';

		$this->assertEquals(
			$output,
			MovieRecommender::getRecommendations( $arguments )
		);
	}
}
