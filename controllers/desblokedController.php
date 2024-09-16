<?php 

	// crea el objeto con la vista
	$tpl = new Kiwi("desblocked");
/*< Captura de de la url el valor de la variable token*/
	$vars = ["TOKEN" => explode("=", $_SERVER["REQUEST_URI"])[1]];
	
	// crea el objeto con la vista


	
		$user = new User();
		// crea un usuario
		
		
		// quitamos del array de post el boton
		

		// procede a intentar el registro
		$response = $user->desblocked(explode("=", $_SERVER["REQUEST_URI"])[1]);
		// Si hubo cualquier error se carga el mensaje de error de la vista
		// var_dump($response);

		$vars = ["MSG_REGISTER_ERROR" => $response["error"]];
	
	// carga la vista			
	$tpl->loadTPL();

	/*< pasa el valor de la variable token a la vista*/
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();
	
 ?>