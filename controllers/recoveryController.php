	<?php 

	// crea el objeto con la vista
	$tpl = new Kiwi("recovery");

	// carga la vista
	$tpl->loadTPL();

	// crea el array con variables a reemplazar en la vista
	
$vars = ["MSG_RECOVERY_ERROR" => ""];
		// var_dump($_POST);
	// si se presiono el botón del formulario
	if(isset($_POST['btn_recovery'])){

		// crea un usuario
		$usuario = new User();

		// quitamos del array de post el boton
		unset($_POST['btn_recovery']);

		// procede a intentar el registro
		$response = $usuario->recovery($_POST);

		
		
		// Si hubo cualquier error se carga el mensaje de error de la vista
		$vars = ["MSG_RECOVERY_ERROR" => $response["error"]];
	}

	// se pasan las variables a la vista
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();


 ?>
 