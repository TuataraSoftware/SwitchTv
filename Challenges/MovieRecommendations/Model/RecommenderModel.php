<?php

namespace SwitchTv\Challenges\MovieRecommendations\Model;

use SplPriorityQueue;

abstract class RecommenderModel {

	protected $recommenderFilter;
	protected $splPriorityQueue;

	public function __construct( $recommenderFilter ) {
		$this->recommenderFilter = $recommenderFilter;
		$this->splPriorityQueue = new SplPriorityQueue();
	}

	public function geSplPriorityQueue() : SplPriorityQueue {
		return $this->splPriorityQueue;
	}

	abstract public function fetchRecommendations() : void;
}
