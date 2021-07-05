<?php

// NameSpace
namespace Controller;

// Importações
use DuugWork\Helper\SendCurl;
use Helper\Apoio;


/**
 * Classe responsável por conter os métodos responsáveis
 * por configurar as páginas relativas ao contrato
 *
 * Class Contrato
 * @package Controller
 */
class Contrato extends \DuugWork\Controller
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


    /**
     * Método responsável por configurar a página que irá
     * listar todos os contratos.
     * ------------------------------------------------------------
     * @method GET
     * @url contratos
     */
    public function listar()
    {
        // Variaveis
        $dados = null; // Dados a serem exibidos na view
        $usuario = null; // Usuario logado

        // Recupera o usuario logado
        $usuario = $this->objHelperApoio->seguranca();

        // Dados a serem enviados para a view
        $dados = [
            "titulo" => "Vista Software | Todos os contratos",
            "usuario" => $usuario,

            // CSS
            "css" => [
                "datatables/css/css"
            ],

            // JS
            "js" => [
                "modulos" => ["Contrato"],
                "plugins" => [
                    "datatables/js/js"
                ]
            ]
        ];

        // Chama a view
        $this->view("painel/contrato/listar", $dados);

    } // End >> fun::listar()



    /**
     * Método responsável por configurar a página que
     * possui o formulário de cadatro para um novo
     * contrato no sistema.
     * ------------------------------------------------------------
     * @method GET
     * @url contrato/inserir
     */
    public function inserir()
    {
        // Variaveis
        $dados = null; // Dados a serem exibidos na view
        $usuario = null; // Usuario logado
        $url = null; // Url para requisição na api Vista
        $informacoesBuscaApi = null; // Informações a serem retornadas
        $imoveis = null; // Imoveis retornados a api

        // Recupera o usuário logado
        $usuario = $this->objHelperApoio->seguranca();

        // Instancia o objeto de requisição
        $objHelperSend = new SendCurl();

        // Informações a ser retornadas pelo
        $informacoesBuscaApi = [
            "fields" => [
                "Codigo",
                "ValorLocacao", "ValorIptu", "ValorCondominio"
            ]
        ];

        // Configura a url
        $url = URL_API . "imoveis/listar?key=" . TOKEN_VISTA;
        $url .= "&pesquisa=" . json_encode($informacoesBuscaApi);

        // Configura o cabeçalho da requisição
        $objHelperSend->setHeader("Accept", "application/json"); // Aceita resposta em Json

        // Realiza a requisição na Api do Vista CRM
        $imoveis = $objHelperSend->resquest($url, null, "GET", true);


        // Buscando os locatarios
        // -------------------------------------------------------------------

        // Configura a url
        $url = BASE_URL . "api/locatario/get?limit=0";

        // Adiciona o token no header
        $objHelperSend->setHeader("Token", "Bearer " . $usuario->token->token);

        // Realiza a busca
        $locatarios = $objHelperSend->resquest($url, null, "GET", true);

        // Previne de erros
        $locatarios = (!empty($locatarios->objeto->itens) ? $locatarios->objeto->itens: null);

        // -------------------------------------------------------------------


        // Buscando os locadores
        // -------------------------------------------------------------------

        // Configura a url
        $url = BASE_URL . "api/locador/get?limit=0";

        // Realiza a busca
        $locadores = $objHelperSend->resquest($url, null, "GET", true);

        // Previne de erros
        $locadores = (!empty($locadores->objeto->itens) ? $locadores->objeto->itens : null);


        // Dados a serem enviados para a view
        $dados = [
            "titulo" => "Vista Software | Adicionar Contrato",
            "usuario" => $usuario,
            "imoveis" => $imoveis,
            "locatarios" => $locatarios,
            "locadores" => $locadores,

            // Css
            "css" => [
                "selectize/dist/css/selectize"
            ],

            // Js
            "js" => [
                "modulos" => ["Contrato"],
                "plugins" => ["selectize/dist/js/standalone/selectize"],
                "pages" => ["selectize"]
            ]
        ];

        // Chama a view
        $this->view("painel/contrato/inserir", $dados);

    } // End >> fun::inserir()


    /**
     * Método responsável por busca o contrato pelo id informado
     * e configurar a página de detalhes do mesmo.
     * ------------------------------------------------------------
     * @param $id [Id do locador]
     * ------------------------------------------------------------
     * @method GET
     * @url contrato/detalhes/[ID]
     */
    public function detalhes($id)
    {
        // Variaveis
        $dados = null;
        $usuario = null;
        $obj = null;
        $url = null;

        // Recupera o usuário locado
        $usuario = $this->objHelperApoio->seguranca();

        // Instancia o objeto da requisição
        $objHelperSendCurl = new SendCurl();

        // Configura o header
        $objHelperSendCurl->setHeader("Accept", "application/json");
        $objHelperSendCurl->setHeader("Token","Bearer " . $usuario->token->token);

        // Url da requisição
        $url = BASE_URL . "api/contrato/get/" . $id;

        // Realiza a requisição
        $obj = $objHelperSendCurl
            ->resquest(
                $url,
                null,
                "GET",
                true
            );

        // Verifica se retornou o objeto
        if(!empty($obj->objeto))
        {
            // Configura os dados a ser enviados para a view
            $dados = [
                "titulo" => "Vista Software | Detalhes do Contrato",
                "usuario" => $usuario,
                "contrato" => $obj->objeto,

                "js" => [
                    "modulos" => ["Contrato","Mensalidade"]
                ]
            ];

            // Chama a view
            $this->view("painel/contrato/datalhes", $dados);
        }
        else
        {
            // Redireciona o usuario para a tela de inserção
            $this->inserir();
        } // Não foi encontrado

    } // End >> fun::detalhes()


    /**
     * Método responsável por montar as configurações de listagem
     * de contratos para a datatable, de maneira que carregue os
     * dados de modo otimizado.
     * ------------------------------------------------------------
     * @method GET
     * @url contrato/get-datatable
     */
    public function getDataTable()
    {
        // Variaveis
        $data = []; // Armazena os dados a serem enviados no padrão correto
        $usuario = null; // Usuario locado
        $objs = null; // Objetos retornados da api
        $url = null; // Url a ser requirida na api
        $btn = null; // Configuração do botão

        // Seguranca
        $usuario = $this->objHelperApoio->seguranca();

        // Instancia o objeto de requisição
        $objHelperSendCurl = new SendCurl();

        // Informa o header
        $objHelperSendCurl->setHeader("Token", "Bearer " . $usuario->token->token);
        $objHelperSendCurl->setHeader("Accept", "application/json");

        // Url da requisição
        // Com limite 0 para retornar todos os reguistros
        $url = BASE_URL . "api/contrato/get?limit=0";

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
                                <a class="dropdown-item" href="'. BASE_URL .'contrato/detalhes/'. $item->id_contrato .'">Ver Detalhes</a>
                                <a class="dropdown-item deletarContrato" data-id="'. $item->id_contrato .'" href="#">Deletar</a>
                            </div>
                        </div>';

                // Cria o array
                $data[] = [
                    "DT_RowId" => "tb_" . $item->id_contrato,
                    "contrato" => "#" . $item->id_contrato,
                    "imovel" => "#" . $item->imovel,
                    "cidade" => $item->cidade . " - " . $item->estado,
                    "inicio" => "<span style='display: none;'>". date("Ymd", strtotime($item->dataInicio)) ."</span>" . date("d/m/Y", strtotime($item->dataInicio)),
                    "fim" => "<span style='display: none;'>". date("Ymd", strtotime($item->dataFim)) ."</span>" . date("d/m/Y", strtotime($item->dataFim)),
                    "btn" => $btn
                ];
            }
        }

        // Retorno
        echo json_encode(["data" => $data]);

    } // End >> fun::getDataTable()


} // End >> Class::Contrato