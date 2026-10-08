<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../login/verifica_user.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/clubhawkings/app/produtos.php');
}

$id = (int) ($_POST['id'] ?? 0);
$quantidade = (int) ($_POST['quantidade'] ?? 1);

if ($id <= 0) {
    set_flash('error', 'Produto inválido.');
    redirecionar('/clubhawkings/app/produtos.php');
}

$resultado = comprar_produto($conexao, $id, $quantidade);

if (!$resultado['ok']) {
    set_flash('error', $resultado['erro']);
} else {
    $nome = $resultado['produto']['nome'] ?? 'Produto';
    set_flash('success', "Compra realizada! $quantidade x $nome.");
}

redirecionar('/clubhawkings/app/produtos.php');
