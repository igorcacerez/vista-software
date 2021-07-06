<?php $this->view("site/include/header"); ?>

    <!-- Pesquisa -->
    <section class="pesquisa">
        <form id="formBusca">
            <div class="container">

                <!-- Botões -->
                <div class="row">
                    <div class="col-md-12 text-center">
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
                    <!-- Tipo de imovel -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <select class="selectBusca" multiple="multiple" name="tipo[]">
                                <option value="">Tipo de Imóvel</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria; ?>"><?= $categoria; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Bairros -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <select class="selectBusca" multiple="multiple" name="bairro[]">
                                <option value="">Bairros</option>
                                <?php foreach ($bairros as $bairro): ?>
                                    <option value="<?= $bairro; ?>"><?= $bairro; ?></option>
                                <?php endforeach; ?>
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
                        </div>
                    </div>

                    <!-- Código -->
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <input class="inputCodigo" type="text" name="codigo" placeholder="Código" />
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
                </div>
            </div>
        </form>
    </section>
    <!-- End >> Pesquisa -->

    <!-- Exibe os imóveis -->
    <section class="listaImoveis">
        <div class="container">
            <div class="row">

                <!-- Card do imovel -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card cardImovel">

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
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <div class="imovel-caracteristicas ">
                                        <div class="item">
                                            <i class="fa fa-bed"></i>
                                            <p class="valor">2</p>
                                            <p class="descricao">Dormitórios</p>
                                        </div>

                                        <div class="item">
                                            <i class="fa fa-bed"></i>
                                            <p class="valor">2</p>
                                            <p class="descricao">Dormitórios</p>
                                        </div>

                                        <div class="item">
                                            <i class="fa fa-bed"></i>
                                            <p class="valor">2</p>
                                            <p class="descricao">Dormitórios</p>
                                        </div>

                                        <div class="item">
                                            <i class="fa fa-bed"></i>
                                            <p class="valor">2</p>
                                            <p class="descricao">Dormitórios</p>
                                        </div>
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
    
    
    <?php $this->view("site/include/footer"); ?>
</body>
</html>