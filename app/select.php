<?php require_once __DIR__ . '/../login/verifica_user.php';?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/projeto_integrador/style/style.css">
    <title>Relatório</title>
</head>

<body>
    <?php include __DIR__ . '/../includes/header.php'?>
    <main>
        <div style="width: 50%; margin:auto; text-align:center; border:lpx solid black; border-radius:5px;">
    <?php 
require_once "../database/connect.php";

$sql = "SELECT * FROM alunos";

$stmt = $conexao->prepare($sql);
$stmt->execute();

$bizarros = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($bizarros as $bizarro){
    echo "ID: {$bizarro['id']}<br>";
    echo "Nome: {$bizarro['nome']}<br>";
    echo "Nascimento: {$bizarro['nasc']}<br>";
    echo "Turma: {$bizarro['turma']}<br>";
    echo "Ativo: {$bizarro['ativo']}<br>";
    echo "<hr>";
}
?>
<?php include __DIR__ . '/../includes/footer.php'?>
</body>
</html>