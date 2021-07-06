import Global from "../global.js"

// Constantes
var tipoAtivo = "comprar";
var buscaAtual = "";

// Verifica se existe
if($("#listaImoveis").length)
{
    // Cria a aplicação VUE
    var objImoveis = new Vue({
        el: '#listaImoveis',
        data: {
            itens: [],
            carregando: 1,
            paginaAtual: 1,
            totalPaginas: 1,
        },
        methods: {
            buscarMais: function () {

                // Altera a pagina
                var pagina = (parseInt(objImoveis.paginaAtual) + 1);

                // Busca mais imoveis
                buscaImovel(buscaAtual, tipoAtivo, pagina);

            }

        } // End >> methods

    }); // End >> objImoveis
}


/**
 * Altera o botão selecionado na busca.
 * ----------------------------------------------
 */
$(".btnEscolha").on("click", function () {

    // Não atualiza
    event.preventDefault();

    // Recupera o tipo
    var tipo = $(this).data("tipo");

    // Verifica se é o tipo que está ativo
    if(tipo !== tipoAtivo)
    {
        // Verifica se é comprar
        if(tipo === "comprar")
        {
            // Desativa o botão aluguel
            $("#btnAlugar").removeClass("selecionado");

            // Remove a o valor ativo
            $("#valorAluguel").css("display", "none");

            // Ativa o botão comprar
            $("#btnComprar").addClass("selecionado");

            // Ativa o valor
            $("#valorVenda").css("display", "block");
        }
        else
        {
            // Desativa o botão comprar
            $("#btnComprar").removeClass("selecionado");

            // Remove a o valor ativo
            $("#valorVenda").css("display", "none");

            // Ativa o botão alugel
            $("#btnAlugar").addClass("selecionado");

            // Ativa o valor
            $("#valorAluguel").css("display", "block");
        }

        // Altera a constante
        tipoAtivo = tipo;

        // Limpa o objeto vue
        objImoveis.carregando = 1;
        objImoveis.itens = [];

        // Busca os itens
        buscaImovel(null,tipo);
    }

    // Não atualiza mesmo
    return false;
});


$("#formBusca").on("submit", function () {

    // Não atualiza
    event.preventDefault();

    // Recupera os dados
    var form = new FormData(this);

    // Bloqueia o form
    $(this).addClass("bloqueiaForm");

    // Salva o formulario
    buscaAtual = form;

    // Limpa o objeto vue
    objImoveis.carregando = 1;
    objImoveis.itens = [];

    // Busca os itens
    buscaImovel(form,tipoAtivo)
        .then((e) => {
            // Desbloqueia o form
            $(this).removeClass("bloqueiaForm");
        })
        .catch((e) => {
            // Desbloqueia o form
            $(this).removeClass("bloqueiaForm");
        });

    // Não atualiza mesmo
    return false;

});


function buscaImovel(form, tipo = tipoAtivo, pag = 0)
{
    return new Promise(function (resolve, reject) {

        // Recupera as informações
        var url = Global.config.urlApi + "imovel/filtra/" + tipo;

        // Verifica se informou a página
        if(pag > 0)
        {
            url += "?pag=" + pag;
        }

        // Realiza a requisição
        Global.enviaApi("POST", url, form)
            .then(async (data) => {

                // Informa que está carregando
                objImoveis.carregando = 1;

                // Verifica se deu certo a requisição
                if(data.objeto !== false && data.objeto !== null && data.objeto !== "")
                {
                    // Salva as páginas
                    objImoveis.paginaAtual = parseInt(data.objeto.pagina);
                    objImoveis.totalPaginas = parseInt(data.objeto.numPaginas);

                    console.log(data.objeto.itens);

                    // Percorre os itens
                    await data.objeto.itens.forEach((imovel) => {

                        console.log(imovel);

                        // Adiciona no vue
                        objImoveis.itens.push(imovel);

                    });

                    // Encerra o carregamento
                    objImoveis.carregando = 0;

                    // Informa que deu certo
                    resolve(true);
                }
                else
                {
                    // Encerra o carregamento
                    objImoveis.carregando = 0;

                    // Encerra
                    reject(true);
                } // Não retornou nada

            });

    });
}


/**
 * Carrega a tabela de listagem após o html todo
 * ser carregado.
 */
$(document).ready(function() {

    // Busca os imoveis
    buscaImovel(null);

});