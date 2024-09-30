<?php 

	// crea el objeto con la vista
	$tpl = new Kiwi("login");
	// carga la vista
	$tpl->loadTPL();
if(isset($_GET['btn_login'])){

		// crea un usuario
		$usuario = new User();		
		// Si hubo cualquier error se carga el mensaje de error de la vista
		$vars = ["MSG_REGISTER_ERROR" => $response["error"]];
	}
		
		

	$tpl->printTPL();

 ?>