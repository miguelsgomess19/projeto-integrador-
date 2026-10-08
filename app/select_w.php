<?php require_once __DIR__ . '/../includes/functions.php'?>
<?php require_once __DIR__ . '/../login/verifica_user.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/projeto_integrador/style/style.css">
    <title>Document</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php' ?>
        <h1>Pagina para consultar</h1>
    <form action="" method="post">
        <label for="id">ID: </label>
        <input type="number" name="id" id="id">
        <input type="submit" value="Consultar">
    </form>
    <?php 
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        consultar_bizarro($conexao,$_POST['id']);
    }
    ?>
</body>
</html>
<?php 


?>