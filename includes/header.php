<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION['id']);
?>
<header>
    <nav>
        <div>
            <a href="/projeto_integrador/index.php">Inicio</a>
            <a href="/projeto_integrador/app/create.php">Cadastrar</a>
            <a href="/projeto_integrador/app/produtos.php">Produtos</a>
            <a href="/projeto_integrador/app/delete.php">Excluir</a>
            <a href="/projeto_integrador/app/update.php">Atualizar</a>
        </div>
        <?php if ($logado): ?>
            <a class="botao-sair" href="/projeto_integrador/login/logout.php">Sair</a>
        <?php else: ?>
            <a class="botao-sair" href="/projeto_integrador/login/login.php">Entrar</a>
        <?php endif; ?>
    </nav>
</header>
