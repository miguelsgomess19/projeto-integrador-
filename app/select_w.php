<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verifica_user.php';

$erro = null;
$produto = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        $erro = 'Informe um ID válido.';
    } else {
        $produto = consultar_produto($conexao, $id);

        if (!$produto) {
            $erro = 'Produto não encontrado.';
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
    <title>Consultar Produto</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <h1>Consultar Produto</h1>

        <form action="" method="POST">
            <label for="id">ID:</label>
            <input type="number" name="id" id="id" min="1" required>
            <button type="submit">Consultar</button>
        </form>

        <?php if ($erro): ?>
            <p class="alert alert--error"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <?php if ($produto): ?>
            <div class="record-list">
                <strong>ID:</strong> <?= (int) $produto['id'] ?><br>
                <strong>Nome:</strong> <?= htmlspecialchars($produto['nome']) ?><br>
                <strong>Preço:</strong> R$ <?= number_format((float) $produto['preco'], 2, ',', '.') ?><br>
                <strong>Estoque:</strong> <?= (int) $produto['estoque'] ?><br>
                <strong>Imagem:</strong> <?= htmlspecialchars($produto['imagem']) ?>
            </div>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
