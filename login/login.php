<?php
require_once __DIR__ . '/../includes/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $usuario = consultar_user($conexao, $email);

    $senhaValida = false;
    if ($usuario) {
        $senhaValida = password_verify($senha, $usuario['senha']);
    }

    if ($usuario && $senhaValida) {
        session_regenerate_id(true);
        $_SESSION['id'] = $usuario['id'];
        set_flash('success', 'Usuário logado!');
        redirecionar('/clubhawkings/index.php');
    } else {
        set_flash('error', 'Usuário ou senha inválido.');
        redirecionar('/clubhawkings/login/login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/clubhawkings/style/style.css">
    <title>Login</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <h1>Faça login para continuar</h1>

        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <p class="alert alert--<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></p>
        <?php endif; ?>

        <?php if (!isset($_SESSION['id'])): ?>
            <form action="" method="post">
                <label for="email">E-mail: </label>
                <input type="email" name="email" id="email" placeholder="Insira o email." required><br>
                <label for="senha">Senha: </label>
                <input type="password" name="senha" id="senha" placeholder="Insira sua senha." required><br>
                <input class="btn" type="submit" value="Entrar">
            </form>
            <p class="muted mt-4"><a href="cadastra.php">Ainda não tem conta? Cadastre-se</a></p>
        <?php else: ?>
            <p class="alert alert--success">Você já está logado.</p>
            <a class="btn" href="../index.php">Ir para o inicio</a>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
