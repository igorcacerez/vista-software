<?php

// NameSpace
namespace Controller\Api;

// Importações
use DuugWork\Controller;
use Helper\Seguranca;

/**
 * Classe responsável por realizar todos os processos
 * da api do usuario.
 *
 * Class Usuario
 * @package Controller\Api
 */
class Usuario extends Controller
{
    // Objetos
    private $objModelUsuario;
    private $objHelperSeguranca;

    // Método construtor
    public function __construct()
    {
        // Chama o construtor da class pai
        parent::__construct();

        // Instancia os objetos
        $this->objModelUsuario = new \Model\Usuario();
        $this->objHelperSeguranca = new Seguranca();

    } // End >> fun::__construct()


    /**
     * Método responsável por receber um email e senha,
     * verificar se existe algum cadastro na tabela usuario com
     * os dados informados. Caso tenha gera um token de acesso e
     * salva o mesmo em uma session e retorna para a view.
     * ------------------------------------------------------------
     * @url api/usuario/login
     * @method POST
     */
    public function login()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Armazena o usuario que realizo o login
        $dadosLogin = null; // Dados de acesso informados
        $token = null; // Token de acesso para o usuario

        // Recupera os dados de acesso
        $dadosLogin = $this->objHelperSeguranca->getDadosLogin();

        // Criptografa a senha
        $dadosLogin["senha"] = md5($dadosLogin["senha"]);

        // Realiza a busca do usuario
        $usuario = $this->objModelUsuario
            ->get(["email" => $dadosLogin["usuario"], "senha" => $dadosLogin["senha"]])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se encontrou o usuario
        if(!empty($usuario))
        {
            // Gera um token de acesso para o usuario
            $token = $this->objHelperSeguranca->getToken($usuario->id_usuario);

            // Verifica se retorno alguma
            if(!empty($token))
            {
                // Remove a senha do usuario
                unset($usuario->senha);

                // Salva a session
                $_SESSION["token"] = $token;

                // Retorno de sucesso
                $dados = [
                    "tipo" => true, // Informa que deu certo
                    "code" => 200, // Codigo HTTP
                    "mensagem" => "Sucesso! Aguarde...", // Mensagem de exibição no alerta
                    "objeto" => [
                        "usuario" => $usuario, // Usuario
                        "token" => $token // Token
                    ]
                ];
            }
            else
            {
                // Msg de retorno
                $dados = ["mensagem" => "Ocorreu um erro ao gerar um token de acesso."];
            } // Error >> Ocorreu um erro ao gerar um token de acesso.
        }
        else
        {
            // Msg de retorno
            $dados = ["mensagem" => "Usuario não encontrado."];
        } // Error >> Usuario não encontrado.

        // Retorno para a view
        $this->api($dados);

    } // End >> fun::login()


    /**
     * Método responsável por realizar a uma busca de um
     * determinado usuario, que possua o id informado.
     * ------------------------------------------------------------
     * @param $id [Id do usuario]
     * ------------------------------------------------------------
     * @url api/usuario/get/[ID]
     * @method GET
     */
    public function get($id)
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $obj = null; // Usuario Encontrado

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Realiza a busca
        $obj = $this->objModelUsuario
            ->get(["id_usuario" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Retorno
        $dados = [
            "tipo" => true, // Informa que a requisição aconteceu
            "code" => 200, // Status HTTP
            "objeto" => (!empty($obj) ? $obj : false) // Retorna o objeto ou false
        ];

        // Retorno para a view
        $this->api($dados);

    } // End >> fun::get()


    public function getAll()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $obj = null; // Usuarios Encontrados
        $ordem = null; // Ordem de exibição (ORDER BY)
        $where = null; // Condições para busca
        $pag = null; // Pagina atual para listagem
        $limite = null; // Numero limite de exibições por página
        $inicio = null; // Item inicial
        $orderBy = null; // Item pelo qual deve ordenar
        $orderTipo = null;  // Tipo da ordenação (ASC ou DESC)

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Variaveis Paginação
        $pag = (isset($_GET["pag"])) ? $_GET["pag"] : 1;
        $limite = (isset($_GET["limit"])) ? $_GET["limit"] : NUM_PAG;

        // Variveis da busca
        $orderBy = (isset($_GET["order_by"])) ? $_GET["order_by"] : null;
        $orderTipo = (isset($_GET["order_by_type"])) ? $_GET["order_by_type"] : "ASC";

        // Verifica se retornou o where
        $where = (isset($_GET["where"])) ? $_GET["where"] : null;

        // Verifica se foi informado a ordem
        if($orderBy != null)
        {
            // cria a ordem
            $ordem = $orderBy . " " . $orderTipo;
        }

        // Atribui a variável inicio, o inicio de onde os registros vão ser mostrados
        // por página, exemplo 0 à 10, 11 à 20 e assim por diante
        $inicio = ($pag * $limite) - $limite;

        // Realiza a busca com páginação
        $obj = $this->objModelUsuario
            ->get($where, $ordem, ($inicio . "," . $limite))
            ->fetchAll(\PDO::FETCH_OBJ);

        // Total de resultados encontrados sem o limite
        $total = $this->objModelUsuario
            ->get($where)
            ->rowCount();

    } // End >> fun::getAll()

} // End >> Class::Usuario