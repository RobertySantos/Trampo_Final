<?php  
date_default_timezone_set('America/Sao_Paulo');
session_start();

require_once '../Dao/conexao.php';


$consulta = "SELECT * FROM user WHERE email = '".$_SESSION['email']."'";
$res = mysqli_query($conn,$consulta);
$row = mysqli_fetch_assoc($res);
$id_user = $row['id'];
$id_service = $_GET['id_service'];
$id_denuncied = $_GET['id_user'];

$data = date('Y-m-d H:i:s');

$verifica = mysqli_query($conn, "SELECT * FROM claims WHERE id_service = '" .$id_service. "' and id_user = $id_user ");
$row = mysqli_fetch_assoc($verifica);
if($row>0){
    echo "<p class='red-text'> denuncia já existente  </p>";
}else{
    $insere = "INSERT INTO claims (complaint, title, id_service, id_user, dates,id_denuncied) VALUES ('".$_POST['text']."', '".$_POST['title']."', '" .$id_service. "', $id_user , '".$data.", $id_denuncied)";
    $row = mysqli_query($conn,$insere);
}

header("Location:../View/Main/Progress");
echo "<p class='red-text'> denuncia enviada  </p>";
?>