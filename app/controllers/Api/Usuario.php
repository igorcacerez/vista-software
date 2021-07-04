<?php

// NameSpace
namespace Controller\Api;

// Importações
use DuugWork\Controller;
use DuugWork\Helper\Input;
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
                $_SESSION["usuario"] = $usuario;

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
            $dados = ["mensagem" => "E-mail ou senha informados estão incorretos."];
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


    /**
     * Método responsável por realizar a uma busca de usuarios
     * podendo filtrar por condições, ordenar e utilziar limites
     * de exibição por página.
     * ------------------------------------------------------------
     * @url api/usuario/get
     * @method GET
     */
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
        $numPag = 1; // Numero total de paginas existente para a busca
        $limiteConfig = null; // Configuração do limite

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


        // Verifica se possui limite informado
        if($limite != 0)
        {
            // Atribui a variável inicio, o inicio de onde os registros vão ser mostrados
            // por página, exemplo 0 à 10, 11 à 20 e assim por diante
            $inicio = ($pag * $limite) - $limite;

            // Configura o limite
            $limiteConfig = "{$inicio},{$limite}";
        }

        // Realiza a busca com páginação
        $obj = $this->objModelUsuario
            ->get($where, $ordem, $limiteConfig)
            ->fetchAll(\PDO::FETCH_OBJ);

        // Total de resultados encontrados sem o
        // limite
        $total = $this->objModelUsuario
            ->get($where)
            ->rowCount();

        // Realiza o calculo das páginas
        if($limite != 0)
        {
            // Existe limite
            $numPag = ($total > 0) ? ceil($total / $limite) : 1;
        }

        // Monta o array de retorno
        $dados = [
            "tipo" => true,
            "code" => 200,
            "objeto" => [
                "itens" => $obj,
                "total" => $total,
                "pagina" => $pag,
                "numPaginas" => $numPag
            ]
        ];

        // Retorno
        $this->api($dados);

    } // End >> fun::getAll()


    /**
     * Método responsável por receber as informações do usuário via
     * post, realizar verificações de campos obrigatorios e verificar
     * se o e-mail informado é unico. Em caso de sucesso insere o
     * usuario no banco de dados e retorna seu registro.
     * ------------------------------------------------------------
     * @url api/usuario/insert
     * @method POST
     */
    public function insert()
    {
        // Variaveis
        $usuario = null; // Usuario logado
        $dados = null; // Retorno para a view
        $post = null; // Recupera os dados enviados via POST
        $salva = null; // Array de inserção no banco de dados
        $obj = null; // Objeto inserido no banco de dados

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Recupera od dados post
        $post = $_POST;

        // Verifica se informou os dados obrigatórios
        if(!empty($post["email"])
            && !empty($post["nome"])
            && !empty($post["senha"])
            && !empty($post["repeteSenha"]))
        {
            // Verifica se já possui algum cadastro com o e-mail informado
            if($this->objModelUsuario->get(["email" => $post["email"]])->rowCount() == 0)
            {
                // Verifica se as senhas combinam
                if($post["senha"] == $post["repeteSenha"])
                {
                    // Monta o array de inserção no banco
                    $salva = [
                        "nome" => $post["nome"],
                        "email" => $post["email"],
                        "senha" => md5($post["senha"]) // Criptografa em MD5
                    ];

                    // Insere no banco de dados
                    $obj = $this->objModelUsuario
                        ->insert($salva);

                    // Verifica se o usuário foi inserido
                    if(!empty($obj))
                    {
                        // Busca o objeto recem adicionado no banco de dados
                        $obj = $this->objModelUsuario
                            ->get(["id_usuario" => $obj])
                            ->fetch(\PDO::FETCH_OBJ);

                        // Remove a senha
                        unset($obj->senha);

                        // Array de retorno
                        $dados = [
                            "tipo" => true, // Informa que deu certo
                            "code" => 200, // codigo http
                            "mensagem" => "Usuário adicionado com sucesso.", // Mensagem de exibição
                            "objeto" => $obj // Retorna o objeto adicionado
                        ];
                    }
                    else
                    {
                        // Msg
                        $dados = ["mensagem" => "Ocorreu um erro ao inserir o usuário."];
                    } // Error >> Ocorreu um erro ao inserir o usuário.
                }
                else
                {
                    // Msg
                    $dados = ["mensagem" => "As senhas não combinam."];
                } // Error >> As senhas não combinam.
            }
            else
            {
                // Msg
                $dados = ["mensagem" => "Já existe um cadastro com o e-mail informado."];
            } // Error >> Já existe um cadastro com o e-mail informado.
        }
        else
        {
            // Msg
            $dados = ["mensagem" => "Dados obrigatórios não foram informados."];
        } // Error >> Dados obrigatórios não foram informados.

        // Retorno
        $this->api($dados);

    } // End >> fun::insert()


    /**
     * Método responsável por receber as informações para serem
     * alteradas de um determinado usuário e alterar as informações
     * no banco de dados.
     * ------------------------------------------------------------
     * @param $id [Id do usuário a ser alterado]
     * ------------------------------------------------------------
     * @url api/usuario/update/[ID]
     * @method PUT
     */
    public function update($id)
    {
        // Variaveis
        $usuario = null; // Usuario logado
        $dados = null; // Retorno para a view
        $altera = null; // Array de alteração no banco de dados
        $obj = null; // Objeto antes de ser alterado
        $objAlterado = null; // Objeto apos ser alterado

        // Objetos
        $objHelperInput = new Input(); // Auxilia na recuperação de informações via PUT

        // Recupera o usuario logado
        $usuario = $this->objHelperSeguranca->security();

        // Busca o usuário a ser alterado
        $obj = $this->objModelUsuario
            ->get(["id_usuario" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se o usuario foi encontrado
        if(!empty($obj))
        {
            // Recupera os dados put
            $put = $objHelperInput->put();

            // Remove campos que não podem ser alterados
            unset($put["id_usuario"]);

            // Verifica se vai alterar a senha
            if(!empty($put["senha"]))
            {
                // Verifica se a senha e a repeteSenha são identicas
                if($put["senha"] == $put["repeteSenha"])
                {
                    // Criptografa a senha
                    $put["senha"] = md5($put["senha"]);
                }
                else
                {
                    // Informa do erro e encerra o processo.
                    $this->api(["mensagem" => "As senhas informadas não confere."]);

                } // Error >> As senhas informadas não confere.
            }
            else
            {
                // Remove a senha
                unset($put["senha"]);
            } // Como não vai alterar - Remove a senha

            // Remove o repeteSenha
            unset($put["repeteSenha"]);

            // Verifica se vai alterar o email
            if(!empty($put["email"]) && $put["email"] != $obj->email)
            {
                // Verifica se o email está em uso
                if($this->objModelUsuario->get(["email" => $put["email"]])->rowCount() > 0)
                {
                    // Informa e encerra o processo
                    $this->api(["mensagem" => "O email informado já está em uso."]);
                } // Error >> O email informado já está em uso.
            }


            // Realiza a alteração
            if($this->objModelUsuario->update($put, ["id_usuario" => $id]) != false)
            {
                // Busca o objeto alterado
                $objAlterado = $this->objModelUsuario
                    ->get(["id_usuario" => $id])
                    ->fetch(\PDO::FETCH_OBJ);

                // Remove as senhas
                unset($obj->senha);
                unset($objAlterado->senha);

                // Array de retorno
                $dados = [
                    "tipo" => true, // Informa que deu certo
                    "code" => 200, // codigo http
                    "mensagem" => "Informações alteradas com sucesso.", // Mensagem de exibição
                    "objeto" => [
                        "antes" => $obj, // Retorna o objeto sem alteracao
                        "atual" => $objAlterado // Retorna o objeto apos ser alterado
                    ]
                ];
            }
            else
            {
                // Msg
                $dados = ["mensagem" => "Ocorreu um erro ao alterar as informações."];
            } // Error >> Ocorreu um erro ao alterar as informações.
        }
        else
        {
            // Msg
            $dados = ["mensagem" => "O item informado não existe."];
        } // Error >> O item informado não existe.

        // Retorno
        $this->api($dados);

    } // End >> fun::update()


    /**
     * Método responsável por receber o id de um determinadado
     * usuario e deletar o mesmo do banco de dados.
     * ------------------------------------------------------------
     * @param $id [Id do usuário a ser deletado]
     * ------------------------------------------------------------
     * @url api/usuario/delete/[ID]
     * @method DELETE
     */
    public function delete($id)
    {
        // Variaveis
        $usuario = null; // Usuario logado
        $dados = null; // Retorno para a view
        $obj = null; // Objeto a ser deletado

        // Recupera o usuário logado
        $usuario = $this->objHelperSeguranca->security();

        // Busca o objeto a ser deletado
        $obj = $this->objModelUsuario
            ->get(["id_usuario" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se encontrou o objeto informado
        if(!empty($obj))
        {
            // Verifica se não esta tentando se auto deletar
            if($usuario->id_usuario != $obj->id_usuario)
            {
                // Deleta o objeto
                if($this->objModelUsuario->delete(["id_usuario" => $id]) != false)
                {
                    // Remove a senha
                    unset($obj->senha);

                    // Array de retorno
                    $dados = [
                        "tipo" => true, // Informa que deu certo
                        "code" => 200, // codigo http
                        "mensagem" => "Item deletado com sucesso.", // Mensagem de exibição
                        "objeto" => $obj // Retorna o objeto deletado
                    ];
                }
                else
                {
                    // Msg
                    $dados = ["mensagem" => "Ocorre um erro ao tentar deletar."];
                } // Error >> Ocorre um erro ao tentar deletar.
            }
            else
            {
                // Msg
                $dados = ["mensagem" => "Impossivel deletar seu próprio usuário."];
            } // Error >> Impossivel deletar seu próprio usuário.
        }
        else
        {
            // Msg
            $dados = ["mensagem" => "O item informado não existe ou já foi deletado."];
        } // Error >> O item informado não existe ou já foi deletado.

        // Retorno
        $this->api($dados);

    } // End >> fun::delete()

} // End >> Class::Usuario