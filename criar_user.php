<?php

//conexao bd
require_once __DIR__ . "/bd/config.php";


if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

//verificar se o form enviou dados para criar user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $palavrapasse = mysqli_real_escape_string($conn, $_POST['palavra-passe']);
    $cargo = mysqli_real_escape_string($conn, $_POST['cargo']);

    //encriptar palavra-passe 
    $palavrapasse_encriptada = password_hash($palavrapasse, PASSWORD_DEFAULT);

    //inserir dados na tabela de utilizadores
    $sql = "INSERT INTO utilizadores (nome, email, palavra_passe, cargo) 
            VALUES ('$nome', '$email', '$palavrapasse_encriptada', '$cargo')";

    //verificar se o utilizador é criado
    if (mysqli_query($conn, $sql)) {
        echo "Utilizador criado com sucesso. ";
        
        //se o cargo for técnico, inserir também na tabela de técnicos
        if ($cargo == "tecnico") {
            $id_utilizador = mysqli_insert_id($conn); //
            
            $sql_tecnico = "INSERT INTO tecnicos (id_tecnico, nome_tecnico, email_tecnico, palavra_passe_tecnico) 
                            VALUES ('$id_utilizador', '$nome', '$email', '$palavrapasse_encriptada')";
            
            if (mysqli_query($conn, $sql_tecnico)) {
                echo "Dados do técnico também foram adicionados.";
            } else {
                echo "Erro ao adicionar dados do técnico: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Erro: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Utilizador</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Criar utilizador</h2>

    <!-- form para criar user -->
    <form action="criar_user.php" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="palavra-passe" class="form-label">palavra-passe</label>
            <input type="password" class="form-control" id="palavra-passe" name="palavra-passe" required>
        </div>

        <div class="mb-3">
            <label for="cargo" class="form-label">Cargo</label>
            <select class="form-select" id="cargo" name="cargo" required>
                <option value="cliente">Cliente</option>
                <option value="administrador">Administrador</option>
                <option value="tecnico">Técnico</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Criar</button>
    </form>
</div>

<!-- Link para o Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
