<?php
    require "../../Controller/verifica.php";
    require '../../Dao/conexao.php';

    //select do usuario
    $consulta = "SELECT * FROM user WHERE email = '".$_SESSION['email']."'";
    $res = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_assoc($res);
    $email = $row['email'];
    $nome = $row['full_name'];

    //select usuarios cadastrados
    $consulta = "SELECT COUNT(id) as 'qtd' FROM user";
    $res = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_assoc($res);
    $user = $row['qtd'];

    //select servicos
    $consulta = "SELECT COUNT(id) as 'qtd' FROM service_request";
    $res = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_assoc($res);
    $servico = $row['qtd'];


    //select reclamações
    $consulta = "SELECT COUNT(id) as 'qtd' FROM claims";
    $res = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_assoc($res);
    $reclamacoes = $row['qtd'];

    $consulta = mysqli_query($conn,"SELECT * FROM claims WHERE finalized = 0 ORDER BY  dates ASC");
    $claims = $consulta;


    //seleciona notas
    /*
    $consulta = "SELECT AVG(grade) as 'nota' FROM grade";
    $res = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_assoc($res);
    $nota = $row['nota'];
    if($nota == null){
        $nota = 0;
    }*/

    //gráfico pizza
    $categoria = [];
    $qtd = [];
    $consulta = "SELECT id_occupation_subcategory as 'COD', COUNT(id_occupation_subcategory) as 'QTD' from service group by id_occupation_subcategory having count(id_occupation_subcategory) > 0 ORDER BY COUNT(id_occupation_subcategory) DESC LIMIT 4";
    $res = mysqli_query($conn,$consulta);
    while ($row = mysqli_fetch_assoc($res)) {
        $consulta1 = "SELECT name as 'nome' FROM occupation_subcategory where id = ".$row['COD'];
        $res1 = mysqli_query($conn,$consulta1);
        while ($row1 = mysqli_fetch_assoc($res1)) {      
            $categoria[] = '"'.$row1['nome'].'"';
        }
        $qtd[] = $row['QTD'];
    }

    $consulta = "SELECT count(id_occupation_subcategory) as 'nota' FROM service";
    $res = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_assoc($res);
    $outros = $row['nota'] -($qtd[0]+$qtd[1]+$qtd[2]);

    //gráfico barra
    $categoriaBarra = [];
    $qtdBarra = [];
    $consulta = "SELECT id_occupation_subcategory as 'COD', COUNT(id_occupation_subcategory) as 'QTD' from service group by id_occupation_subcategory having count(id_occupation_subcategory) > 0 ORDER BY COUNT(id_occupation_subcategory) DESC LIMIT 4";
    $res = mysqli_query($conn,$consulta);
    while ($row = mysqli_fetch_assoc($res)) {
        $consulta1 = "SELECT occupation_subcategory.id_occupation as 'Cod', occupation.name as 'Categoria' FROM occupation_subcategory INNER JOIN occupation ON occupation_subcategory.id_occupation = occupation.id WHERE occupation_subcategory.id =".$row['COD'];
        $res1 = mysqli_query($conn,$consulta1);
        while ($row1 = mysqli_fetch_assoc($res1)) {      
            $categoriaBarra[] = '"'.$row1['Categoria'].'"';
        }
        $qtdBarra[] = $row['QTD'];
    }

    $consulta = "SELECT count(id_occupation_subcategory) as 'nota' FROM service";
    $res = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_assoc($res);
    $outrosBarra = $row['nota'] -($qtdBarra[0]+$qtdBarra[1]+$qtdBarra[2]); 

    //confere gráfico
    switch(count($categoriaBarra)){
        case 0:
            $categoriaBarra[0] = '""';
            $qtdBarra[0] = 0;
            $categoriaBarra[1] = '""';
            $qtdBarra[1] = 0;
            $categoriaBarra[2] = '""';
            $qtdBarra[2] = 0;
        break;
        case 1:
            $categoriaBarra[1] = '""';
            $qtdBarra[1] = 0;
            $categoriaBarra[2] = '""';
            $qtdBarra[2] = 0;
        break;
        case 2:
            $categoriaBarra[2] = '""';
            $qtdBarra[2] = 0;
        break;
    }

    switch(count($categoria)){
        case 0:
            $categoria[0] = '""';
            $qtd[0] = 0;
            $categoria[1] = '""';
            $qtd[1] = 0;
            $categoria[2] = '""';
            $qtd[2] = 0;
        break;
        case 1:
            $categoria[1] = '""';
            $qtd[1] = 0;
            $categoria[2] = '""';
            $qtd[2] = 0;
        break;
        case 2:
            $categoria[2] = '""';
            $qtd[2] = 0;
        break;
    }
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="_sass/materialize.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>trampo</title>

    <style>
        .card-content {
            padding: 5px;
            margin-bottom: 10px;
        }

        .container-adm {
            margin-right: 20px;
        }

        @media(min-width:991px) {
            .container-adm {

                float: right;
            }
        }

        @media(max-width:1050px) {
            .container-adm {

                margin-right: auto;
            }
        }

    </style>

</head>

<body>

    <header>
        <nav class="nav-extended" style="background:#1ac3b2;">
            <div class="nav-wrapper">
                <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <a href="#!" class="brand-logo center">Administrador</a>
            </div>
        </nav>
    </header>
    <!-- MENU -->
    <main style="padding-top: 4em;">
        <ul id="slide-out" class="sidenav sidenav-fixed darken-1" style="background:#1ac3b2;">
            <h5 class="center-align white-text ">TRAMPO</h5>
            <li>
                <div class="user-view">
                    <a href="#user"><img class="circle z-depth-1" src="_img/user.png" alt="user profile picture"></a>
                    <div class="user-info">
                        <a href="#name"><span class="white-text name"><?=$nome?></span></a>
                        <a href="#email"><span class="white-text email"><?=$email?></span></a>
                    </div>
                </div>
            </li>
          
            <li><a href="reclamacoes.php" class="waves-effect white-text"><i class="material-icons white-text">new_releases</i>Reclamações</a>
            </li>
            <li>
                <div class="divider"></div>
            </li>
            <li><a href="#modalLeave" class="waves-effect modal-trigger white-text"><i class="material-icons white-text">power_settings_new</i>Sair</a></li>
        </ul>
    </main>
    <div id="modalLeave" class="modal">
        <div class="modal-content">
            <h4 class="center-align">Deseja sair?</h4>
        </div>
        <div class="modal-footer">
            <a href="" class="modal-close waves-effect btn-flat">Sim</a>
            <button style="background:#1ac3b2;" class="modal-close waves-effect waves-light btn">Não</button>
        </div>
    </div>

    <!-- Visao Geral -->
    <div class="row">
        <div class="container container-adm">
            <div class="row ">
                <div class="col m6 right">
                    <select>
                        <option value="">Hoje</option>
                        <option value="">Últimos 30 dias</option>
                    </select>
                </div>
                <h4 style="color:#1ac3b2;">Visão Geral</h4>

                <div class="col s12 m6 l3 center-align">
                    <div class="card-content z-depth-1">
                   
                        <h1 style="color:#1ac3b2;"><i class="fa fa-users"></i></h1>
                        <h3><?=$user?></h3>
                        <p>
                            Usuários cadastrados
                        </p>
                    </div>
                </div>
                <div class="col s12 m6 l3  center-align">
                    <div class="card-content z-depth-1">
                    
                        <h1 style="color:#1ac3b2;"><i class="fa fa-desktop"></i></h1>
                        <h3><?=$servico?></h3>
                        <p>
                            Serviços Realizados
                        </p>
                    </div>
                </div>
                <div class="col s12 m6 l3  center-align">
                    <div class="card-content z-depth-1">
                        
                        <h1 style="color:#1ac3b2;"><i class="fa fa-star"></i></h1>
                        <h3><?="#"?></h3>
                        <p>
                            Média de Qualidade
                        </p>
                    </div>
                </div>
                <div class="col s12 m6 l3 center-align">
                    <div class="card-content z-depth-1">
                        
                        <h1 style="color:#1ac3b2;"><i class="fa fa-exclamation-triangle"></i></h1>
                        <h3><?=$reclamacoes?></h3>
                        <p>
                            Reclamações
                        </p>
                    </div>
                </div>
            </div>
            <!--Serviços-->
            <div class="row">
                <div class="col m6 right">
                    <select>
                        <option value="">Hoje</option>
                        <option value="">Últimos 30 dias</option>
                    </select>
                </div>
                <h4 style="color:#1ac3b2;">Serviços</h4>
                <div class="col s12 m6  center-align">
                    <div class="card-content z-depth-1">
                        
                        <h5 style="color:#1ac3b2;">Categorias mais frequentes</h5>
                        <canvas id="graph" width="300" height="300"></canvas>

                    </div>
                </div>
                <div class="col m6 s12 center-align">
                    <div class="card-content z-depth-1">
                       
                        <h5 style="color:#1ac3b2;">Número de serviços por categoria</h5>
                        <canvas id="graph1" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
            <!-- Reclamacoes -->
            <div class="row">
                <div class="col m6 s5 right">
                    <select>
                        <option value="">Hoje</option>
                        <option value="">Últimos 30 dias</option>
                    </select>
                </div>
                <h4 style="color:#1ac3b2;">Reclamações</h4>
                <?php while($row = mysqli_fetch_assoc($claims)){ ?>
            <?php 
                    $consulta = "SELECT * FROM user WHERE id = '".$row['id_user']."'";
                    $res = mysqli_query($conn,$consulta);
                    $rows = mysqli_fetch_assoc($res);

                    $consulta = "SELECT * FROM user WHERE id = '".$row['id_denuncied']."'";
                    $res = mysqli_query($conn,$consulta);
                    $denuncied = mysqli_fetch_assoc($res);
                    ?>
                <form action = "../../Controller/desativaUserADM.php/?id=<?php echo $denuncied['id']?>" method="Post">
        
                <div class="col m12">
                <br>
                    

                    <div class="card horizontal" style="padding: 5px;">
                        <a href="#" class="circle">
                            <div class="circle" style="width:50px; height: 50px;background: #000;margin:2px;">
                            </div>
                        </a>
                        <div class="container-fluid left-align" style="padding-top:3px;">
                            <span><?php echo $rows['full_name']?>| Usuario</span>
                            <br>
                            <span style="color:#aaa;">Data da Denúncia: <?php echo $row['dates'] ?></span>

                            <div class="container-fluid left-align">
                                <h6><?php echo $row['title'] ?></h6>
                                <span style="color:#aaa;">Reclamação Contra: <?php echo $denuncied['full_name']?> | Código do Serviço Prestado: <?php echo $row['id_service'] ?></span>
                                <div style="word-wrap: break-word;"><?php echo $row['complaint'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="container right-align">
                             <button style="background:#1ac3b2;" action = "../../Controller/desativaUserADM.php/?id=<?php echo $denuncied['id']?>" method="Post" class="modal-close waves-effect waves-light btn">Desativar Conta</button>
                        </div>
                    </div>
                </div>
               
                <?php } ?>
                </form>

                    
                        
                        
                
            </div>
            <!-- Novo serviço ou nova categoria -->
            <div class="row">

                <h4 style="color:#1ac3b2;">Cadastrar Categorias ou Serviços</h4>
                <a href="#modal_service" class="modal-trigger">
                    <div class="col s6 center-align">

                        <div class="card-content z-depth-1 col s12 waves-effect">
                            <h1>
                                <img src="_img/customer-support.png" width="120">
                            </h1>
                            <h4 style="color:#1ac3b2;">Cadastrar Categoria</h4>

                        </div>
                    </div>
                </a>
                <a href="#modal_category" class="modal-trigger">
                    <div class="col s6 center-align">
                        <div class="card-content z-depth-1 col s12 waves-effect">
                            <h1>
                                <img src="_img/list.png" width="120">
                            </h1>
                            <h4 style="color:#1ac3b2;">#</h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
    <!-- Modal categoria -->

    <div id="modal_category" class="modal">
        <div class="modal-content">
            <h4 class="center-align" style="color:#1ac3b2;">Cadastrar Categoria</h4>
            <form action="" method="POST">
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input type="text" id="name" name="name">
                        <label for="name">Nome Categoria</label>
                    </div>


                    <div class="input-field col s12 m6">
                        <input type="text" id="cod" name="cod">
                        <label for="cod">Codigo Serviço</label>
                    </div>
                </div>
            </form>

        </div>
        <div class="modal-footer">
            <a href="" class="modal-close waves-effect btn-flat">Cadastrar</a>
            <button style="color:#1ac3b2;" class="modal-close waves-effect waves-light btn">Cancelar</button>
        </div>
    </div>
    <!-- Modal Serviço -->
    <div id="modal_service" class="modal">
        <div class="modal-content">
            <h4 class="center-align" style="color:#1ac3b2;">Cadastrar Categoria</h4>
            <form action="../../Controller/occupation.php" method="POST">
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="title" name="title">
                        <label for="title">Nome Categoria</label>
                    </div>
                </div>
            
                </div>
                <div class="modal-footer">
                   <button style="background:#1ac3b2;" class="modal-close waves-effect waves-light btn" type="submit" action="../../Controller/occupation.php" method="POST">Cadastrar</button>
                    </form>
                    <button style="background:#1ac3b2;" class="modal-close waves-effect waves-light btn">Cancelar</button>
                </div>
            
    </div>
    <!---->
    <script src="_js/jquery/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="_js/bin/materialize.min.js"></script>
    <script type="text/javascript" src="_js/bin/main.js"></script>
    <script src="_js/Chart.min.js"></script>

    <script>
        var ctx = document.getElementById("graph").getContext('2d');

        var mychart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [<?=$categoria[0]?>, <?=$categoria[1]?>, <?=$categoria[2]?>, "Outros"],
                datasets: [{
                    label: 'dados',
                    data: [<?=$qtd[0]?>, <?=$qtd[1]?>, <?=$qtd[2]?>, <?=$outros?>],
                    backgroundColor: [
                        '#16968a',
                        '#3297b4',
                        '#1ac3b2',
                        '#3c7dff'
                    ],
                    borderColor: [
                        'rgba:(34,34,34,.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
        var ctx1 = document.getElementById("graph1").getContext('2d');

        var mychart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: [<?=$categoriaBarra[0]?>, <?=$categoriaBarra[1]?>, <?=$categoriaBarra[2]?>, "outros"],
                datasets: [{
                    label: 'Número de Servços',
                    data: [<?=$qtdBarra[0]?>, <?=$qtdBarra[1]?>, <?=$qtdBarra[2]?>, <?=$outrosBarra?>, 0],
                    backgroundColor: '#1ac3b2',
                    borderColor: [
                        'rgba:(34,34,34,.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });

    </script>
</body>

</html>
