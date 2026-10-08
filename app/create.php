<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verifica_user.php';

$erro = null;
$senhaOk = !empty($_SESSION['admin_liberado']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etapa = $_POST['etapa'] ?? '';

    if ($etapa === 'senha') {
        $senhaAdmin = $_POST['senha_admin'] ?? '';

        if ($senhaAdmin !== '123456') {
            $erro = 'Senha de administrador incorreta.';
        } else {
            $_SESSION['admin_liberado'] = true;
            $senhaOk = true;
        }
    } elseif ($etapa === 'cadastro') {
        $nome = trim($_POST['nome'] ?? '');
        $estoque = (int) ($_POST['estoque'] ?? 0);
        $preco = (float) ($_POST['preco'] ?? 0);
        $imagem = $_FILES['imagem'] ?? null;

        if (!$senhaOk) {
            $senhaOk = false;
            $erro = 'Informe a senha de administrador para continuar.';
        } elseif ($nome === '') {
            $erro = 'Informe o nome do produto.';
        } elseif ($estoque < 0) {
            $erro = 'O estoque não pode ser negativo.';
        } elseif ($preco < 0) {
            $erro = 'O preço não pode ser negativo.';
        } elseif (!$imagem || $imagem['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Envie uma imagem válida.';
        } else {
            $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($extensao, $permitidas, true)) {
                $erro = 'Formato de imagem não permitido. Use jpg, jpeg, png, gif ou webp.';
            } elseif ($imagem['size'] > 5 * 1024 * 1024) {
                $erro = 'A imagem pode ter no máximo 5MB.';
            } else {
                $pastaUploads = __DIR__ . '/../uploads';

                if (!is_dir($pastaUploads)) {
                    mkdir($pastaUploads, 0777, true);
                }

                $arquivo = 'produto_' . uniqid('', true) . '.' . $extensao;
                $destino = $pastaUploads . DIRECTORY_SEPARATOR . $arquivo;

                if (move_uploaded_file($imagem['tmp_name'], $destino)) {
                    redimensionar_imagem($destino);
                    cadastrar_produto($conexao, $nome, $estoque, $preco, $arquivo);
                    set_flash('success', 'Produto cadastrado com sucesso!');
                    redirecionar('/clubhawkings/app/produtos.php');
                } else {
                    $erro = 'Não foi possível salvar a imagem.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/clubhawkings/style/style.css">
    <title>Cadastro de Produto</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <p class="alert alert--<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></p>
        <?php endif; ?>
        <?php if ($erro): ?>
            <p class="alert alert--error"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <?php if (!$senhaOk): ?>
            <h1>Acesso restrito</h1>
            <h3>Informe a senha de administrador para cadastrar produtos.</h3>

            <form action="" method="POST">
                <input type="hidden" name="etapa" value="senha">

                <label for="senha_admin">Senha de administrador:</label>
                <input type="password" name="senha_admin" id="senha_admin" placeholder="Insira a senha." required>

                <button type="submit">Entrar</button>
                <a class="btn btn--ghost" href="/clubhawkings/app/produtos.php">Voltar</a>
            </form>
        <?php else: ?>
            <h1>Cadastro de Produto</h1>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="etapa" value="cadastro">

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" placeholder="Nome do produto" required>

                <label for="estoque">Estoque:</label>
                <input type="number" id="estoque" name="estoque" placeholder="Quantidade em estoque" min="0" required>

                <label for="preco">Preço (R$):</label>
                <input type="number" id="preco" name="preco" placeholder="Preço do produto" min="0" step="0.01" required>

                <label for="imagem">Imagem do produto:</label>
                <input type="file" id="imagem" name="imagem" accept="image/*" required>

                <button type="submit">Cadastrar</button>
                <button type="reset">Limpar</button>
            </form>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
