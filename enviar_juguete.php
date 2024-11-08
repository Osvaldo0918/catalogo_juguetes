<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (!isset($_SESSION['Correo']) || !isset($_POST['juguete'])) {
    header("Location: index.php");
    exit();
}

$correo_destino = $_SESSION['Correo'];
$username = $_SESSION['Nombre'];
$juguete = htmlspecialchars($_POST['juguete']);

include 'config.php';

$sql ="SELECT name, price, image FROM toys WHERE name = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $juguete);
$stmt->execute();
$result = $stmt->get_result();
$toy = $result->fetch_assoc();

if (!$toy) {
    echo "No se encontró el juguete especificado.";
    exit();
}

$juguete = $toy['name'];
$precio_juguete = $toy['price'];
$imagen_juguete = $toy['image'];

$mail = new PHPMailer(true);

try{
    $mail->isSMTP();
    $mail->Host = 'smtp-mail.outlook.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'osvaldo_serrcu@comunidad.unam.mx';
    $mail->Password = 'Therasmus_09';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('osvaldo_serrcu@comunidad.unam.mx', 'Osvaldo');
    $mail->addAddress($correo_destino, $username);

    if (!empty($imagen_juguete)) {
        $mail->addEmbeddedImage("images/$imagen_juguete", 'imagen_juguete', $imagen_juguete);
    }

    $mail->isHTML(true);
    $mail->Subject = "Información sobre el juguete: $juguete";
    $mail->Body = "
        <h2>Hola, $username</h2>
        <p>Aquí tienes la información sobre el juguete que seleccionaste: </p>
        <h3>$juguete</h3>
        <p>Precio: <strong>$precio_juguete</strong></p>
        <p><img src='cid:imagen_juguete' style='width:200px; height:auto;'
    alt='$juguete'></p>
        <p>Esperamos que disfrutes del catálogo de juguetes.</p>
    ";

    $mail->send();
    echo "La información del juguete se ha enviado correctamente a tu correo.";
} catch (Exception $e) {
    echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
}
?>