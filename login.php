<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HigiPest - Login</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .content {
            flex: 1; /* Faz com que o conteúdo ocupe o espaço restante */
            display: flex;
            align-items: center; /* Centraliza verticalmente */
            justify-content: center; /* Centraliza horizontalmente */
        }
    </style>
</head>
<body>

    <?php require('components/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6"> <!-- Aumentando a largura do card -->
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <h2 class="text-center mb-4">Login</h2>

                            <!-- Mensagem de erro -->
                            <?php if (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
                                <div class="alert alert-danger text-center" role="alert">
                                    E-mail ou palavra-passe incorretos.
                                </div>
                            <?php endif; ?>

                            <!-- Formulário de login -->
                            <form action="/web/login/verificar-login.php" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Entrar</button>
                                <a href="/web/login/recuperarpasse.php" class="btn btn-link">Recuperar Palavra-Passe</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer - footer.php -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
