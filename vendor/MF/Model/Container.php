<?php

namespace MF\Model;

use App\Connection;

class Container {

	public static function getModel($model) {
		// ele está pegando a classe 
		$class = "\\App\\Models\\".ucfirst($model);
		$conn = Connection::getDb();

		return new $class($conn);
	}
}


?>