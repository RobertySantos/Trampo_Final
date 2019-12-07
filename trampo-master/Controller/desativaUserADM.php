<?php
session_start();

require_once '../Dao/conexao.php';

        $editar = mysqli_query($conn, 
        "UPDATE user SET activity = 1 WHERE id = '".$_GET['id']."' ");
        $editar = mysqli_query($conn, 
        "UPDATE claims SET finalized = 1 WHERE id_denuncied = '".$_GET['id']."' ");
        header("Location:../../View/admin/tela_adm.php");

    ?>