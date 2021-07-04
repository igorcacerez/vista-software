<?php

// NameSpace
namespace Controller;

// Importações
use DuugWork\Controller;
use DuugWork\Helper\SendCurl;
use Helper\Apoio;


class Locador  extends Controller
{
    // Objetos
    private $objHelperApoio;

    // Método construtor
    public function __construct()
    {
        // Instancia o pai
        parent::__construct();

        // Instancia os objetos
        $this->objHelperApoio = new Apoio();

    } // End >> fun::__construct()


    public function listar()
    {
        // Variaveis
        $dados = null;
        $usuario = null;

        // Recupera o usuario logado
        $usuario = $this->objHelperApoio->seguranca();

        // Dados a serem enviados para a view
        $dados = [
            "titulo" => "Vista Software | Todos os proprietários",
            "usuario" => $usuario,

            // CSS
            "css" => [
                "datatables/css/css"
            ],

            // JS
            "js" => [
                "modulos" => ["Locador"],
                "plugins" => [
                    "datatables/js/js"
                ]
            ]
        ];

        // Chama a view
        $this->view("painel/locador/listar", $dados);

    } // End >> fun::listar()


    /**
     * Método responsável por montar as configurações de listagem
     * de locadores para a datatable, de maneira que carregue os
     * dados de modo otimizado.
     * ------------------------------------------------------------
     * @method GET
     * @url locador/get-datatable
     */
    public function getDataTable()
    {
        // Variaveis
        $data = [];
        $usuario = null;
        $objs = null;
        $condicao = null;
        $url = null;
        $btn = null;

        // Seguranca
        $usuario = $this->objHelperApoio->seguranca();

        // Instancia o objeto de requisição
        $objHelperSendCurl = new SendCurl();

        // Informa o header
        $objHelperSendCurl->setHeader("Token", "Bearer " . $usuario->token->token);
        $objHelperSendCurl->setHeader("Accept", "application/json");

        // Url da requisição
        // Com limite 0 para retornar todos os reguistros
        $url = BASE_URL . "api/locador/get?limit=0";

        // Realiza a requisição
        $objs = $objHelperSendCurl
            ->resquest(
                $url,
                null,
                "GET",
                true
            );

        // Verifica se houve retorno
        if(!empty($objs->objeto->itens))
        {
            // Percorre os itens retornados
            foreach ($objs->objeto->itens as $item)
            {
                // Configura o botão
                $btn = '<div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" style="">
                                <a class="dropdown-item" href="'. BASE_URL .'locador/alterar/'. $item->id_locador .'">Alterar</a>
                                <a class="dropdown-item deletarProprietario" data-id="'. $item->id_locador .'" href="#">Deletar</a>
                            </div>
                        </div>';

                // Cria o array
                $data[] = [
                    "DT_RowId" => "tb_" . $item->id_locador,
                    "nome" => $item->nome,
                    "email" => $item->email,
                    "diasRepasse" => $item->diasRepasse,
                    "btn" => $btn
                ];
            }

            // Retorno
            echo json_encode(["data" => $data]);
        }

    } // End >> fun::getDataTable()

} // End >> CLass::Locador