<?php
// Destinatário
$to = "dmmesquita31@gmail.com";

// Assunto
$subject = "Teste de Email via PHP";

// Mensagem
$message = "Este é um email de teste enviado via PHP no XAMPP.";

// Cabeçalhos
$headers = "From: your_email@gmail.com";

// Envio
if (mail($to, $subject, $message, $headers)) {
    echo "Email enviado com sucesso!";
} else {
    echo "Falha ao enviar email.";
}
?>
