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
    } elseif ($etapa === 'atualizacao') {
        $id = (int) ($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $estoque = (int) ($_POST['estoque'] ?? 0);
        $preco = isset($_POST['preco']) && $_POST['preco'] !== '' ? (float) $_POST['preco'] : null;

        if (!$senhaOk) {
            $erro = 'Informe a senha de administrador para continuar.';
        } elseif ($id <= 0) {
            $erro = 'Selecione um produto.';
        } elseif ($nome === '') {
            $erro = 'Informe o nome do produto.';
        } elseif ($estoque < 0) {
            $erro = 'O estoque não pode ser negativo.';
        } elseif ($preco !== null && $preco < 0) {
            $erro = 'O preço não pode ser negativo.';
        } elseif (empty($produtos)) {
            $erro = 'Nenhum produto cadastrado para atualizar.';
        } else {
            atualizar_produto($conexao, $id, $nome, $estoque, $preco);
            set_flash('success', 'Produto atualizado com sucesso!');
            redirecionar('/projeto_integrador/app/produtos.php');
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
    <title>Atualizar Produto</title>
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
            <h3>Informe a senha de administrador para atualizar produtos.</h3>

            <form action="" method="POST">
                <input type="hidden" name="etapa" value="senha">

                <label for="senha_admin">Senha de administrador:</label>
                <input type="password" name="senha_admin" id="senha_admin" placeholder="Insira a senha." required>

                <button type="submit">Entrar</button>
                <a class="btn btn--ghost" href="/projeto_integrador/app/produtos.php">Voltar</a>
            </form>
        <?php elseif (empty($produtos)): ?>
            <h1>Atualizar Produto</h1>
            <p class="text-center">Nenhum produto cadastrado ainda.</p>
            <a class="btn btn--ghost" href="/projeto_integrador/app/create.php">Cadastrar produto</a>
        <?php else: ?>
            <h1>Atualizar Produto</h1>

            <form action="" method="POST">
                <input type="hidden" name="etapa" value="atualizacao">

                <label for="id">Produto:</label>
                <select id="id" name="id" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?= (int) $produto['id'] ?>">
                            <?= htmlspecialchars($produto['nome']) ?> (Estoque: <?= (int) $produto['estoque'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="nome">Novo nome:</label>
                <input type="text" id="nome" name="nome" placeholder="Nome do produto" required>

                <label for="estoque">Novo estoque:</label>
                <input type="number" id="estoque" name="estoque" placeholder="Quantidade em estoque" min="0" required>

                <label for="preco">Novo preço (R$):</label>
                <input type="number" id="preco" name="preco" placeholder="Preço do produto" min="0" step="0.01">

                <button type="submit">Atualizar</button>
                <a class="btn btn--ghost" href="/projeto_integrador/app/produtos.php">Voltar</a>
            </form>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
