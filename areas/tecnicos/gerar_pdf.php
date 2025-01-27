<?php
require_once __DIR__ . "/../../vendor/tecnickcom/tcpdf/tcpdf.php";
require_once __DIR__ . "/../../bd/config.php";

//obter o ID do relatório da URL
$id_relatorio = $_GET['id'] ?? null;

if (!$id_relatorio) {
    die("ID do relatório não fornecido!");
}

//consulta para obter dados do relatorio
$query_relatorio = "
    SELECT r.*, c.nome_cliente, c.email_cliente, e.estabelecimento_contrato, t.nome_tecnico
    FROM relatorios r
    JOIN clientes c ON r.id_cliente = c.id_cliente
    JOIN contratos e ON r.id_contrato = e.id_contrato
    LEFT JOIN tecnicos t ON r.id_tecnico = t.id_tecnico
    WHERE r.id_relatorio = ?
";

$stmt = $conn->prepare($query_relatorio);
$stmt->bind_param("i", $id_relatorio);
$stmt->execute();
$result = $stmt->get_result();

//verifica se o relatório foi encontrado
if ($result->num_rows > 0) {
    $relatorio = $result->fetch_assoc();
} else {
    die("Relatório não encontrado.");
}

$stmt->close();
$conn->close();

//criação do objeto TCPDF
$pdf = new TCPDF();
$pdf->AddPage();

// Título
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Relatório do Cliente: ' . htmlspecialchars($relatorio['nome_cliente']), 0, 1, 'C');

// Adicionar conteúdo do relatório
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(10); // Quebra de linha
$pdf->Cell(0, 10, 'Cliente: ' . htmlspecialchars($relatorio['nome_cliente']), 0, 1);
$pdf->Cell(0, 10, 'Estabelecimento: ' . htmlspecialchars($relatorio['estabelecimento_contrato']), 0, 1);
$pdf->Cell(0, 10, 'Técnico: ' . htmlspecialchars($relatorio['nome_tecnico']), 0, 1);
$pdf->Cell(0, 10, 'Data de Criação: ' . date('d/m/Y H:i', strtotime($relatorio['criado_em'])), 0, 1);
$pdf->Ln(5); // Quebra de linha
$pdf->MultiCell(0, 10, 'Descrição: ' . nl2br(htmlspecialchars($relatorio['descricao'])));

// Fechar e gerar o PDF
$pdf->Output('relatorio_' . $id_relatorio . '.pdf', 'D');
