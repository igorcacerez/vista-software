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

        // Recupera o usuario logado
        $usuario = $this->objHelperApoio->seguranca();



    } // End >> fun::painel()

} // END::Class Principal