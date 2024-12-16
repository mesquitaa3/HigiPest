<?php
$testFile = __DIR__ . '/uploads/test.txt';
if (file_put_contents($testFile, "Teste de gravação bem-sucedido!")) {
    echo "Gravação bem-sucedida.";
} else {
    echo "Falha ao gravar.";
}
?>
