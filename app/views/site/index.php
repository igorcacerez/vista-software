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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

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
                        <li>
                            <a href="#" class="bold">
                                <i style="color: #f57b3d!important;" class="fa fa-file-text-o"></i> 2ª VIA BOLETO
                            </a>
                        </li>
                        <li>
                            <a href="https://web.whatsapp.com/send?phone=+5551981228895">
                                <i class="fab fa-whatsapp"></i>
                                <p>Locação <br> (51) 98122-8895</p>
                            </a>
                        </li>

                        <li>
                            <a href="https://web.whatsapp.com/send?phone=+5551981228895">
                                <i class="fab fa-whatsapp"></i>
                                <p>Financeiro <br> (51) 98278-2222</p>
                            </a>
                        </li>

                        <li>
                            <a href="https://web.whatsapp.com/send?phone=+5551981228895">
                                <i class="fab fa-whatsapp"></i>
                                <p>Vendas <br> (51) 98278-1111</p>
                            </a>
                        </li>
                    </nav>
                </div>

            </div>
        </div>
    </header>
    <!-- End >> Header -->

    <!-- Pesquisa -->
    <section class="pesquisa">
        <div class="container">

            <!-- Botões -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Comprar -->
                    <div class="btnEscolha selecionado" id="btnComprar" data-tipo="comprar">
                        <p>
                            <span></span>
                            Comprar
                        </p>
                    </div>

                    <!-- Aluguel -->
                    <div class="btnEscolha" id="btnAlugar" data-tipo="alugar">
                        <p>
                            <span></span>
                            Alugar
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <div class="row">
                <form id="formBusca">

                    <!-- Tipo de imovel -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <select class="selectBusca" multiple="multiple" name="tipo[]">
                                <option value="">Tipo de Imóvel</option>
                                <option value="Apartamento">Apartamento</option>
                                <option value="Area">Area</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bairros -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <select class="selectBusca" multiple="multiple" name="bairro[]">
                                <option value="">Bairros</option>
                                <option value="Aberta Dos Morros">Aberta Dos Morros</option>
                                <option value="Alphaville">Alphaville</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dormitórios -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <select class="selectBusca" name="dormitorios">
                                <option value="">Dormitórios</option>
                                <option value="1">01 +</option>
                                <option value="2">02 +</option>
                                <option value="3">03 +</option>
                                <option value="4">04 +</option>
                            </select>
                        </div>
                    </div>

                    <!-- Faixa de Preço -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <!-- Venda -->
                            <select class="selectBusca" name="valorVenda" id="valorVenda">
                                <option value="">Faixa de Valores</option>
                                <option value="0-100000">até R$ 100.000,00</option>
                                <option value="100000-200000">R$ 100.000,00 à R$ 200.000,00</option>
                                <option value="200000-300000">R$ 200.000,00 à R$ 300.000,00</option>
                                <option value="300000-400000">R$ 300.000,00 à R$ 400.000,00</option>
                                <option value="400000-500000">R$ 400.000,00 à R$ 500.000,00</option>
                                <option value="500000-999999999">acima de R$ 500.000,00</option>
                            </select>

                            <!-- Aluguel -->
                            <select class="selectBusca" name="valorAluguel" id="valorAluguel" style="display: none;">
                                <option value="">Faixa de Valores</option>
                                <option value="0-1000">até R$ 1.000,00</option>
                                <option value="1000-2000">R$ 1.000,00 à R$ 2.000,00</option>
                                <option value="2000-3000">R$ 2.000,00 à R$ 3.000,00</option>
                                <option value="3000-4000">R$ 3.000,00 à R$ 4.000,00</option>
                                <option value="4000-5000">R$ 4.000,00 à R$ 5.000,00</option>
                                <option value="5000-7500">R$ 5.000,00 à R$ 7.500,00</option>
                                <option value="7500-10000">R$ 7.500,00 à R$ 10.000,00</option>
                                <option value="10000-999999">acima de R$ 10.000,00</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tipo de imovel -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <input type="text" name="codigo" placeholder="Código" />
                        </div>
                    </div>

                    <!-- Botão -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <button type="submit" class="btn-envia">
                                BUSCAR
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </section>
    <!-- End >> Pesquisa -->

    <!-- Exibe os imóveis -->
    <section class="listaImoveis">
        <div class="container">
            <div class="row">

                <!-- Card do imovel -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card">

                        <!-- Imagem do imovel -->
                        <div class="imagem-imovel" style="background-image: url('<?= BASE_URL; ?>assets/theme/site/img/imagem-exemplo.jpg')">
                            <span class="valor">R$ 1.850.230,00</span>
                        </div>

                        <div class="card-body">

                            <!-- Informações -->
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- titulo -->
                                    <h5 class="titulo">
                                        <span>Bairro</span>
                                        Tipo do Imóvel
                                    </h5>

                                    <p class="endereco">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Porto Alegre, Bairro - RS
                                    </p>
                                </div>
                            </div>

                            <!-- Caracteristicas -->
                            <div class="row imovel-caracteristicas text-center">

                                <div class="col-lg-2 col-md-4">
                                    <div class="item">
                                        <i class="fa fa-bed"></i>
                                        <p class="valor">2</p>
                                        <p class="descricao">Dormitórios</p>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary">Saiba Mais</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End >> Exibe os imóveis -->
    
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="col-md-3">
                <a href="<?= BASE_URL; ?>">
                    <img src="<?= BASE_URL; ?>assets/theme/site/img/logo.png " class="logo" alt="<?= SITE_NOME; ?> logo" />
                </a>
            </div>

            <div class="col-md-3">
                <a class="icone" href="https://web.whatsapp.com/send?phone=+5551982782222" target="_blank">
                    <!-- <img src="<?= BASE_URL; ?>assets/theme/site/img/whatsapp.png" alt="Icone WhatsApp" /> -->
                    <span class="whats">
                        <i class="fab fa-whatsapp"></i>
                    </span>
                    <p>Financeiro <br> (51) 98278-2222</p>
                </a>

                <a class="icone" href="tel:+555132692901" target="_blank">
                    <!-- <img src="<?= BASE_URL; ?>assets/theme/site/img/telefone.png" alt="Icone Telefone" /> -->
                    <span class="telefone">
                        <i class="fas fa-phone-alt"></i>
                    </span>

                    <p>Ligue! <br> (51) 3269-2901</p>
                </a>
            </div>

            <div class="col-md-3">
                <a href="https://www.facebook.com/passowimoveis" target="_blank" class="social fb">
                    <i class="fab fa-facebook"></i>
                </a>

                <a href="https://www.youtube.com/channel/UCC5GrrRXp71E9X5FvIAy4VQ" target="_blank" class="social youtube">
                    <i class="fab fa-youtube"></i>
                </a>

                <a href="https://www.instagram.com/passowimoveis" target="_blank" class="social insta">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </footer>


    <!-- Jquery -->
    <script src="<?= BASE_URL; ?>assets/theme/painel/vendor/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    <!-- Autoload JS ================================================== -->
    <?php $this->view("autoload/js"); ?>
</body>
</html>