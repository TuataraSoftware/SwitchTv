<?php

namespace SwitchTv\Challenges\MovieRecommendations\Controller;

use SwitchTv\Challenges\MovieRecommendations\Model\RecommenderModel;
use SwitchTv\Challenges\MovieRecommendations\View\RecommenderView;

abstract class RecommenderController {

	protected $recommenderModel;
	protected $recommenderView;

	public static function getRecommendations( $recommenderFilter ) : string {
		$recommenderController = self::getInstance( $recommenderFilter );

		$recommenderController->recommenderModel->fetchRecommendations();

		return $recommenderController->recommenderView->render( $recommenderController->recommenderModel->geSplPriorityQueue() );
	}

	protected function __construct( RecommenderModel $recommenderModel, RecommenderView $recommenderView ) {
		$this->recommenderModel = $recommenderModel;
		$this->recommenderView = $recommenderView;
	}

	abstract protected static function generateModel( $recommenderFilter ) : RecommenderModel;

	abstract protected static function generateView( $recommenderFilter ) : RecommenderView;

	abstract protected static function generateController( RecommenderModel $recommenderModel, RecommenderView $recommenderView ) : self;

	private static function getInstance( $recommenderFilter ) : self {
		$recommenderModel = static::generateModel( $recommenderFilter );
		$recommenderView = static::generateView( $recommenderFilter );
		$recommenderController = static::generateController( $recommenderModel, $recommenderView );

		return $recommenderController;
	}
}
