<!doctype html>
<html lang="pt-br">
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
    <link rel="icon" href="https://passowimoveis.com.br/wp-content/uploads/2020/06/cropped-favicon-32x32.jpg" sizes="32x32" />
    <link rel="icon" href="https://passowimoveis.com.br/wp-content/uploads/2020/06/cropped-favicon-192x192.jpg" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://passowimoveis.com.br/wp-content/uploads/2020/06/cropped-favicon-180x180.jpg" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/painel/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">

    <!-- Css padrão -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/site/css/estilo.css" type="text/css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/theme/site/css/responsivo.css" type="text/css">

    <!-- Autoload ===================================================== -->
    <?php $this->view("autoload/css"); ?>
</head>
<body>
<!-- Header -->
<header>
    <div class="container">
        <div class="row">

            <!-- Logo -->
            <div class="col-md-3">
                <a href="<?= BASE_URL; ?>">
                    <img src="<?= BASE_URL; ?>assets/theme/site/img/logo.png " class="logo" alt="<?= SITE_NOME; ?> logo" />
                </a>
            </div>

            <!-- Demais informações -->
            <div class="col-md-9">
                <!-- Abre menu responsivo -->
                <button class="btnMenu">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Menu -->
                <nav>
                    <ul class="menuTop">
                        <li>
                            <a href="#" class="bold">
                                <i style="color: #f57b3d!important;" class="fa fa-file-text-o"></i> 2ª VIA BOLETO
                            </a>
                        </li>

                        <li class="icone">
                            <a href="https://web.whatsapp.com/send?phone=+5551981228895">
                                <i class="fab fa-whatsapp"></i>
                                <p>Locação <br> (51) 98122-8895</p>
                            </a>
                        </li>

                        <li class="icone">
                            <a href="https://web.whatsapp.com/send?phone=+5551981228895">
                                <i class="fab fa-whatsapp"></i>
                                <p>Financeiro <br> (51) 98278-2222</p>
                            </a>
                        </li>

                        <li class="icone">
                            <a href="https://web.whatsapp.com/send?phone=+5551981228895">
                                <i class="fab fa-whatsapp"></i>
                                <p>Vendas <br> (51) 98278-1111</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</header>
<!-- End >> Header -->