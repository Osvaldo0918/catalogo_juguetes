<?php
session_start();
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $gender = htmlspecialchars($_POST['gender']);

    $sql = "INSERT INTO users (name, email, gender) VALUES (?,?,?)";
    $stml = $mysqli->prepare($sql);
    $stml->bind_param("sss", $name, $email, $gender);

    if ($stml->execute()) {
        $_SESSION['Nombre'] = $name;
        $_SESSION['Correo'] = $email;
        $_SESSION['Genero'] = $gender;

        header("Location: segunda_vista.php");
        exit();
    } else {
        echo "Error al insertar datos: " . $mysqli->error;
    }

    $stml->close();
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de usuario - Catálogo de juguetes</title>
    </head>
    <body>
        <h2>Bienvenido al catálogo de juguetes</h2>
        <form method="POST" action="index.php">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Correo:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="gender">Genero:</label>
            <select id="gender" name="gender" required>
                <option value="niño">Niño</option>
                <option value="niña">Niña</option>
            </select><br><br>

            <button type="submit">Enviar</button>
        </form>
    </body>
</html>