<?php


// NameSpace
namespace Controller;

// Importações
use DuugWork\Helper\SendCurl;
use Helper\Apoio;

/**
 * Classe responsável por conter os métodos responsáveis
 * por configurar as páginas relativas ao usuario
 *
 * Class Usuario
 * @package Controller
 */
class Usuario extends \DuugWork\Controller
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
     * listar todos os usuarios.
     * ------------------------------------------------------------
     * @method GET
     * @url usuarios
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
            "titulo" => "Vista Software | Todos os usuários",
            "usuario" => $usuario,

            // CSS
            "css" => [
                "datatables/css/css"
            ],

            // JS
            "js" => [
                "modulos" => ["Usuario"],
                "plugins" => [
                    "datatables/js/js"
                ]
            ]
        ];

        // Chama a view
        $this->view("painel/usuario/listar", $dados);

    } // End >> fun::listar()


    /**
     * Método responsável por configurar a página que
     * possui o formulário de cadatro para um novo
     * usuario no sistema.
     * ------------------------------------------------------------
     * @method GET
     * @url usuario/inserir
     */
    public function inserir()
    {
        // Variaveis
        $dados = null; // Dados a serem exibidos na view
        $usuario = null; // Usuario logado

        // Recupera o usuário logado
        $usuario = $this->objHelperApoio->seguranca();

        // Dados a serem enviados para a view
        $dados = [
            "titulo" => "Vista Software | Adicionar Usuário",
            "usuario" => $usuario,

            // Js
            "js" => [
                "modulos" => ["Usuario"]
            ]
        ];

        // Chama a view
        $this->view("painel/usuario/inserir", $dados);

    } // End >> fun::inserir()


    /**
     * Método responsável por busca o usuario pelo id informado
     * e configurar a página de alteração do mesmo.
     * ------------------------------------------------------------
     * @param $id [Id do usuario]
     * ------------------------------------------------------------
     * @method GET
     * @url usuario/alterar/[ID]
     */
    public function alterar($id)
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
        $url = BASE_URL . "api/usuario/get/" . $id;

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
                "titulo" => "Vista Software | Alterar Usuário",
                "usuario" => $usuario,
                "user" => $obj->objeto,

                "js" => [
                    "modulos" => ["Usuario"]
                ]
            ];

            // Chama a view
            $this->view("painel/usuario/alterar", $dados);
        }
        else
        {
            // Redireciona o usuario para a tela de inserção
            $this->inserir();
        } // Não foi encontrado

    } // End >> fun::alterar()


    /**
     * Método responsável por montar as configurações de listagem
     * de locatarios para a datatable, de maneira que carregue os
     * dados de modo otimizado.
     * ------------------------------------------------------------
     * @method GET
     * @url usuario/get-datatable
     */
    public function getDataTable()
    {
        // Variaveis
        $data = []; // Armazena os dados a serem enviados no padrão correto
        $usuario = null; // Usuario logado
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
        $url = BASE_URL . "api/usuario/get?limit=0";

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
                                <a class="dropdown-item" href="'. BASE_URL .'usuario/alterar/'. $item->id_usuario .'">Alterar</a>
                                <a class="dropdown-item deletarUsuario" data-id="'. $item->id_usuario .'" href="#">Deletar</a>
                            </div>
                        </div>';

                // Cria o array
                $data[] = [
                    "DT_RowId" => "tb_" . $item->id_usuario,
                    "nome" => $item->nome,
                    "email" => $item->email,
                    "btn" => $btn
                ];
            }
        }

        // Retorno
        echo json_encode(["data" => $data]);

    } // End >> fun::getDataTable()

} // End >> Class::Usuario