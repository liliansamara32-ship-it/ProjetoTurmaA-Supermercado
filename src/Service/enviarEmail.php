<?php
require_once('phpMailer/PHPMailer.php');
require_once('phpMailer/SMTP.php');
require_once('phpMailer/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarEmailCliente($destinatario, $nome, $mensagemHTML) {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

try {

    // dados fixos
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sidsmart404@gmail.com';
    $mail->Password = 'SENHA_DO_APP_FIXA';
    $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('sidsmart404@gmail.com', 'Supermercado');

    // email do cliente
    $mail->addAddress($destinatario, $nome);

    $mail->isHTML(true);
    $mail->Subject = "Pedido confirmado - Sid's Mart";
    $mail->Body = $mensagemHTML;

    $mail->send();
    return true;
    

} catch (Exception $e) {
    error_log("Erro ao enviar email: " . $mail->ErrorInfo);
    return false;
    }
}
