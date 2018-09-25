<?php

namespace SwitchTv\Challenges\MovieRecommendations\View;

use SwitchTv\Challenges\MovieRecommendations\Model\Recommendation;
use SplPriorityQueue;

abstract class RecommenderView {

	protected $recommenderFilter;

	public function __construct( $recommenderFilter ) {
		$this->recommenderFilter = $recommenderFilter;
	}

	/**
	 * Iterates through $splPriorityQueue, formats each item and concatenates the result.
	 *
	 * NOTE: $recommenderFilter is not used for rendering the view, however it is available for children classes
	 * to override this method and append filtering data to the view when needed.
	 *
	 * @param SplPriorityQueue $splPriorityQueue
	 *
	 * @return string
	 */
	public function render( SplPriorityQueue $splPriorityQueue ) : string {
		if( $splPriorityQueue->isEmpty() ) {
			return static::getEmptyQueueMessage();
		}

		$formattedResults = [];
		$splPriorityQueue->top();

		while( $splPriorityQueue->valid() ) {
			$recommendation = $splPriorityQueue->current();

			if( $recommendation instanceof Recommendation ) {
				$formattedResults [] = static::formatRecommendation( $recommendation );
			}

			$splPriorityQueue->next();
		}

		return implode( static::getGlue(), $formattedResults );
	}

	abstract protected static function formatRecommendation( Recommendation $recommendation ) : string;

	abstract protected static function getEmptyQueueMessage() : string;

	abstract protected static function getGlue() : string;
}
