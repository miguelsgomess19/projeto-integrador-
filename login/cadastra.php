<?php
require_once __DIR__ . '/../includes/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Informe um e-mail válido.');
        redirecionar('/clubhawkings/login/cadastra.php');
    }

    if (strlen($senha) < 6) {
        set_flash('error', 'A senha precisa ter no mínimo 6 caracteres.');
        redirecionar('/clubhawkings/login/cadastra.php');
    }

    try {
        cadastrar_user($conexao, $email, $senha);
        set_flash('success', 'Usuário cadastrado com sucesso! Faça login.');
        redirecionar('/clubhawkings/login/login.php');
    } catch (PDOException $e) {
        set_flash('error', 'Não foi possível cadastrar. E-mail já existe?');
        redirecionar('/clubhawkings/login/cadastra.php');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/clubhawkings/style/style.css">
    <title>Cadastro Usuário</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <h1>Cadastrar usuário</h1>

        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <p class="alert alert--<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <label for="email">E-mail: </label>
            <input type="email" name="email" id="email" placeholder="voce@email.com" required><br>
            <label for="senha">Senha: </label>
            <input type="password" name="senha" id="senha" placeholder="Mínimo 6 caracteres" required><br>
            <input class="btn" type="submit" value="Cadastrar">
            <a class="btn btn--ghost" href="login.php">Voltar</a>
        </form>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
