<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php'; // Autoload do PHPMailer
require_once __DIR__ . '/../../bd/config.php';  // Configuração do banco de dados

if (isset($_POST['email']) && isset($_POST['id_relatorio'])) {
    $email = $_POST['email'];
    $id_relatorio = $_POST['id_relatorio'];

    // Valida o e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'E-mail inválido!';
        exit();
    }

    // Obtém os dados do relatório do banco de dados
    $query = "SELECT r.*, c.nome_cliente, c.email_cliente, e.estabelecimento_contrato, t.nome_tecnico
              FROM relatorios r
              JOIN clientes c ON r.id_cliente = c.id_cliente
              JOIN contratos e ON r.id_estabelecimento = e.id_contrato
              LEFT JOIN tecnicos t ON r.id_tecnico = t.id_tecnico
              WHERE r.id_relatorio = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_relatorio);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $relatorio = $result->fetch_assoc();

        // Configura o PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurações SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dmmesquita0331@gmail.com'; // Substitua pelo seu e-mail
            $mail->Password = 'uqdq uxfy omfd ebre'; // Substitua pela senha do aplicativo
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuração de remetente e destinatário
            $mail->setFrom('dmmesquita0331@gmail.com', 'HigiPest'); // Remetente
            $mail->addAddress($email); // Destinatário (e-mail do cliente)

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Relatorio ';
            $mail->Body = "
                <h1>Relatório</h1>
                <p><strong>Cliente:</strong> {$relatorio['nome_cliente']}</p>
                <p><strong>Estabelecimento:</strong> {$relatorio['estabelecimento_contrato']}</p>
                <p><strong>Técnico:</strong> {$relatorio['nome_tecnico']}</p>
                <p><strong>Data de Criação:</strong> " . date('d/m/Y H:i', strtotime($relatorio['criado_em'])) . "</p>
                <p><strong>Descrição:</strong><br>" . nl2br(htmlspecialchars($relatorio['descricao'])) . "</p>
            ";

            // Enviar o e-mail
            $mail->send();
            echo 'E-mail enviado com sucesso!';
        } catch (Exception $e) {
            echo "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Relatório não encontrado!';
    }
} else {
    echo 'Dados ausentes para o envio do e-mail!';
}
?>
