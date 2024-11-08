<?php
session_start();
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $gender = htmlspecialchars($_POST['gender']);

    $sql = "INSERT INTO users (name, email, gender) VALUES (?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $gender);

    if ($stmt->execute()) {
        $_SESSION['Nombre'] = $name;
        $_SESSION['Correo'] = $email;
        $_SESSION['Genero'] = $gender;

        header("Location: segunda_vista.php");
        exit();
    } else {
        echo "Error al insertar datos: " . $mysqli->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de usuario - Catálogo de juguetes</title>
        <style>
            /*CSS para darle vista al formulario */
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: Arial, Helvetica, sans-serif;
                background-color: #f4f3f9;
            }

            .container{
                text-align: center;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                width: 90%;
                max-width: 400px;
            }

            h2 {
                color: #333;
            }

            label {
                display: block;
                margin-top: 15px;
                font-weight: bold;
                color: #555;
            }

            input[type="text"],
            input[type="email"],
            select {
                width: 100%;
                padding: 10px;
                margin-top: 5px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-sizing: border-box;
            }

            button{
                margin-top: 20px;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                background-color: #007bff;
                color: #fff;
                font-size: 16px;
                cursor: pointer;
            }

            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Bienvenido al catálogo de juguetes</h2>
            <form method="POST" action="index.php">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" required>

                <label for="gender">Género:</label>
                <select id="gender" name="gender" required>
                    <option value="niño">Niño</option>
                    <option value="niña">Niña</option>
                </select>

                <button type="submit">Enviar</button>
            </form>
        </div>
    </body>
</html>