<?php

namespace SwitchTv\Challenges\MovieRecommendations\Controller;

use SwitchTv\Challenges\MovieRecommendations\Model\{
	RecommenderModel, MovieModel
};

use SwitchTv\Challenges\MovieRecommendations\View\{
	RecommenderView, MovieView
};

final class MovieController extends RecommenderController {

	protected static function generateModel( $recommenderFilter ) : RecommenderModel {
		return new MovieModel( $recommenderFilter );
	}

	protected static function generateView( $recommenderFilter ) : RecommenderView {
		return new MovieView( $recommenderFilter );
	}

	protected static function generateController( RecommenderModel $recommenderModel, RecommenderView $recommenderView ) : RecommenderController {
		return new MovieController( $recommenderModel, $recommenderView );
	}
}
