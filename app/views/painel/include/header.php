<!DOCTYPE html>
<html dir="ltr" lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $titulo; ?></title>

    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#55b5a6">
    <meta name="apple-mobile-web-app-status-bar-style" content="#55b5a6">
    <meta name="msapplication-navbutton-color" content="#55b5a6"

    <!-- Favicon -->
    <link rel=icon href=https://www.vistasoft.com.br/wp-content/uploads/2020/12/logo-vista.png sizes=32x32>
    <link rel=icon href=https://www.vistasoft.com.br/wp-content/uploads/2020/12/logo-vista.png sizes=192x192>
    <link rel=apple-touch-icon href=https://www.vistasoft.com.br/wp-content/uploads/2020/12/logo-vista.png>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

    <!-- Icons -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/painel/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet"
          href="<?= BASE_URL; ?>assets/theme/painel/vendor/@fortawesome/fontawesome-free/css/all.min.css"
          type="text/css">

    <!-- Argon CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/painel/css/argon.css?v=1.2.0" type="text/css">

    <!-- Autoload ===================================================== -->
    <?php $this->view("autoload/css"); ?>
</head>

<body>
<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="<?= BASE_URL; ?>assets/theme/painel/img/logo.png" class="navbar-brand-img" alt="Vista Software Logo" />
            </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">

                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= BASE_URL; ?>painel">
                            <i class="ni ni-tv-2 text-primary"></i>
                            <span class="nav-link-text">Painel</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL; ?>locadores">
                            <i class="ni ni-planet text-orange"></i>
                            <span class="nav-link-text">Proprietários</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL; ?>locatarios">
                            <i class="ni ni-pin-3 text-primary"></i>
                            <span class="nav-link-text">Clientes</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL; ?>contratos">
                            <i class="ni ni-single-02 text-yellow"></i>
                            <span class="nav-link-text">Contratos</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL; ?>usuarios">
                            <i class="ni ni-bullet-list-67 text-default"></i>
                            <span class="nav-link-text">Usuários</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL; ?>sair">
                            <i class="ni ni-key-25 text-info"></i>
                            <span class="nav-link-text">Sair</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="main-content" id="panel">

    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-gradient-primary">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center  ml-md-auto ">
                    <li class="nav-item d-xl-none">
                        <!-- Sidenav toggler -->
                        <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </div>
                    </li>
                </ul>


                <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Imagem padrão usuario" src="<?= BASE_URL; ?>assets/custom/img/user.png" />
                                </span>
                                <div class="media-body  ml-2  d-none d-lg-block">
                                    <span class="mb-0 text-sm  font-weight-bold" style="color: #fff;"><?= $usuario->nome; ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu  dropdown-menu-right ">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Bem-vindo!</h6>
                            </div>
                            <a href="<?= BASE_URL; ?>" target="_blank" class="dropdown-item">
                                <i class="ni ni-button-play"></i>
                                <span>Ver Site</span>
                            </a>
                            <a href="<?= BASE_URL; ?>usuario/alterar/<?= $usuario->id_usuario; ?>" class="dropdown-item">
                                <i class="ni ni-settings-gear-65"></i>
                                <span>Meus Dados</span>
                            </a>
                            <a href="https://www.vistasoft.com.br/contato" target="_blank" class="dropdown-item">
                                <i class="ni ni-support-16"></i>
                                <span>Suporte</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= BASE_URL; ?>sair" class="dropdown-item">
                                <i class="ni ni-user-run"></i>
                                <span>Sair</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Header -->