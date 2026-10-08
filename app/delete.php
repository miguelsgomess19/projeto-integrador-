<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verifica_user.php';

$erro = null;
$senhaOk = !empty($_SESSION['admin_liberado']);
$produtos = listar_produtos($conexao);

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
    } elseif ($etapa === 'exclusao') {
        $id = (int) ($_POST['id'] ?? 0);

        if (!$senhaOk) {
            $erro = 'Informe a senha de administrador para continuar.';
        } elseif ($id <= 0) {
            $erro = 'Selecione um produto.';
        } else {
            $resultado = excluir_produto($conexao, $id);

            if (!$resultado['ok']) {
                $erro = $resultado['erro'];
            } else {
                $nome = $resultado['produto']['nome'] ?? 'Produto';
                set_flash('success', "Produto \"$nome\" excluído com sucesso!");
                redirecionar('/projeto_integrador/app/delete.php');
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
    <link rel="stylesheet" href="/projeto_integrador/style/style.css">
    <title>Excluir Produto</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <p class="alert alert--<?= htmlspecialchars($flash['tipo']) ?>"><?= htmlspecialchars($flash['mensagem']) ?></p>
        <?php endif; ?>
        <?php if ($erro): ?>
            <p class="alert alert--error"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <?php if (!$senhaOk): ?>
            <h1>Acesso restrito</h1>
            <h3>Informe a senha de administrador para excluir produtos.</h3>

            <form action="" method="POST">
                <input type="hidden" name="etapa" value="senha">

                <label for="senha_admin">Senha de administrador:</label>
                <input type="password" name="senha_admin" id="senha_admin" placeholder="Insira a senha." required>

                <button type="submit">Entrar</button>
                <a class="btn btn--ghost" href="/projeto_integrador/app/produtos.php">Voltar</a>
            </form>
        <?php elseif (empty($produtos)): ?>
            <h1>Excluir Produto</h1>
            <p class="text-center">Nenhum produto cadastrado ainda.</p>
            <a class="btn btn--ghost" href="/projeto_integrador/app/create.php">Cadastrar produto</a>
        <?php else: ?>
            <h1>Excluir Produto</h1>

            <form action="" method="POST">
                <input type="hidden" name="etapa" value="exclusao">

                <label for="id">Produto:</label>
                <select id="id" name="id" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?= (int) $produto['id'] ?>">
                            <?= htmlspecialchars($produto['nome']) ?> (Estoque: <?= (int) $produto['estoque'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Excluir</button>
                <a class="btn btn--ghost" href="/projeto_integrador/app/produtos.php">Voltar</a>
            </form>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
