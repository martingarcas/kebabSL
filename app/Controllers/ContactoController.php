<?php

	namespace App\Controllers;

	use League\Plates\Engine;

	class ContactoController {
		protected $templates;

		public function __construct() {
			$this->templates = new Engine('../templates');
		}

		public function index() {
			echo $this->templates->render('contacto');
		}
	}

?>