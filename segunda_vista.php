<?php
session_start();

if (!isset($_SESSION['Nombre']) || !isset($_SESSION['Genero'])) {
    header("Location: index.php");
    exit();
}

$nombre = $_SESSION['Nombre'];
$genero = $_SESSION['Genero'];

include 'config.php';

$sql = "SELECT name, price, image AS image FROM toys WHERE gender = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $genero);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catálogo de juguetes</title>
        <style>
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                
                justify-content: center;
                font-family: Arial, Helvetica, sans-serif;
                background-color: #f4f4f9;
                overflow: auto;
            }

            .container {
                text-align: center;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                width: 90%;
                max-width: 500px;
                margin: auto;
            }
            h2 {
                color: #333;
            }

            ul{
                list-style-type: none;
                padding: 0;
            }

            li {
                margin: 15px 0;
                border-bottom: 1px solid #ddd;
                padding-bottom: 10px;
            }

            img {
                width: 150px;
                height: 150px;
                object-fit: cover;
                border-radius: 5px;
            }

            button {
                padding: 8px 15px;
                border: none;
                border-radius: 5px;
                background-color: #007bff;
                color: #fff;
                cursor: pointer;
            }

            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Hola, <?php echo htmlspecialchars($nombre); ?>. Estos son los juguetes recomendados para <?php echo htmlspecialchars($genero); ?>s:</h2>

            <?php if ($result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <?php if (!empty($row['image'])): ?>
                                <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" ><br>
                            <?php endif; ?>
                            <strong><?php echo htmlspecialchars($row['name'] ?? 'Nombre no disponible'); ?></strong><br>
                            Precio: $<?php echo htmlspecialchars($row['price'] ?? '0.00'); ?><br>
                            <form method="POST" action="enviar_juguete.php">
                                <input type="hidden" name="juguete" value="<?php echo htmlspecialchars($row['name'] ?? ''); ?>">
                                <button type="submit">Enviar información del juguete</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay juguetes disponibles para <?php echo htmlspecialchars($genero); ?>s en este momento.</p>
        <?php endif; ?>

        </div>
    </body>
</html>