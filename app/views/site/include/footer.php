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