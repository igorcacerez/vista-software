<?php $this->view("painel/include/header"); ?>

<!-- Header -->
<div class="header bg-gradient-primary pb-6 pt-3"></div>

<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">

                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Todos os proprietários </h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="<?= BASE_URL; ?>locador/inserir" class="btn btn-sm btn-primary">Adicionar</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush dt-responsive getDatatableProprietarios" style="width: 100%;">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Nome</th>
                            <th scope="col" class="sort" data-sort="budget">E-mail</th>
                            <th scope="col" class="sort" data-sort="status">Dias para Repasse</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view("painel/include/footer"); ?>
</div>
</div>
<!-- Argon Scripts -->

</body>
</html>

