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
                                    <h3 class="mb-0">Inserir Contrato</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="<?= BASE_URL; ?>contratos" class="btn btn-sm btn-primary">Ver Todos</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form id="formAdicionarContrato">
                                <h6 class="heading-small text-muted mb-4">Campos com (*) são obrigatórios</h6>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Imóvel *</label>
                                            <select class="selectBusca" id="buscaImovel" name="imovel" required>
                                                <option selected disabled>Selecione um imóvel</option>
                                                <?php foreach ($imoveis as $imovel): ?>
                                                    <option value="<?= $imovel->Codigo; ?>"><?= $imovel->Codigo; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Proprietário *</label>
                                            <select class="selectBusca" name="id_locador" required>
                                                <option selected disabled>Selecione um proprietário</option>
                                                <?php foreach ($locadores as $locador): ?>
                                                    <option value="<?= $locador->id_locador; ?>"><?= $locador->nome; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Locatário *</label>
                                            <select class="selectBusca" name="id_locatario" required>
                                                <option selected disabled>Selecione um locatário</option>
                                                <?php foreach ($locatarios as $locatario): ?>
                                                    <option value="<?= $locatario->id_locatario; ?>"><?= $locatario->nome; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Taxa de Administração *</label>
                                            <input type="text" class="form-control maskNumero" required name="taxaAdministracao" placeholder="Em porcentagem" />
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Data Início *</label>
                                            <input type="date" class="form-control" required name="dataInicio" placeholder="Início do contrato" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Data Fim *</label>
                                            <input type="date" class="form-control" required name="dataFim" placeholder="Final do contrato" />
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Aluguel *</label>
                                            <input type="text" id="inpAluguel" class="form-control maskValor" required name="valorAluguel" placeholder="Valor do aluguel" />
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Condomínio</label>
                                            <input type="text" id="inpCondominio" class="form-control maskValor" name="valorCondominio" placeholder="Valor do condomínio" />
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">IPTU</label>
                                            <input type="text" id="inpIptu" class="form-control maskValor" name="valorIptu" placeholder="Valor do IPTU" />
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-black">
                                            Cadastrar
                                        </button>
                                    </div>
                                </div>

                            </form>

                            <!-- Evita outra requisição na api -->
                            <textarea id="infoImovel" style="display:none;">
                                <?= json_encode($imoveis); ?>
                            </textarea>
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

