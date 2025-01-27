<?php
//destinario
$to = "dmmesquita31@gmail.com";

//assunto
$subject = "Teste de Email via PHP";

//mensagem
$message = "Este é um email de teste enviado via PHP no XAMPP.";

//cabeçalhos
$headers = "From: your_email@gmail.com";

// Envio
if (mail($to, $subject, $message, $headers)) {
    echo "Email enviado com sucesso!";
} else {
    echo "Falha ao enviar email.";
}
?>
