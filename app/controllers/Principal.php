<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 26/03/2019
 * Time: 18:29
 */

// NameSpace
namespace Controller;

// Importações
use DuugWork\Controller as CI_controller;
use DuugWork\Helper\SendCurl;
use Helper\Apoio;


/**
 * Classe responsável por conter métodos que construam
 * as paginas principais do site.
 *
 * Class Principal
 * @package Controller
 */
class Principal extends CI_controller
{
    // Objetos
    private $objHelperApoio;

    // Método construtor
    function __construct()
    {
        // Carrega o contrutor da classe pai
        parent::__construct();

        // Instancia os objetos
        $this->objHelperApoio = new Apoio();

    } // End >> fun::__construct()


    /**
     * Método responsável por configurar a página de login e de logout.
     * ------------------------------------------------------------------
     * @param null $email [Email do usuario de logout]
     * ------------------------------------------------------------------
     * @url login ou logout/[EMAIL]
     * @method GET
     */
    public function login($email = null)
    {
        // Variaveis
        $dados = null; // Armazena o conteudo que será exibido na view
        $seo = null; // Armazena o conteudo de Seo e Smo

        // Verifica se não possui session ativa
        if(empty($_SESSION["usuario"]))
        {
            // Recupera o conteudo de seo
            $seo = $this->getSEO(
                // SEO
                [
                    "title" => "Vista Soft | Acesso Restrito",
                    "description" => "Página de acesso restrito do sistema Vista Software."
                ],

                // SMO
                [
                    "url" => BASE_URL . "login",
                    "title" => "Vista Soft | Acesso Restrito",
                    "description" => "Página de acesso restrito do sistema Vista Software."
                ]
            );

            // Configura os dados de exibição
            $dados = [
                "seo" => $seo["seo"],
                "smo" => $seo["smo"],
                "email" => $email,
                "js" => ["modulos" => ["Usuario"]] // Modulo JS a ser utilizado na página
            ];

            // Chama a view
            $this->view("painel/acesso/login", $dados);
        }
        else
        {
            // Redirecioan
            header("Location: " . BASE_URL . "painel");
        } // Redireciona para o painel

    } // End >> fun::login()


    /**
     * Método responsável por limpar a session do php e do
     * javascript. Redireciona o usuario para a página de login
     * ------------------------------------------------------------------
     * @url sair
     * @method GET
     */
    public function sair()
    {
        // Destroi a session
        session_destroy();

        // Chama a página de sair
        $this->view("painel/acesso/sair");
    } // End >> fun::sair()


    public function index()
    {

    } // End >> fun::index()



    public function painel()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $condicao = null; // Condições a serem enviadas a api
        $numContratosAtivos = null; // Numero de contratos ativos
        $numLocatarios = null; // Numero de locatarios cadastrados
        $lucroRecebido = null; // Valor do lucro já recebido
        $lucroEsperado = null; // Valor do lucro esperado

        // Recupera o usuario logado
        $usuario = $this->objHelperApoio->seguranca();

        // Objeto de requisições
        $objHelperSendCurl = new SendCurl();

        // Configura o Header
        $objHelperSendCurl->setHeader("Token", "Bearer {$usuario->token->token}");
        $objHelperSendCurl->setHeader("Accept", "application/json");

        // Url
        $url = BASE_URL . "api/";

        // --------------------------------------------------------------------------------------------------
        // Contratos ativos ---------------------------------------------------------------------------------
        // Condição para a busca
        $condicao = [
            "where" => [
                "dataFim >=" => date("Y-m-d")
            ],
            "limit" => 1 // Faz com que o servidor não gaste processamento atoa
        ];

        // Realiza a busca
        $numContratosAtivos = $objHelperSendCurl->resquest(
            $url . "contrato/get?" . http_build_query($condicao),
            null,
            "GET",
            false
        );

        // Configura o valor recebido
        // Evitando erros
        $numContratosAtivos = (!empty($numContratosAtivos->tipo) ? $numContratosAtivos->objeto->total : 0);

        // --------------------------------------------------------------------------------------------------
        // --------------------------------------------------------------------------------------------------


        // --------------------------------------------------------------------------------------------------
        // Numero de locatários -----------------------------------------------------------------------------
        // Condição para a busca
        $condicao = [
            "limit" => 1 // Faz com que o servidor não gaste processamento atoa
        ];

        // Realiza a busca
        $numLocatarios = $objHelperSendCurl->resquest(
            $url . "locatario/get?" . http_build_query($condicao),
            null,
            "GET",
            true
        );

        // Configura o valor recebido
        // Evitando erros
        $numLocatarios = (!empty($numLocatarios->tipo) ? $numLocatarios->objeto->total : 0);

        // --------------------------------------------------------------------------------------------------
        // --------------------------------------------------------------------------------------------------


        // --------------------------------------------------------------------------------------------------
        // Valor de lucro recebido e esperado ---------------------------------------------------------------

        // Realiza a busca do lucro Recebido
        $lucroRecebido = $objHelperSendCurl->resquest(
            $url . "mensalidade/lucro/recebido",
            null,
            "GET",
            true
        );

        // Realiza a busca do lucro Esperado
        $lucroEsperado = $objHelperSendCurl->resquest(
            $url . "mensalidade/lucro/esperado",
            null,
            "GET",
            true
        );

        // Configura o valor recebido
        // Evitando erros
        $lucroRecebido = (!empty($lucroRecebido->tipo) ? $lucroRecebido->objeto : 0);
        $lucroEsperado = (!empty($lucroEsperado->tipo) ? $lucroEsperado->objeto : 0);

        // --------------------------------------------------------------------------------------------------
        // --------------------------------------------------------------------------------------------------

        // --------------------------------------------------------------------------------------------------
        // Próximas mensalidades desse mês ------------------------------------------------------------------

        // Condição de busca
        $condicao = [
            "where" => [
                "dataVencimento <=" => date("Y-m-d", strtotime("+30 days")),
                "pago" => 0
            ],
            "order_by" => "dataVencimento",
            "order_by_type" => "ASC",
            "limit" => 0, // Retornar todos sem paginação
        ];

        // Realiza a busca das mensalidades
        $mensalidades = $objHelperSendCurl
            ->resquest(
                $url . "mensalidade/get?" . http_build_query($condicao),
                null,
                "GET",
                true
            );

        // Evita erros
        $mensalidades = (!empty($mensalidades->objeto->itens) ? $mensalidades->objeto->itens : null);

        // Configura os dados de exibição
        $dados = [
            // Informações padrões
            "titulo" => "Vista Software | Painel Administrativo",
            "usuario" => $usuario,

            // Contadores
            "numContratosAtivos" => $numContratosAtivos,
            "numLocatarios" => $numLocatarios,
            "lucroRecebido" => number_format($lucroRecebido, 2,",", "."),
            "lucroEsperado" => number_format($lucroEsperado, 2,",", "."),

            // Mensalidade
            "mensalidades" => $mensalidades,

            // Js a ser carregados
            "js" => [
                "modulos" => ["Usuario","Grafico"], // Modulo JS a ser utilizado na página
                "plugins" => [
                    "chart.js/dist/Chart.min",
                    "chart.js/dist/Chart.extension"
                ]
            ]
        ];

        // Chama a view
        $this->view("painel/dashboard", $dados);

    } // End >> fun::painel()

} // END::Class Principal