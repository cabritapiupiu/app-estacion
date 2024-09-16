<?php 

	// crea el objeto con la vista
	$tpl = new Kiwi("reset");
/*< Captura de de la url el valor de la variable token*/
	$vars = ["TOKEN" => explode("=", $_SERVER["REQUEST_URI"])[1]];
	
	// crea el objeto con la vista


	if(isset($_POST['btn_reset'])){
		$user = new User();
		// crea un usuario
		
		
		// quitamos del array de post el boton
		unset($_POST['btn_reset']);

		// procede a intentar el registro
		$response = $user->reset_($_POST,explode("=", $_SERVER["REQUEST_URI"])[1]);
		// Si hubo cualquier error se carga el mensaje de error de la vista
		

		$vars = ["MSG_REGISTER_ERROR" => $response["error"]];
	}
	// carga la vista			
	$tpl->loadTPL();

	/*< pasa el valor de la variable token a la vista*/
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();
	
 ?>