
<?php
session_start();

require_once '../Dao/conexao.php';

        $editar = mysqli_query($conn, 
        "UPDATE user SET activity = 1 WHERE email = '".$_SESSION['email']."' ");

        header("Location:../../View/TelaLogin/index.php");

    ?>