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
</head>
<body class="d-flex flex-column" style="min-height: 100vh;">

    <?php require('components/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center">Login</h2>

        <!-- Mensagem de erro -->
        <?php if (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
            <div class="alert alert-danger text-center" role="alert">
                E-mail ou palavra-passe incorretos.
            </div>
        <?php endif; ?>

        <!-- Form de login -->
        <form action="/web/login/verificar-login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
            <a href="/web/login/recuperarpasse.php" class="btn btn-link">Recuperar Palavra-Passe</a>
        </form>
    </div>
    
    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer - footer.php -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- menu dropdown funciona atraves deste script -->

</body>
</html>
