<!DOCTYPE html>
<html dir="ltr" lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $seo["title"]; ?></title>

    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#55b5a6">
    <meta name="apple-mobile-web-app-status-bar-style" content="#55b5a6">
    <meta name="msapplication-navbutton-color" content="#55b5a6"

    <!-- SEO -->
    <meta name="description" content="<?= $seo["description"]; ?>">
    <meta name="author" content="Vista Software">
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="all, index, follow">
    <meta name="revisit" content="2 days">
    <meta name="Revisit-After" content="2 Days">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    <meta name="format-detection" content="telephone=no">

    <!-- SMO -->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:url" content="<?= $smo["url"]; ?>">
    <meta property="og:title" content="<?= $smo["title"]; ?>">
    <meta property="og:site_name" content="<?= $smo["description"]; ?>">
    <meta property="og:description" content="<?= $smo["description"]; ?>">
    <meta property="og:image" content="<?= $smo["image"]; ?>">
    <meta property="og:image:type" content="<?= $smo["image_type"]; ?>">
    <meta property="og:image:width" content="<?= $smo["image_width"]; ?>">
    <meta property="og:image:height" content="<?= $smo["image_height"]; ?>">

    <!-- Favicon -->
    <link rel=icon href=https://www.vistasoft.com.br/wp-content/uploads/2020/12/logo-vista.png sizes=32x32>
    <link rel=icon href=https://www.vistasoft.com.br/wp-content/uploads/2020/12/logo-vista.png sizes=192x192>
    <link rel=apple-touch-icon href=https://www.vistasoft.com.br/wp-content/uploads/2020/12/logo-vista.png>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

    <!-- Icons -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/painel/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/painel/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">

    <!-- Argon CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/painel/css/argon.css?v=1.2.0" type="text/css">

    <!-- Autoload ===================================================== -->
    <?php $this->view("autoload/css"); ?>
</head>

<style>
    .bg-default{background-color: #d5dce2 !important;}
</style>

<body class="bg-default">

<!-- Main content -->
<div class="main-content">

    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9"></div>

    <!-- Page content -->
    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-light border-0 mb-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <img src="<?= BASE_URL; ?>assets/theme/painel/img/logo.png" alt="Vista Software Logo" style="max-width: 60%; padding: 20px 0px;" />
                        </div>
                        <form id="formLogin">
                            <div class="form-group mb-3">
                                <?php if(!empty($email)): ?>
                                    <p class="email-logout"><?= $email; ?></p>
                                    <input value="<?= $email; ?>" name="email" type="hidden" />
                                <?php else: ?>
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>

                                        <input class="form-control" placeholder="E-mail" name="email" type="email" />
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>

                                    <input class="form-control" placeholder="Senha" type="password" name="senha" />
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-black my-4">Acessar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <footer class="py-5" id="footer-main">
        <div class="container">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; <?= date("Y"); ?> -
                        <a href="https://www.vistasoft.com.br/" class="font-weight-bold ml-1" target="_blank">Vista Soft</a>
                    </div>
                </div>
                <div class="col-xl-6 text-center text-xl-right text-muted">
                    <p>Todos os direitos reservados</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Argon Scripts -->

    <!-- Core -->
    <script src="<?= BASE_URL; ?>assets/theme/painel/vendor/jquery/dist/jquery.min.js"></script>
    <script src="<?= BASE_URL; ?>assets/theme/painel/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL; ?>assets/theme/painel/vendor/js-cookie/js.cookie.js"></script>
    <script src="<?= BASE_URL; ?>assets/theme/painel/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="<?= BASE_URL; ?>assets/theme/painel/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>

    <!-- Argon JS -->
    <script src="<?= BASE_URL; ?>assets/theme/painel/js/argon.js?v=1.2.0"></script>

    <!-- Autoload JS ================================================== -->
    <?php $this->view("autoload/js"); ?>
</body>
</html>