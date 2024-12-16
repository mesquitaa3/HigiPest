<?php

$servername = "localhost"; 
$username = "web";         
$password = "web";         
$dbname = "web-project";   

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar se a conexão existe
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

echo "funciona!";
?>
