<?php

namespace SwitchTv\Challenges\MovieRecommendations\Model;

final class Recommendation {

	private $name;
	private $value;
	private $priority;

	public function __construct( string $name, $value, int $priority ) {
		$this->name = $name;
		$this->value = $value;
		$this->priority = $priority;
	}

	public function getName() : string {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}

	public function getPriority() : int {
		return $this->priority;
	}
}
