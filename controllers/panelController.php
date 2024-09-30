<?php 

	// se muestra el contenido de SESSION (para debug)
	$usuario = $_SESSION["morphyx"]["user"];
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		// var_dump($userAgent);
	// crea el objeto con la vista
	$tpl = new Kiwi("panel");

	// carga la vista
	$tpl->loadTPL();

	// Reemplaza las variables de la vista
	// $tpl->setVarsTPL(["USER_NAME" => $usuario->first_name]);

	// imprime en la vista en la página
	$tpl->printTPL();

 ?>