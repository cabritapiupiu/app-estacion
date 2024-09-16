<?php
	define("RELEASE", 0);
	define("DEBUG", 1);

	/*< Especifica el modo de trabajo de la aplicación*/
	define("MODE", DEBUG);

	// Código único para tokens de la aplicación
	define("WEB_TOKEN", "21ifvDGVQl8BaIAR1z7e0VWCfzAXOXeySa7vxpQoIuhSlLmL98fzRGuciXadwu14");

	//=== Variables de configuración del proyecto
	$_ENV["PROJECT_NAME"] = "ClimaNow";
	$_ENV["PROJECT_DESCRIPTION"] = "Web dedicada al monitoreo del clima";
	$_ENV["PROJECT_AUTHOR"] = "Luca Cabral";
	$_ENV["PROJECT_AUTHOR_CONTACT"] = "luca39760@gmail.com";
	$_ENV["PROJECT_URL"] = "https://mattprofe.com.ar/alumno/6829/app-estacion/app-estacion/";
	$_ENV["PROJECT_KEYWORDS"] = "ClimaNow monitoreo del clima";


	$_ENV["PROJECT_WEB_TOKEN"] = WEB_TOKEN;

	/*< Especifica el modo de carga de los CSS y JS */
	$_ENV["PROJECT_MODE"] = MODE ? "?var=".mt_rand(0, 1000) : "";


	//=== Acceso a la base de datos
	$_ENV["HOST"]= "mattprofe.com.ar";
	$_ENV["USER"]= "6829";
	$_ENV["PASS"]= "rata.higuera.guante";
	$_ENV["DB"]= "6829";
	$_ENV["PORT"] = 3306; // puerto de conexión a la DB

	//== Acceso a correo electrónico
	$_ENV["MAILER_REMITENTE"] = 'ClimaNow6829@gmail.com'; // <=== Correo de la app
	$_ENV["MAILER_NOMBRE"] = 'ClimaNow'; // <=== Nombre que aparece en el correo
	$_ENV["MAILER_PASSWORD"] = 'pzey womx tgqw xigo'; // <=== Token

	$_ENV["MAILER_HOST"] = 'smtp.gmail.com';
	$_ENV["MAILER_PORT"] = '587';
	$_ENV["MAILER_SMTP_AUTH"] = true;
	$_ENV["MAILER_SMTP_SECURE"] = 'tls';


 ?>