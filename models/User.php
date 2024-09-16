<?php 

	/**
	* @file User.php
	* @brief Declaraciones de la clase User para la conexión con la base de datos.
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/

	// incluye la libreria para conectar con la db
	include_once 'DBAbstract.php';

	/*< incluye la clase Mailer.php para enviar correo electrónico*/
	include_once 'Mailer.php';

	// se crea la clase User que hereda de DBAbstract
	class User extends DBAbstract{

		private $nameOfFields = array();

		/**
		 * 
		 * @brief Es el constructor de la clase User
		 * 
		 * Al momento de instanciar User se llama al padre para que ejecute su constructor
		 * 
		 * */
		function __construct(){
		
			// quiero salir de la clase actual e invocar al constructor
			parent::__construct();

			/**< Obtiene la estructura de la tabla */
			$result = $this->query('DESCRIBE User');

			foreach ($result as $key => $row) {
				$buff =$row["Field"];
				
				/**< Almacena los nombres de los campos*/
				$this->nameOfFields[] = $buff;

				/**< Autocarga de atributos a la clase */
				$this->$buff=NULL;
			}
			

		}

		/**
		 * 
		 * Hace soft delete del registro
		 * @return bool siempre verdadero
		 * 
		 * */
		function leaveOut(){

			$id = $this->id;
			$fecha_hora = date("Y-m-d H:i:s");

			$ssql = "UPDATE User SET add_date='$fecha_hora' WHERE id=$id";

			$this->query($ssql);

			return true;
		}


function desbloquear($email,$token){


	$correo = new Mailer();

				/*< plantilla de email para validar cuenta*/
				$cuerpo_email = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Inicio de Sesión</title>
<style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center;
}

h1 {
    color: #6d1b7b;
    font-size: 24px;
    margin-bottom: 15px;
}

.info-table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

.info-table td {
    padding: 8px;
    border: 1px solid #ddd;
}

.info-table td:first-child {
    font-weight: bold;
    text-align: left;
    width: 40%;
}

.alert-button {
    background-color: #d9534f;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.alert-button:hover {
    background-color: #c9302c;
}

p {
    margin-bottom: 15px;
}
    </style>

</head>
<body>
    <div class="container">
        <h1>Información para desbloquear email</h1>
        <p>Si no fuiste tú quien inició sesión y deseas cambiar el password :</p>

 <a href="https://mattprofe.com.ar/alumno/6829/aux/reset?token='.$token.'" class="button">Click aquí para cambiar contraseña</a>
		
    </div>
</body>
</html>';


               
        // https://mattprofe.com.ar/alumno/6829/aux/verify?token='.$token_email.'
				/*< envia el correo electrónico de recovery*/
					$correo->send(["destinatario" => $email, "motivo" => "Recuperacion de cuenta", "contenido" => $cuerpo_email] );

		}


function desblocked($token){
		$result = $this->query("SELECT * FROM User WHERE token ='$token'");
			if($result != []){
				
				if($result [0]["token_action"] != "" AND  $result[0]["bloqueado"] == 1){

					$token_action = "";
						$email= $result["email"];
						$ssql = "UPDATE User SET  blocked_date ='NULL' bloqueado = '0' , token_action = '$token_action' WHERE  token = '$token'";
						$result = $this->query($ssql);
						$this->desbloquear($token,$email);
						return ["error" => "usuario desbloqueado, revise su correo electrónico", "errno" => 200];
				}
						
						
			}
				return ["error" => "El token no corresponde a un usuario", "errno" => 400];		
	}



function blocked($token){
		$result = $this->query("SELECT * FROM User WHERE token ='$token'");
			if($result != []){
				
				if($result [0]["token_action"] == "" AND  $result[0]["activo"] == 1){}
						$email= $result [0]["email"];
						$token_action = md5($_ENV['PROJECT_WEB_TOKEN'].$email);
						$bloqueado=1;
						$fecha_hora = date("Y-m-d H:i:s");
						$ssql = "UPDATE User SET  bloqueado = '$bloqueado' , token_action = '$token_action' , blocked_date = '$fecha_hora' WHERE  token = '$token'";
						$result = $this->query($ssql);
						$this->desbloquear($email,$token);
						return ["error" => "Usuario bloqueado, revise su correo electrónico", "errno" => 200];
			}
				return ["error" => "El token no corresponde a un usuario", "errno" => 400];		
	}


function obtenerNavegador($user_agent) {
    $navegador = "Desconocido";

    if (preg_match('/MSIE/i', $user_agent) || preg_match('/Trident/i', $user_agent)) {
        $navegador = 'Internet Explorer';
    } elseif (preg_match('/Firefox/i', $user_agent)) {
        $navegador = 'Mozilla Firefox';
    } elseif (preg_match('/Chrome/i', $user_agent)) {
        $navegador = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $user_agent)) {
        $navegador = 'Apple Safari';
    } elseif (preg_match('/Opera/i', $user_agent) || preg_match('/OPR/i', $user_agent)) {
        $navegador = 'Opera';
    } elseif (preg_match('/Edge/i', $user_agent)) {
        $navegador = 'Microsoft Edge';
    }

    return $navegador;
}






function obtenerIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP desde proxies compartidos
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // IP a través de un proxy
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // IP remota por defecto
        return $_SERVER['REMOTE_ADDR'];
    }
}


// 
function informacion($email,$token){



				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				$navegador = $this->obtenerNavegador($user_agent);
				

			
			$so = "Desconocido";
			$ip_cliente = $this->obtenerIP();
			if (preg_match('/linux/i', $navegador)) {
    			$so = 'Linux';
				} elseif (preg_match('/macintosh|mac os x/i', $navegador)) {
    					$so = 'Mac';
					} elseif (preg_match('/windows|win32|win64/i', $navegador)) {
    					$so = 'Windows';
					}

/*< instancia la clase Mailer para enviar el correo electrónico de validación de correo electrónico*/
				$correo = new Mailer();

				/*< plantilla de email para validar cuenta*/
				$cuerpo_email = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Inicio de Sesión</title>
<style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center;
}

h1 {
    color: #6d1b7b;
    font-size: 24px;
    margin-bottom: 15px;
}

.info-table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

.info-table td {
    padding: 8px;
    border: 1px solid #ddd;
}

.info-table td:first-child {
    font-weight: bold;
    text-align: left;
    width: 40%;
}

.alert-button {
    background-color: #d9534f;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.alert-button:hover {
    background-color: #c9302c;
}

p {
    margin-bottom: 15px;
}
    </style>

</head>
<body>
    <div class="container">
        <h1>Información de Inicio de Sesión</h1>
        <p>Hemos detectado un inicio de sesión en tu cuenta con la siguiente información:</p>
        
        <table class="info-table">
            <tr>
             	<td><strong>IP</strong></td>
                <td>'.$ip_cliente.'</td>
            </tr>
            <tr>
            	<td><strong>Sistema Operativo</strong></td>
                 <td>'. $so.'</td>
            </tr>
            <tr>
            	<td><strong>Navegador Web</strong></td>
                <td>'.$navegador.'</td>
            </tr>
        </table>

        <p>Si no fuiste tú quien inició sesión, por favor, bloquea tu cuenta inmediatamente haciendo clic en el siguiente botón:</p>

 <a href="https://mattprofe.com.ar/alumno/6829/aux/blocked?token='.$token.'" class="button">No fui yo, bloquear cuenta</a>

        <button class="alert-button"></button>
    </div>
</body>
</html>';


               
        // https://mattprofe.com.ar/alumno/6829/aux/verify?token='.$token_email.'
				/*< envia el correo electrónico de recovery*/
					$correo->send(["destinatario" => $email, "motivo" => "Recuperacion de cuenta", "contenido" => $cuerpo_email] );

		}








			/**
		 * 
		 * Hace soft delete del registro
		 * @return bool siempre verdadero
		 * 
		 * */
			function verify($token){

					$id = $this->id;
					$fecha_hora = date("Y-m-d H:i:s");

					$ssql = "UPDATE User SET activo= 1 ,token_action='',add_date= NOW() WHERE token_action = '$token'";

					$this->query($ssql);

					return true;
		}

		// 	function reset($token){
		// 			$ssql = "UPDATE User SET  token_action='', WHERE token_action = '$token'";
		// 			$this->query($ssql);

		// 			return true;
		// }

		/**
		 * 
		 * Finaliza la sesión
		 * @return bool true
		 * 
		 * */
		function logout(){
			return true;
		}

		/**
		 * 
		 * Intenta loguear al usuario mediante email y contraseña
		 * @param array $form indexado de forma asociativa
		 * @return array que posee códigos de error especiales
		 * */
		function login($form){

			/*< recupera el method http*/
			$request_method = $_SERVER["REQUEST_METHOD"];

			/* si el method es invalido*/
			// if($request_method!="GET"){
			// 	return ["errno" => 410, "error" => "Metodo invalido"];
			// }

			/*< recupera el email del formulario*/
			$email = $form["login_email"];

			/*< consultamos si existe el email*/
			$result = $this->query("CALL `login`('$email')");

			// el email no existe
			if(count($result)==0){
				return ["error" => "Email no registrado", "errno" => 404];
			}

			/*< seleccionamos solo la primer fila de la matriz*/
			$result = $result[0];
						
			// si el email existe y la contraseña es valida


if($result["activo"]==0){

				
				return ["error" => "Su usuario aún no se ha validado, revise su casilla decorreo", "errno" => 406];
			}

			if($result["password"] != md5($form["txt_pass"]."morphyx")){
					 $token=$result["token"];
					$this->informacion($email,$token);
					return ["error" => "Error de contraseña", "errno" => 405];
				
			}
			if ($result["bloqueado"] == "1") {
				// funcion de des bloqueo
				return ["error" => " Su usuario esta bloqueado rebise su email", "errno" => 407];
			}

			

				foreach ($this->nameOfFields as $key => $value) {
					$this->$value = $result[$value];
				}
				/*< carga la clase en la sesión*/
				$_SESSION["morphyx"]['user'] = $this;

				/*< usuario valido*/
				$token=$result["token"];
				$this->informacion($email,$token);
				return ["error" => "Acceso valido", "errno" => 200];
				

		}

	


function reset_($form,$token){
		
		$result = $this->query("SELECT * FROM User WHERE token = '$token'");
	if ($result != []) {

		if ($form["reset_pass"] == $form["reset_pass2"]) {
			$pass= md5($form["reset_pass"]."morphyx");
			$ssql = "UPDATE User SET  password='$pass',blocked_date ='NULL',token_action= '', bloqueado = '0'
			 WHERE  token = '$token'";
			$result = $this->query($ssql);
			// var_dump($form["reset_pass"]);

			return ["error" => "contraseña cambiada", "errno" => 200];
		}

	}

	return ["error" => "contraseña no cambiada", "errno" => 400];

}

		/**
		 * Esta recupera la contrasera en el caso de perderla o nesesitarla
		 * @param array $form es un arreglo assoc con los datos del formulario
		 * @return array que posee códigos de error especiales  
		 * */
		function recovery($form){
			$email = $form["recovery_email"];
			$result = $this->query("SELECT * FROM User WHERE email = '$email'");
			if ($result != []) {
				

				/*< se crea el token único para validar el correo electrónico*/
				$token = $result[0]["token"];
				

			

				/*< instancia la clase Mailer para enviar el correo electrónico de validación de correo electrónico*/
				$correo = new Mailer();

				/*< plantilla de email para validar cuenta*/
				$cuerpo_email = '<!DOCTYPE html>
					<html lang="es">
						<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
        }
        .header h1 {
            margin: 0;
            color: #4CAF50;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 0;
            background-color: #4CAF50;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding: 10px;
        }
    </style>
						</head>
						<body>
    <div class="container">
        <div class="header">
            <h1>ClimaNow</h1>
        </div>
        <div class="content">
            <p>Recuperación de Cuenta</p>
            <p>ClimaNow Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si no solicitaste este cambio, puedes ignorar este correo.

				Para restablecer tu contraseña, por favor haz clic en el siguiente botón:</p>
            <a href="https://mattprofe.com.ar/alumno/6829/aux/reset?token='.$token.'" class="button">recuperar contraseña</a>
        </div>
        <div class="footer">
            <p>&copy; 2024 ClimaNow. Todos los derechos reservados.</p>
        </div>
    </div>
						</body>
					</html>
                    ';
				/*< envia el correo electrónico de recovery*/
					$correo->send(["destinatario" => $email, "motivo" => "Recuperacion de cuenta", "contenido" => $cuerpo_email] );

				/*< aviso de registro exitoso*/
				return ["error" => "Email enviado", "errno" => 200];
				
			}
			return ["error" => "Email no encontrado", "errno" => 405];

		}



		/**
		 * 
		 * Agrega un nuevo usuario si no existe el correo electronico en la tabla users
		 * @param array $form es un arreglo assoc con los datos del formulario
		 * @return array que posee códigos de error especiales 
		 * 
		 * */
		function register($form){
			/*< recupera el email*/
			$email = $form["txt_email"];

			/*< consulta si el email ya esta en la tabla de usuarios*/
			$result = $this->query("SELECT * FROM User WHERE email = '$email'");
						
							if ($form["txt_pass"] != $form["txt_pass2"]) {
								return ["error" => "Las contraseñas no coinsiden", "errno" => 404];
							}
							
							
			// el email no existe entonces se registra
			if($result == [] ){


				/*< encripta la contraseña*/
				$pass = md5($form["txt_pass"]."morphyx");

				/*< se crea el token único para validar el correo electrónico*/
				$token_email = md5($_ENV['PROJECT_WEB_TOKEN'].$email);
				$token = md5($_ENV['PROJECT_WEB_TOKEN'].$email);

				/*< agrega el nuevo usuario y deja en pendiente de validar su email*/
				$ssql = "INSERT INTO User (token,email, password,token_action) 
				VALUES ('$token','$email','$pass', '$token_email')";

				/*< ejecuta la consulta*/
				$result = $this->query($ssql);

				/*< se recupera el id del nuevo usuario*/
				$this->id = $this->db->insert_id;

				/*< instancia la clase Mailer para enviar el correo electrónico de validación de correo electrónico*/
				$correo = new Mailer();

				/*< plantilla de email para validar cuenta*/
				$cuerpo_email = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
        }
        .header h1 {
            margin: 0;
            color: #4CAF50;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 0;
            background-color: #4CAF50;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ClimaNow</h1>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Gracias por registrarte en ClimaNow. Para completar tu registro, por favor confirma tu dirección de correo electrónico haciendo clic en el botón de abajo:</p>
            <a href="https://mattprofe.com.ar/alumno/6829/aux/verify?token='.$token_email.'" class="button">Verificar Email</a>
            <p>Si no te registraste en ClimaNow , ignora este correo electrónico.</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 ClimaNow. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
';

				/*< envia el correo electrónico de validación*/
				$correo->send(["destinatario" => $email, "motivo" => "Confirmación de registro", "contenido" => $cuerpo_email] );

				/*< aviso de registro exitoso*/
				return ["error" => "Usuario registrado rebise el gmail", "errno" => 200];

			}		

			$date_zero = '0000-00-00 00:00:00';

			// El usuario volvio a la aplicacion
				
			// if($result['add_date'] != NULL){

			// 	/*< recupera el id del usuario que quiere volver a nuestra app*/
			// 	$id=$result["id"];
			// 	$this->id = $result["id"];

			// 	/*< encripta la nueva contraseña*/
			// 	$pass = md5($form["txt_pass"]."morphyx");

			// 	/*< consulta para volver a activar el usuario que se había ido
			// 	  first_name='', last_name=''  'delete_at' in 'field list' */
			// 	$ssql = "UPDATE User SET  `password`='$pass', add_date='0000-00-00 00:00:00' WHERE id=$id";

			// 	/*< ejecuta la consulta*/
			// 	$result = $this->query($ssql);

			// 	/*< mensaje de usuario volvio a la app*/
			// 	return ["error" => "Usuario que abandono volvio a la app", "errno" => 201];
				
			// }

			// si el email existe 
			return ["error" => "Correo ya registrado", "errno" => 405];

		}





		/**
		 * 
		 * Agrega un nuevo usuario si no existe el correo electronico en la tabla users
		 * @param array $form es un arreglo assoc con los datos del formulario
		 * @return array que posee códigos de error especiales 
		 * 
		 * */

		/**
		 * 
		 * Actualiza los datos del usuario con los datos de un formulario
		 * @param array $form es un arregle asociativo con los datos a actualizar
		 * @return array arreglo con el código de error y descripción
		 * 
		 * */
		function update($form){
			$nombre = $form["txt_first_name"];
			$apellido = $form["txt_last_name"];
			$id = $this->id;


			$this->first_name = $nombre;
			$this->last_name = $apellido;

			$ssql = "UPDATE User SET first_name='$nombre'  WHERE id=$id";

			$result = $this->query($ssql);

			return ["error" => "Se actualizo correctamente", "errno" => 200];
		}

		/**
		 * 
		 * Cantidad de usuarios registrados
		 * @return int cantidad de usuarios registrados
		 * 
		 * */
		function getCantUsers(){

			$result = $this->query("SELECT * FROM User");

			return $this->db->affected_rows;
		}


		/**
		 * 
		 * @brief Retorna un listado limitado
		 * @param string $request_method espera a GET
		 * @param array $request [inicio][cantidad]
		 * @return array lista con los datos de los usuarios 
		 * 
		 * */
		function getAllUsers($request){

			$request_method = $_SERVER["REQUEST_METHOD"];

			/*< Es el método correcto en HTTP?*/
			if($request_method!="GET"){
				return ["errno" => 410, "error" => "Metodo invalido"];
			}

			/*< Solo un usuario logueado puede ver el listado */
			if(!isset($_SESSION["morphyx"])){
				return ["errno" => 411, "error" => "Para usar este método debe estar logueado"];
			}

			


			$inicio = 0;

			if(isset($request["inicio"])){
				$inicio = $request["inicio"];
			}

			if(!isset($request["cantidad"])){
				return ["errno" => 404, "error" => "falta cantidad por GET"];
			}

			$cantidad = $request["cantidad"];

			$result = $this->query("SELECT * FROM User LIMIT $inicio, $cantidad");

			return $result;
		}

}
	

 