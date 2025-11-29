<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use GrupoA\Supermercado\Config\Config;

function enviarEmailCliente($destinatario, $nome, $mensagemHTML) {
    $mail = new PHPMailer(true);
    $emailConfig = Config::getEmailConfig();

try {

    // dados fixos
    $mail->isSMTP();
    $mail->Host = $emailConfig['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $emailConfig['username'];
    $mail->Password = $emailConfig['password'];
    $mail->SMTPSecure = $emailConfig['secure'];
    $mail->Port = $emailConfig['port'];

    $mail->setFrom($emailConfig['from_email'], $emailConfig['from_name']);

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
