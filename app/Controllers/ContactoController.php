<?php

	namespace App\Controllers;

	use League\Plates\Engine;

//	phpinfo();

	class ContactoController {

		protected $templates;

		public function __construct() {
			$this->templates = new Engine('../resources/views');
		}

		public function index($view) {
			echo $this->templates->render($view);
		}
	}

?>