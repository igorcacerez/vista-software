<!-- Footer -->
<footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6">
            <div class="copyright text-center  text-lg-left  text-muted">
                &copy; <?= date("Y"); ?>
                <a href="https://www.vistasoft.com.br" class="font-weight-bold ml-1" target="_blank">Vista Software</a>
                - Todos os direitos reservados
            </div>
        </div>
    </div>
</footer>

<!-- Core -->
<script src="<?= BASE_URL; ?>assets/theme/painel/vendor/jquery/dist/jquery.min.js"></script>
<script src="<?= BASE_URL; ?>assets/theme/painel/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL; ?>assets/theme/painel/vendor/js-cookie/js.cookie.js"></script>
<script src="<?= BASE_URL; ?>assets/theme/painel/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?= BASE_URL; ?>assets/theme/painel/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>

<!-- Autoload JS ================================================== -->
<?php $this->view("autoload/js"); ?>

<!-- Argon JS -->
<script src="<?= BASE_URL; ?>assets/theme/painel/js/argon.js?v=1.2.0"></script>