<?php
header('Content-Type: application/json');

$servername = "localhost"; // Cambia esto según tu configuración
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_base_de_datos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Verificar que se haya pasado el parámetro correcto
if (isset($_GET['list-clients-location'])) {
    $sql = "SELECT ip, latitude, longitude, COUNT(*) as access_count 
            FROM tracker 
            GROUP BY ip, latitude, longitude";

    $result = $conn->query($sql);

    $clients = [];

    if ($result->num_rows > 0) {
        // Recorrer los resultados y almacenarlos en un array
        while ($row = $result->fetch_assoc()) {
            $clients[] = [
                'ip' => $row['ip'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'access_count' => 1 // 1 si no está repetido
            ];
        }
    } else {
        echo json_encode([]);
        exit;
    }

    // Devolver el resultado en formato JSON
    echo json_encode($clients);
} else {
    echo json_encode(["error" => "Invalid parameter."]);
}

$conn->close();
?>
