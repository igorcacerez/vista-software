import Global from "../global.js"


/**
 * Método responsável por pagar uma fatura e remover a
 * linha da databela.
 * ----------------------------------------------------------
 */
$(".pagarMensalidadeRemoveLinha").on("click", function () {

    // Não atualiza
    event.preventDefault();

    // Pergunta se realmente quer deletar
    Swal.fire({
        title: 'Pagar Mensalidade',
        text: 'Deseja realmente alterar o status para pago?',
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#55b5a6',
        cancelButtonColor: '#DC3545',
        confirmButtonText: 'Sim!'
    }).then((result) => {
        if (result.value)
        {
            // Recupera os dados via data
            var id = $(this).data("id");

            // Bloqueia a linha
            $("#linha_" + id).addClass("bloqueiaForm");

            // Recupera o token e url
            var url = Global.config.urlApi + "mensalidade/update/mensalidade/" + id;
            var token = Global.session.get("token").token;

            // Realiza a requisição
            Global.enviaApi("PUT", url, null, token)
                .then((data) => {

                    // Informa que deu certo
                    Global.setSuccess(data.mensagem);

                    // Remove o bloqueio da linha
                    $("#linha_" + id).removeClass("bloqueiaForm");

                    // Remove a linha
                    $("#linha_" + id).css("display", "none");

                })
                .catch((e) => {
                    // Remove o bloqueio da linha
                    $("#linha_" + id).removeClass("bloqueiaForm");
                });
        }
    });

    // Não atualiza mesmo
    return false;

});