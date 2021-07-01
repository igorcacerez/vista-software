<?php

// NameSpace
namespace Controller\Api;

// Importaçoes
use DuugWork\Helper\Input;
use Helper\Seguranca;
use Model\Contrato;

/**
 * Classe responsável por realizar todos os processos
 * da api do locador.
 *
 * Class Locador
 * @package Controller\Api
 */
class Locador extends \DuugWork\Controller
{
    // Objetos
    private $objModelLocador;
    private $objModelContrato;
    private $objHelperSeguranca;

    // Método construtor
    public function __construct()
    {
        // Inicializa o método pai
        parent::__construct();

        // Instancia os objetos
        $this->objModelLocador = new \Model\Locador();
        $this->objModelContrato = new Contrato();
        $this->objHelperSeguranca = new Seguranca();

    } // End >> fun::__construct()


    /**
     * Método responsável por realizar a uma busca de um
     * determinado locador, que possua o id informado.
     * ------------------------------------------------------------
     * @param $id [Id do locador]
     * ------------------------------------------------------------
     * @url api/locador/get/[ID]
     * @method GET
     */
    public function get($id)
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $obj = null; // Locador Encontrado

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Realiza a busca
        $obj = $this->objModelLocador
            ->get(["id_locador" => $id])
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
     * Método responsável por realizar a uma busca de locadores
     * podendo filtrar por condições, ordenar e utilziar limites
     * de exibição por página.
     * ------------------------------------------------------------
     * @url api/locador/get
     * @method GET
     */
    public function getAll()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $obj = null; // Locadores Encontrados
        $ordem = null; // Ordem de exibição (ORDER BY)
        $where = null; // Condições para busca
        $pag = null; // Pagina atual para listagem
        $limite = null; // Numero limite de exibições por página
        $inicio = null; // Item inicial
        $orderBy = null; // Item pelo qual deve ordenar
        $orderTipo = null;  // Tipo da ordenação (ASC ou DESC)
        $numPag = null; // Numero total de paginas existente para a busca

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
        $obj = $this->objModelLocador
            ->get($where, $ordem, ($inicio . "," . $limite))
            ->fetchAll(\PDO::FETCH_OBJ);

        // Total de resultados encontrados sem o
        // limite
        $total = $this->objModelLocador
            ->get($where)
            ->rowCount();

        // Realiza o calculo das páginas
        $numPag = ($total > 0) ? ceil($total / $limite) : 1;

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
     * Método responsável por receber as informações do locador via
     * post, realizar verificações de campos obrigatorios.
     * Em caso de sucesso insere o locador no banco de dados e
     * retorna seu registro.
     * ------------------------------------------------------------
     * @url api/locador/insert
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
            && !empty($post["diasRepasse"]))
        {
            // Monta o array de inserção no banco
            $salva = [
                "nome" => $post["nome"],
                "email" => $post["email"],
                "diasRepasse" => preg_replace('/[^0-9]/', '', $post["diasRepasse"]) // Apenas numeros
            ];

            // Insere no banco de dados
            $obj = $this->objModelLocador
                ->insert($salva);

            // Verifica se o usuário foi inserido
            if(!empty($obj))
            {
                // Busca o objeto recem adicionado no banco de dados
                $obj = $this->objModelLocador
                    ->get(["id_locador" => $obj])
                    ->fetch(\PDO::FETCH_OBJ);

                // Array de retorno
                $dados = [
                    "tipo" => true, // Informa que deu certo
                    "code" => 200, // codigo http
                    "mensagem" => "Locador adicionado com sucesso.", // Mensagem de exibição
                    "objeto" => $obj // Retorna o objeto adicionado
                ];
            }
            else
            {
                // Msg
                $dados = ["mensagem" => "Ocorreu um erro ao inserir o locador."];
            } // Error >> Ocorreu um erro ao inserir o locatario.
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
     * alteradas de um determinado locador e alterar as informações
     * no banco de dados.
     * ------------------------------------------------------------
     * @param $id [Id do locador a ser alterado]
     * ------------------------------------------------------------
     * @url api/locador/update/[ID]
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

        // Busca o locador a ser alterado
        $obj = $this->objModelLocador
            ->get(["id_locador" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se o locador foi encontrado
        if(!empty($obj))
        {
            // Recupera os dados put
            $put = $objHelperInput->put();

            // Remove campos que não podem ser alterados
            unset($put["id_locador"]);

            // Realiza a alteração
            if($this->objModelLocador->update($put, ["id_locador" => $id]) != false)
            {
                // Busca o objeto alterado
                $objAlterado = $this->objModelLocador
                    ->get(["id_locador" => $id])
                    ->fetch(\PDO::FETCH_OBJ);

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
     * locador e deletar o mesmo do banco de dados.
     * ------------------------------------------------------------
     * @param $id [Id do locador a ser deletado]
     * ------------------------------------------------------------
     * @url api/locador/delete/[ID]
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
        $obj = $this->objModelLocador
            ->get(["id_locador" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se encontrou o objeto informado
        if(!empty($obj))
        {
            // Verifica se o objeto possui vinculações
            if($this->objModelContrato->get(["id_locador" => $id])->rowCount() == 0)
            {
                // Deleta o objeto
                if($this->objModelLocador->delete(["id_locador" => $id]) != false)
                {
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
                $dados = ["mensagem" => "Impossível deletar. O locador possui contatos vinculados."];
            } // Error >> Impossível deletar. O locador possui contatos vinculados.
        }
        else
        {
            // Msg
            $dados = ["mensagem" => "O item informado não existe ou já foi deletado."];
        } // Error >> O item informado não existe ou já foi deletado.

        // Retorno
        $this->api($dados);

    } // End >> fun::delete()

} // End > Class::Locador