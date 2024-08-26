<?php

	// crea un usuario
	

	// crea el objeto con la vista
	$tpl = new Clima("landing");

	// carga la vista
	$tpl->loadTPL();

	// array para pasar variables a la vista
	// $vars = ["CANT_USERS" => $users->getCantUsers(),
	// 		 "CANT_CENTRAL" => 50];

	// reemplaza las variables en la vista
	$tpl->setVarsTPL($vars);

	// imprime en la página la vista
	$tpl->printTPL();




 ?>