import Global from "../global.js"

/**
 * Método responsável por receber os dados os dados
 * e solicitar um requisição para que seja feio o login
 * do usuário.
 * ----------------------------------------------------------
 */
$("#formLogin").on("submit", function () {

    // Não atualiza a página
    event.preventDefault();

    // Recupera os dados do formulário
    var form = new FormData(this);

    // Bloqueia o formulário
    $(this).addClass("bloqueiaForm");

    // Dados de login
    var email = form.get("email");
    var senha = form.get("senha");

    // Realiza a requisição
    realizaLogin(email,senha)
        .then(function(data){

            // Salva a session
            Global.session.set("usuario", data.objeto.usuario);
            Global.session.set("token", data.objeto.token);

            // Avisa que deu certo
            Global.setSuccess("Sucesso! Aguarde...");

            // Atualiza a página
            setTimeout(() => {

                // Manda para o painel
                location.href = Global.config.url + "painel";

            }, 800);

        })
        .catch((error) => {
            // Desbloqueia o formulário
            $(this).removeClass("bloqueiaForm");
        });

    // Desbloqueia o formulário
    $(this).removeClass("bloqueiaForm");

    // Não atualiza mesmo
    return false;
});


/**
 * Método responsável por realizar o login
 * ----------------------------------------------------------
 * @param user string
 * @param senha string
 * ----------------------------------------------------------
 * @author igorcacerez
 * */
function realizaLogin(user, senha)
{
    return new Promise(function (resolve, reject) {

        // Configura o Header a ser enviado
        $.ajaxSetup({
            async: false,
            headers:{
                'Authorization': "Basic " + window.btoa(user + ":" + senha)
            }
        });

        // Faz o envio do post
        $.post(Global.config.urlApi + "usuario/login", null, (data) => {


            if(data.tipo === true)
            {
                resolve(data);
            }
            else
            {
                // Avisa que deu merda
                Global.setError(data.mensagem);

                reject(true);
            }

        }, "json");
    });

} // End >> Fun::realizaLogin()



/**
 * Método responsável por enviar a requisição de
 * inserção de usuario para a api.
 */
$("#formAdicionarUsuario").on("submit", function () {

    // Não atualiza a página
    event.preventDefault();

    // Recupera o formulário enviado
    var form = new FormData(this);

    // Bloqueia o formulário
    $(this).addClass("bloqueiaForm");

    // Recupera as informações para requisição
    var url = Global.config.urlApi + "usuario/insert";
    var token = Global.session.get("token").token;

    // Realiza a requisição
    Global.enviaApi("POST", url, form, token)
        .then((data) => {

            // Informa que deu certo
            Global.setSuccess(data.mensagem);

            // Limpa o formulário
            Global.limparFormulario("#formAdicionarUsuario");

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
$("#formAlterarUsuario").on("submit", function () {

    // Não atualiza a página
    event.preventDefault();

    // Recupera o formulário enviado
    var form = new FormData(this);

    // Bloqueia o formulário
    $(this).addClass("bloqueiaForm");

    // Recupera o id
    var id = $(this).data("id");

    // Recupera as informações para requisição
    var url = Global.config.urlApi + "usuario/update/" + id;
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
 * Carrega a tabela de listagem após o html todo
 * ser carregado.
 */
$(document).ready(function() {

    // Verifica se existe a div para evitar erros
    if($('.getDatatableUsuario').length)
    {
        // getDatatableUsuarios
        var tabelaProprietarios = $('.getDatatableUsuario').DataTable({
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            ajax: Global.config.url + "usuario/get-datatable",
            pageLength: 40,
            columns:[
                {"data": "nome"},
                {"data": "email"},
                {"data": "btn"}
            ],
            deferRender: true,
            drawCallback: function ()
            {
                /**
                 * Método responsável por deletar uma determinado
                 * locador. Enviando a solicitação para a API
                 */
                $(".deletarUsuario").on("click", function () {

                    // Não atualiza a página
                    event.preventDefault();

                    // Recupera as informações
                    var id = $(this).data("id");

                    // Url e Token
                    var url = Global.config.urlApi + "usuario/delete/" + id;
                    var token = Global.session.get("token");

                    // Pergunta se realmente quer deletar
                    Swal.fire({
                        title: 'Deletar Usuário',
                        text: 'Deseja realmente deletar esse usuário?',
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
                                    $('.getDatatableUsuario')
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

});// End >> Fun::getDatatableUsuario()