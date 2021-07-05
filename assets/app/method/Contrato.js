import Global from "../global.js"


/**
 * Método responsável por enviar a requisição de
 * inserção de locador para a api.
 */
$("#formAdicionarContrato").on("submit", function () {

    // Não atualiza a página
    event.preventDefault();

    // Recupera o formulário enviado
    var form = new FormData(this);

    // Bloqueia o formulário
    $(this).addClass("bloqueiaForm");

    // Recupera as informações para requisição
    var url = Global.config.urlApi + "contrato/insert";
    var token = Global.session.get("token").token;

    // Realiza a requisição
    Global.enviaApi("POST", url, form, token)
        .then((data) => {

            // Informa que deu certo
            Global.setSuccess(data.mensagem);

            // Limpa o formulário
            Global.limparFormulario("#formAdicionarContrato");

            // Remove o bloqueio
            $(this).removeClass("bloqueiaForm");

        })
        .catch((e) => {
            // Remove o bloqueio
            $(this).removeClass("bloqueiaForm");
        });

    // Força para não atualizar a página
    return false;

});


/**
 * Método responsável por enviar a requisição de
 * alteração para a api.
 */
$("#formAlterarProprietario").on("submit", function () {

    // Não atualiza a página
    event.preventDefault();

    // Recupera o formulário enviado
    var form = new FormData(this);

    // Bloqueia o formulário
    $(this).addClass("bloqueiaForm");

    // Recupera o id
    var id = $(this).data("id");

    // Recupera as informações para requisição
    var url = Global.config.urlApi + "locador/update/" + id;
    var token = Global.session.get("token").token;

    // Realiza a requisição
    Global.enviaApi("PUT", url, form, token)
        .then((data) => {

            // Informa que deu certo
            Global.setSuccess(data.mensagem);

            // Remove o bloqueio
            $(this).removeClass("bloqueiaForm");

        })
        .catch((e) => {
            // Remove o bloqueio
            $(this).removeClass("bloqueiaForm");
        });

    // Força para não atualizar a página
    return false;

});


/**
 * Método responsável por utilizar um json escondido na view
 * para exibir de forma dinamica o valor do imovel. ss
 */
$("#buscaImovel").change(function () {

    // Evita atualizações
    event.preventDefault();

    // Recupera o código selecionado
    var cod = $(this).val();

    // Recupera o json de imoveis
    var imoveis = $("#infoImovel").val();

    // Decodifica o json
    imoveis = JSON.parse(imoveis);

    // Verifica se encontrou
    if(imoveis[cod] !== undefined)
    {
        // Preenche os campos
        $("#inpAluguel").val(Global.formatMoney(imoveis[cod].ValorLocacao, 2, "", ".", ","));
        $("#inpCondominio").val(Global.formatMoney(imoveis[cod].ValorCondominio, 2, "", ".", ","));
        $("#inpIptu").val(Global.formatMoney(imoveis[cod].ValorIptu, 2, "", ".", ","));
    }

    // não atualiza
    return false;

});


/**
 * Carrega a tabela de listagem após o html todo
 * ser carregado.
 */
$(document).ready(function() {

    // Verifica se existe a div para evitar erros
    if($('.getDatatableContrato').length)
    {
        // getDatatableUsuarios
        var tabelaProprietarios = $('.getDatatableContrato').DataTable({
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            ajax: Global.config.url + "contrato/get-datatable",
            pageLength: 40,
            columns:[
                {"data": "contrato"},
                {"data": "imovel"},
                {"data": "cidade"},
                {"data": "inicio"},
                {"data": "fim"},
                {"data": "btn"}
            ],
            deferRender: true,
            drawCallback: function ()
            {
                /**
                 * Método responsável por deletar uma determinado
                 * locador. Enviando a solicitação para a API
                 */
                $(".deletarContrato").on("click", function () {

                    // Não atualiza a página
                    event.preventDefault();

                    // Recupera as informações
                    var id = $(this).data("id");

                    // Url e Token
                    var url = Global.config.urlApi + "contrato/delete/" + id;
                    var token = Global.session.get("token");

                    // Pergunta se realmente quer deletar
                    Swal.fire({
                        title: 'Deletar Contrato',
                        text: 'Deseja realmente deletar esse contrato?',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#55b5a6',
                        cancelButtonColor: '#DC3545',
                        confirmButtonText: 'Sim, deletar!'
                    }).then((result) => {
                        if (result.value)
                        {
                            // Realiza a solicitação
                            Global.enviaApi("DELETE", url, null, token.token)
                                .then((data) => {

                                    // Avisa que deu certo
                                    Global.setSuccess(data.mensagem);

                                    // Remove da tabela
                                    $('.getDatatableContrato')
                                        .DataTable()
                                        .row("#tb_" + id)
                                        .remove()
                                        .draw(false);
                                });
                        }
                    });


                    // Não atualiza mesmo
                    return false;
                });

            }
        });
    }

});// End >> Fun::listarProdutos()