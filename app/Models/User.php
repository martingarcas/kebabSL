<?php
	namespace App\Models;

	class User {
		public function getUsers() {
			return [
				['name' => 'Juan', 'email' => 'juan@example.com'],
				['name' => 'Ana', 'email' => 'ana@example.com']
			];
		}
	}

?>
