<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y limpiar los datos del formulario
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Array para almacenar los errores
    $errors = [];

    // Validación de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Correo electrónico no válido.";
    }

    // Validación de teléfono (solo números, longitud mínima de 7 caracteres)
    if (!preg_match("/^[0-9]{7,}$/", $phone)) {
        $errors[] = "Teléfono no válido. Debe contener al menos 7 dígitos numéricos.";
    }

    // Validación de campos vacíos
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    // Si hay errores, los devolvemos a JavaScript
    if (!empty($errors)) {
        echo json_encode([
            "status" => "error",
            "errors" => $errors
        ]);
        exit;
    }

    // Si no hay errores, procesamos el formulario (ejemplo: enviar un correo)
    $to = "tu_correo@ejemplo.com";  // Cambia esto por tu dirección de correo
    $email_subject = "Nuevo mensaje de: $name - $subject";
    $email_body = "Has recibido un nuevo mensaje de contacto.\n\n".
                  "Nombre: $name\n".
                  "Correo: $email\n".
                  "Teléfono: $phone\n".
                  "Asunto: $subject\n".
                  "Mensaje:\n$message";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Intentar enviar el correo
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Gracias por contactarte con nosotros, te responderemos a la brevedad."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Hubo un problema al enviar el mensaje. Inténtalo de nuevo."
        ]);
    }
}
?>
