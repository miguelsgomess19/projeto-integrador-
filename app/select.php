<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verifica_user.php';

$stmt = $conexao->query("SELECT id, nome, nasc, turma, ativo FROM alunos ORDER BY nome");
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/projeto_integrador/style/style.css">
    <title>Relatório de Alunos</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <h1>Relatório de Alunos</h1>

        <?php if (empty($alunos)): ?>
            <p class="text-center">Nenhum aluno cadastrado ainda.</p>
        <?php else: ?>
            <div class="record-list">
                <?php foreach ($alunos as $aluno): ?>
                    <strong>ID:</strong> <?= (int) $aluno['id'] ?><br>
                    <strong>Nome:</strong> <?= htmlspecialchars($aluno['nome']) ?><br>
                    <strong>Nascimento:</strong> <?= htmlspecialchars($aluno['nasc'] ?? '-') ?><br>
                    <strong>Turma:</strong> <?= htmlspecialchars($aluno['turma']) ?><br>
                    <strong>Ativo:</strong> <?= !empty($aluno['ativo']) ? 'Sim' : 'Não' ?>
                    <hr>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
