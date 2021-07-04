<?php $this->view("painel/include/header"); ?>

    <!-- Header -->
    <div class="header bg-gradient-primary pb-6 pt-2">
        <div class="container-fluid">
            <div class="header-body">
                 
                <!-- Card stats -->
                <div class="row">

                    <!-- Contratos Ativos -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Contratos Ativos</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $numContratosAtivos; ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="ni ni-chart-pie-35"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Numero de Locatarios -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Locatários</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $numLocatarios; ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                            <i class="fas fa-user-friends"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lucro total -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Lucro Recebido</h5>
                                        <span class="h2 font-weight-bold mb-0">R$<?= $lucroRecebido; ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Valor esperado -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Lucro Esperado</h5>
                                        <span class="h2 font-weight-bold mb-0">R$<?= $lucroEsperado; ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                            <i class="ni ni-chart-bar-32"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row">

            <!-- Grafico de lucro da imobiliaria por mes -->
            <div class="col-xl-8">
                <div class="card bg-default">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-light text-uppercase ls-1 mb-1">ÚLTIMOS MESES</h6>
                                <h5 class="h3 text-white mb-0">Lucro da Imobiliária</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafico de contratos negociados por mês -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Por MÊs</h6>
                                <h5 class="h3 mb-0">Novos Contratos</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart-bars" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listagem -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Próximas mensalidades</h3>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Contrato</th>
                                <th scope="col">Imóvel</th>
                                <th scope="col">Proprietário</th>
                                <th scope="col">Locatário</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Vencimento</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($mensalidades)): ?>
                                <?php foreach ($mensalidades as $mensalidade): ?>
                                    <tr id="linha_<?= $mensalidade->id_mensalidadeRepasse; ?>">
                                        <th scope="row">
                                            #<?= $mensalidade->contrato->id_contrato; ?>
                                        </th>
                                        <td>
                                            #<?= $mensalidade->contrato->imovel; ?>
                                        </td>
                                        <td>
                                            <?= $mensalidade->contrato->locador->nome; ?>
                                        </td>
                                        <td>
                                            <?= $mensalidade->contrato->locatario->nome; ?>
                                        </td>
                                        <td>
                                            R$<?= number_format($mensalidade->valorTotal, 2, ",", "."); ?>
                                        </td>
                                        <td>
                                            <span <?= ($mensalidade->dataVencimento < date("Y-m-d") ? 'style="color: red;"' : ''); ?>>
                                                <?= date("d/m/Y", strtotime($mensalidade->dataVencimento)); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-success pagarMensalidadeRemoveLinha"
                                                    data-id="<?= $mensalidade->id_mensalidadeRepasse; ?>">PAGAR</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <td colspan="7">
                                    <p>Não possui nenhuma mensalidade próxima à vencer</p>
                                </td>
                            <?php endif; ?>
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
