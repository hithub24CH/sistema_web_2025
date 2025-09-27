<?php
header('Content-Type: application/json');
include 'conexion.php';

try {
    // Obtener los datos JSON del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"));
    //var_dump($data); 
    if (isset($data->email) && isset($data->password)) {
        $email = $data->email;
        $password = $data->password;

        // Conectar a la base de datos
        $conex = conexion::Conectar();
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $conex->prepare($sql);
        $stmt->execute(array(':email' => $email));
        $sqlresult = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sqlresult) {
            echo json_encode([
                "success" => true,
                "message" => "Inicio de sesión exitoso",
                "nombre" => $sqlresult['nombre'],
                "appaterno" => $sqlresult['appaterno'],
                "foto" => $sqlresult['foto']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Email no encontrado"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
} finally {
    $conex = null; // Cerrar la conexión
}
?>
