import Global from "../global.js"


/**
 * Método responsável por enviar a requisição de
 * inserção de locador para a api.
 */
$("#formAdicionarProprietario").on("submit", function () {

    // Não atualiza a página
    event.preventDefault();

    // Recupera o formulário enviado
    var form = new FormData(this);

    // Bloqueia o formulário
    $(this).addClass("bloqueiaForm");

    // Recupera as informações para requisição
    var url = Global.config.urlApi + "locador/insert";
    var token = Global.session.get("token").token;

    // Realiza a requisição
    Global.enviaApi("POST", url, form, token)
        .then((data) => {

            // Informa que deu certo
            Global.setSuccess(data.mensagem);

            // Limpa o formulário
            Global.limparFormulario("#formAdicionarProprietario");

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

            // Limpa o formulário
            Global.limparFormulario("#formAlterarProprietariosss");

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
 * Carrega a tabela de listagem após o html todo
 * ser carregado.
 */
$(document).ready(function() {

    // Verifica se existe a div para evitar erros
    if($('.getDatatableProprietarios').length)
    {
        // getDatatableUsuarios
        var tabelaProprietarios = $('.getDatatableProprietarios').DataTable({
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            ajax: Global.config.url + "locador/get-datatable",
            pageLength: 40,
            columns:[
                {"data": "nome"},
                {"data": "email"},
                {"data": "diasRepasse"},
                {"data": "btn"}
            ],
            deferRender: true,
            drawCallback: function ()
            {
                /**
                 * Método responsável por deletar uma determinado
                 * locador. Enviando a solicitação para a API
                 */
                $(".deletarProprietario").on("click", function () {

                    // Não atualiza a página
                    event.preventDefault();

                    // Recupera as informações
                    var id = $(this).data("id");

                    // Url e Token
                    var url = Global.config.urlApi + "locador/delete/" + id;
                    var token = Global.session.get("token");

                    // Pergunta se realmente quer deletar
                    Swal.fire({
                        title: 'Deletar Proprietário',
                        text: 'Deseja realmente deletar esse proprietário?',
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
                                    $('.getDatatableProprietarios')
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


                /**
                 * Método responsável por deletar uma determinado
                 * produto. Enviando a solicitação para a API
                 */
                $(".duplicarProduto").on("click", function () {

                    // Não atualiza a página
                    event.preventDefault();

                    // Recupera as informações
                    var id = $(this).data("id");

                    // Url e Token
                    var url = Global.config.urlApi + "produto/duplicar/" + id;
                    var token = Global.session.get("token");

                    // Pergunta se realmente quer deletar
                    Swal.fire({
                        title: 'Duplicar Produto',
                        text: 'Deseja duplicar este produto?',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#007BFF',
                        cancelButtonColor: '#DC3545',
                        confirmButtonText: 'Sim, duplique!'
                    }).then((result) => {
                        if (result.value)
                        {
                            // Realiza a solicitação
                            Global.enviaApi("POST", url, null, token.token)
                                .then((data) => {

                                    // Avisa que deu certo
                                    Global.setSuccess(data.mensagem);

                                    // Redireciona
                                    setTimeout(() => {
                                        location.href = Global.config.url + "produto/editar/" + data.objeto.id_produto;
                                    }, 800);
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