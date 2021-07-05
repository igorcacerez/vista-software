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
                                    <h3 class="mb-0">Informações do Contrato</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="<?= BASE_URL; ?>contratos" class="btn btn-sm btn-primary">Ver Todos</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Contrato</label>
                                        <p>#<?= $contrato->id_contrato; ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Imóvel</label>
                                        <p>#<?= $contrato->imovel; ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Proprietário</label>
                                        <p><?= $contrato->locador->nome; ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Locatário</label>
                                        <p><?= $contrato->locatario->nome; ?></p>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Taxa Admin.</label>
                                        <p><?= $contrato->taxaAdministracao; ?>%</p>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Aluguel</label>
                                        <p>R$<?= number_format($contrato->valorAluguel,2,",", "."); ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Condomínio</label>
                                        <p>R$<?= number_format($contrato->valorCondominio,2,",", "."); ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">IPTU</label>
                                        <p>R$<?= number_format($contrato->valorIptu,2,",", "."); ?></p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Endereço</label>
                                        <?php if(!empty($contrato->endereco)): ?>
                                            <p>
                                                <?= $contrato->endereco; ?>
                                                <?= (!empty($contrato->numero) ? ", Nº " . $contrato->numero : ""); ?>
                                            </p>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Bairro</label>
                                        <p><?= (!empty($contrato->bairro) ? $contrato->bairro : "Não Informado"); ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Cidade</label>
                                        <?php if(!empty($contrato->cidade)): ?>
                                            <p>
                                                <?= $contrato->cidade; ?>
                                                <?= (!empty($contrato->estado) ? " - " . $contrato->estado : ""); ?>
                                            </p>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensalidades -->
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                    <div class="card">

                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="mb-0">Mensalidades</h3>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center table-flush dt-responsive">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Vencimento</th>
                                    <th scope="col" class="sort" data-sort="name">Data Repasse</th>
                                    <th scope="col" class="sort" data-sort="budget">Mensalidade</th>
                                    <th scope="col" class="sort" data-sort="budget">Repasse</th>
                                    <th scope="col" class="sort" data-sort="budget">Status Rensalidade</th>
                                    <th scope="col" class="sort" data-sort="budget">Status Repasse</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                    <?php foreach ($contrato->mensalidades as $mensalidade): ?>
                                        <tr>
                                            <td>
                                                <?= date("d/m/Y", strtotime($mensalidade->dataVencimento)) ?>
                                            </td>
                                            <td>
                                                <?= date("d/m/Y", strtotime($mensalidade->dataRepasse)) ?>
                                            </td>
                                            <td>
                                                R$<?= number_format($mensalidade->valorTotal, 2, ",", "."); ?>
                                            </td>
                                            <td>
                                                R$<?= number_format($mensalidade->valorRepasse, 2, ",", "."); ?>
                                            </td>
                                            <td>
                                                <?php if($mensalidade->pago): ?>
                                                    <span class="badge badge-pill badge-success">PAGO</span>
                                                <?php else: ?>
                                                    <?php if($mensalidade->dataVencimento < date("Y-m-d")): ?>
                                                        <span class="badge badge-pill badge-danger">ATRASADO</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-pill badge-warning">AGUARDANDO</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($mensalidade->repasse): ?>
                                                    <span class="badge badge-pill badge-success">PAGO</span>
                                                <?php else: ?>
                                                    <?php if($mensalidade->dataRepasse < date("Y-m-d")): ?>
                                                        <span class="badge badge-pill badge-danger">ATRASADO</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-pill badge-warning">AGUARDANDO</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a style="color: #525f7f !important;" class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" style="">
                                                        <a class="dropdown-item alterarMensalidade" href="#" data-id="<?= $mensalidade->id_mensalidadeRepasse ?>">
                                                            <?php if($mensalidade->pago): ?>
                                                                Cancelar Mensalidade
                                                            <?php else: ?>
                                                                Pagar Mensalidade
                                                            <?php endif; ?>
                                                        </a>

                                                        <a class="dropdown-item alterarRepasse" href="#" data-id="<?= $mensalidade->id_mensalidadeRepasse ?>">
                                                            <?php if($mensalidade->repasse): ?>
                                                                Cancelar Repasse
                                                            <?php else: ?>
                                                                Pagar Repasse
                                                            <?php endif; ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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

