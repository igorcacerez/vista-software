<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12 logo">
                <a href="<?= BASE_URL; ?>">
                    <img src="<?= BASE_URL; ?>assets/theme/site/img/logo.png " alt="<?= SITE_NOME; ?> logo" />
                    <p>
                        AV. Wenceslau Escobar, 2037 <br>
                        Tristeza - Porto Alegre - RS
                    </p>
                </a>
            </div>

            <div class="col-lg-9 col-md-12">
                <div class="row text-center">
                    <div class="col-md-4 col-sm-6">
                        <a class="icone" href="https://web.whatsapp.com/send?phone=+5551982782222" target="_blank">
                             <img src="<?= BASE_URL; ?>assets/theme/site/img/whatsapp.png" alt="Icone WhatsApp" />
                            <p>Locação <br> (51) 98122-8895</p>
                        </a>

                        <a class="icone" href="https://web.whatsapp.com/send?phone=+555182781111" target="_blank">
                             <img src="<?= BASE_URL; ?>assets/theme/site/img/whatsapp.png" alt="Icone Telefone" />
                            <p>Vendas <br> (51) 98278-1111</p>
                        </a>
                    </div>

                    <div class="col-md-4  col-sm-6">
                        <a class="icone" href="https://web.whatsapp.com/send?phone=+5551982782222" target="_blank">
                            <img src="<?= BASE_URL; ?>assets/theme/site/img/whatsapp.png" alt="Icone WhatsApp" />
                            <p>Financeiro <br> (51) 98278-2222</p>
                        </a>

                        <a class="icone" href="tel:+555132692901" target="_blank">
                            <img src="<?= BASE_URL; ?>assets/theme/site/img/telefone.png" alt="Icone Telefone" />
                            <p>Ligue! <br> (51) 3269-2901</p>
                        </a>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="iconeSocial">
                            <a href="https://www.facebook.com/passowimoveis" target="_blank">
                                <img src="<?= BASE_URL; ?>assets/theme/site/img/facebook.png" alt="Icone Facebook" />
                            </a>

                            <a href="https://www.youtube.com/channel/UCC5GrrRXp71E9X5FvIAy4VQ" target="_blank">
                                <img src="<?= BASE_URL; ?>assets/theme/site/img/youtube.png" alt="Icone YouTube" />
                            </a>

                            <a href="https://www.instagram.com/passowimoveis" target="_blank">
                                <img src="<?= BASE_URL; ?>assets/theme/site/img/instagram.png" alt="Icone Instagram" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sub">
        <div class="container">
            <div class="row">
                <p>© <?= date("Y") ?> <a href="https://www.vistasoft.com.br/" target="_blank">Vista Software</a> - Todos os direitos reservados</p>
            </div>
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