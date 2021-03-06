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
                            <select class="selectBusca" multiple="multiple" name="categoria[]">
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
                            <div id="valorVenda">
                                <select class="selectBusca" name="valorVenda">
                                    <option value="">Faixa de Valores</option>
                                    <option value="0-100000">até R$ 100.000,00</option>
                                    <option value="100000-200000">R$ 100.000,00 à R$ 200.000,00</option>
                                    <option value="200000-300000">R$ 200.000,00 à R$ 300.000,00</option>
                                    <option value="300000-400000">R$ 300.000,00 à R$ 400.000,00</option>
                                    <option value="400000-500000">R$ 400.000,00 à R$ 500.000,00</option>
                                    <option value="500000-999999999">acima de R$ 500.000,00</option>
                                </select>
                            </div>

                            <!-- Aluguel -->
                            <div id="valorAluguel" style="display: none;">
                                <select class="selectBusca" name="valorAluguel">
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
    <section class="listaImoveis" id="listaImoveis">
        <div class="container">
            <div class="row">

                <!-- Sem imovel ou carregando -->
                <div v-if="itens.length == 0" class="col-md-12 auxiliar">

                    <p v-if="carregando == 1" class="carregando">
                        <img src="<?= BASE_URL; ?>assets/custom/img/loader-50.gif" alt="carregando" />
                        <span>Carregando</span>
                    </p>

                    <p v-else class="semItem">
                        Não encontramos nenhum imóvel.
                    </p>
                </div>

                <!-- Card do imovel -->
                <div v-for="imovel in itens" class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card cardImovel">

                        <!-- Imagem do imovel -->
                        <div class="imagem-imovel" v-bind:style="{'background-image': 'url(' + imovel.imagem + ')'}">
                            <span class="valor" v-if="imovel.valor == 0">NÃO INFORMADO</span>
                            <span class="valor" v-else>{{imovel.valor}}</span>
                        </div>

                        <div class="card-body">

                            <!-- Informações -->
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- titulo -->
                                    <h5 class="titulo">
                                        <span>{{imovel.bairro}}</span>
                                        {{imovel.categoria}}
                                    </h5>

                                    <p class="endereco">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{imovel.cidade}}
                                    </p>
                                </div>
                            </div>

                            <!-- Caracteristicas -->
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <div  class="imovel-caracteristicas">
                                        <div v-for="itensImovel in imovel.itens" class="item">
                                            <i v-bind:class="itensImovel.icone"></i>
                                            <p class="valor">{{itensImovel.valor}}</p>
                                            <p class="descricao">{{itensImovel.titulo}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" v-on:click="saibaMais()">Saiba Mais</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row" v-if="itens.length > 0 && paginaAtual < totalPaginas && carregando == 0">
                <div class="col-md-12 text-center pb-3 pt-3">
                    <button class="buscarMais" v-on:click="buscarMais()">Ver Mais</button>
                </div>
            </div>

        </div>
    </section>
    <!-- End >> Exibe os imóveis -->


    <!-- Modal do imóvel -->
    <div class="modal fade" id="modalImovel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border: none; background: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2>Se interessou?</h2>

                    <p>Entre em contato conosco.</p>

                    <a href="https://web.whatsapp.com/send?phone=+555182781111" target="_blank">
                        <button type="button" class="btn btn-primary">Fale Conosco</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <?php $this->view("site/include/footer"); ?>
</body>
</html>