<?php

// NameSpace
namespace Controller\Api;

// Importações
use DuugWork\Controller;
use Helper\Seguranca;
use Model\MensalidadeRepasse;

/**
 * Classe responsável por realizar todos os processos
 * da api da mensalidade.
 *
 * Class Mensalidade
 * @package Controller\Api
 */
class Mensalidade extends Controller
{
    // Objetos
    private $objModelContrato;
    private $objModelMensalidadeRepasse;
    private $objModelLocatario;
    private $objModelLocador;
    private $objHelperSeguranca;

    // Metodo construtor
    public function __construct()
    {
        // Chama o construtor pai
        parent::__construct();

        // Instancia os objetos
        $this->objModelContrato = new \Model\Contrato();
        $this->objModelMensalidadeRepasse = new MensalidadeRepasse();
        $this->objModelLocatario = new \Model\Locatario();
        $this->objModelLocador = new \Model\Locador();
        $this->objHelperSeguranca = new Seguranca();

        // Realiza a seguranca para todos os métodos
        $this->objHelperSeguranca->security();

    } // End >> fun::__construct()


    /**
     * Método responsável por realizar a uma busca de uma
     * determinada mensalidade, que possua o id informado.
     * ------------------------------------------------------------
     * @param $id [Id da mensalidade]
     * ------------------------------------------------------------
     * @url api/mensalidade/get/[ID]
     * @method GET
     */
    public function get($id)
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $obj = null; // Contrato Encontrado

        // Realiza a busca do contrato
        $obj = $this->objModelContrato
            ->get(["id_contrato" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se encontrou
        if(!empty($obj))
        {
            // Busca o contrato
            $obj->contrato = $this->objModelContrato
                ->get(["id_contrato" => $obj->contrato])
                ->fetch(\PDO::FETCH_OBJ);

            // Busca o locador
            $obj->contrato->locador = $this->objModelLocador
                ->get(["id_locador" => $obj->contrato->id_locador])
                ->fetch(\PDO::FETCH_OBJ);

            // Busca o locatário
            $obj->contrato->locatario = $this->objModelLocatario
                ->get(["id_locatario" => $obj->contrato->id_locatario])
                ->fetch(\PDO::FETCH_OBJ);
        }

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
     * Método responsável por realizar a uma busca de mensalidade
     * podendo filtrar por condições, ordenar e utilziar limites
     * de exibição por página.
     * ------------------------------------------------------------
     * @url api/mensalidade/get
     * @method GET
     */
    public function getAll()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $obj = null; // Locadores Encontrados
        $ordem = null; // Ordem de exibição (ORDER BY)
        $where = null; // Condições para busca
        $pag = null; // Pagina atual para listagem
        $limite = null; // Numero limite de exibições por página
        $inicio = null; // Item inicial
        $orderBy = null; // Item pelo qual deve ordenar
        $orderTipo = null;  // Tipo da ordenação (ASC ou DESC)
        $numPag = 1; // Numero total de paginas existente para a busca
        $limiteConfig = null; // Configuração do limite

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
        $obj = $this->objModelContrato
            ->get($where, $ordem, $limiteConfig)
            ->fetchAll(\PDO::FETCH_OBJ);

        // Total de resultados encontrados sem o
        // limite
        $total = $this->objModelContrato
            ->get($where)
            ->rowCount();

        // Verifica se está retornando algo
        if($total > 0)
        {
            // Percorre os objetos
            foreach ($obj as $contrato)
            {
                // Busca o locador
                $contrato->locador = $this->objModelLocador
                    ->get(["id_locador" => $contrato->id_locador])
                    ->fetch(\PDO::FETCH_OBJ);

                // Busca o locatário
                $contrato->locatario = $this->objModelLocatario
                    ->get(["id_locatario" => $contrato->id_locatario])
                    ->fetch(\PDO::FETCH_OBJ);
            }
        }

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
     * Método responsável por retornar o calculo do lucro da
     * imobiliaria com as mensalidades. O calculo pode ser
     * do valor total esperado (ainda não pago) ou do valor
     * total recebido (já pago pelos locatarios)
     * ------------------------------------------------------------
     * @param $tipo [Recebido ou Esperado]
     * ------------------------------------------------------------
     * @url api/mensalidade/get
     * @method GET
     */
    public function valorDeLucro($tipo)
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $obj = null; // Armazena o objeto retornado da model
        $lucro = null; // Armazena o valor de lucro
        $where = null; // Condição de busca

        // Verifica o tipo do lucro
        if($tipo == "recebido")
        {
            // Condição
            $where = ["pago" => true];
        }

        // Realiza a busca no banco de dados
        $obj = $this->objModelMensalidadeRepasse
            ->get(
                $where,
                null,
                null,
                "SUM(valorTotal) as somatoriaValorTotal, SUM(valorRepasse) as somatoriaValorRepasse"
            )
            ->fetch(\PDO::FETCH_OBJ);

        // Realiza o calculo do lucro
        // Valor total - o valor de repasse
        $lucro = $obj->somatoriaValorTotal - $obj->somatoriaValorRepasse;

        // Converte o valor para apenas 2 casas decimais
        // No padrão americado
        $lucro = number_format($lucro, 2, ".", "");

        // Array de retorno
        $dados = [
            "tipo" => true, // Informa que deu certo
            "code" => 200, // codigo http
            "mensagem" => null, // Mensagem de exibição
            "objeto" => $lucro // Retorna o objeto adicionado
        ];

        // Retorna na view
        $this->api($dados);

    } // End >> fun::lucroRecebido()


    /**
     * Método responsável por verificar qual o status atual
     * de um item e alterar para o seu status oposto. É necessário
     * realizar as válidações antes de alterar.
     * ------------------------------------------------------------
     * @param $tipo [Mensalidade ou Repasse]
     * @param $id [Id da mensalidade ao ser alterada]
     * ------------------------------------------------------------
     * @url api/mensalidade/update/[TIPO]/[ID]
     * @method PUT
     */
    public function update($tipo, $id)
    {
        // Variaveis
        $dados = null; // Array de retorno
        $obj = null; // objeto atual a ser alterado
        $objAlterado = null; // objeto com as informações já alteradas
        $usuario = null; // Usuario logado
        $altera = null; // Array com os itens a ser alterados
        $msg = null; // Mensagem personalizada em caso de sucesso.

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Verifica se o tipo é aceito
        if($tipo == "mensalidade" || $tipo == "repasse")
        {
            // Busca o objeto
            $obj = $this->objModelMensalidadeRepasse
                ->get(["id_mensalidadeRepasse" => $id])
                ->fetch(\PDO::FETCH_OBJ);

            // Verifica se foi encontrado
            if(!empty($obj))
            {
                // Verifica se vai ativar um repasse --------------------
                if($obj->repasse == false && $tipo == "repasse")
                {
                    // Verifica se ainda não foi pago
                    if($obj->pago == false)
                    {
                        // Encerra e informa que não pode realizar um
                        // repasse caso a mensalidade não seja paga
                        $this->api(["mensagem" => "Para realizar um repasse primeiro a mensalidade deve ser paga."]);
                    }
                }
                // ------------------------------------------------------

                // Verifica se vai desativar um pagamento ----------------
                if($obj->pago == true && $tipo == "mensalidade")
                {
                    // Verifica se o repasse foi feito
                    if($obj->repasse == true)
                    {
                        // Encerra e informa que não pode cancelar um pagamento de
                        // mensalidado caso o repasse tenha cido efetuado.
                        $this->api(["mensagem" => "Não é possível cancelar um pagamento de mensalidade, caso o repasse tenha sido efetuado."]);
                    }
                }
                // -------------------------------------------------------

                // Verifica qual campo que vai ser alterado
                if($tipo == "repasse")
                {
                    // Configura o array de alteração
                    $altera["repasse"] = ($obj->repasse == true) ? false : true;

                    // Mensagem
                    $msg = "Repasse" . (($obj->repasse == true) ? " cancelado " : " realizado ") . "com sucesso.";
                }
                else
                {
                    // Configura o array de alteração
                    $altera["pago"] = ($obj->pago == true) ? false : true;

                    // Msg
                    $msg = "Pagamento da mensalidade foi" . (($obj->pago == true) ? " cancelado " : " realizado ") . "com sucesso";

                } // Nesse caso está alterando a mensalidade

                // Realiza a alteração no banco de dados
                // E verifica se ocorreu tudo certo
                if($this->objModelMensalidadeRepasse->update($altera, ["id_mensalidadeRepasse" => $id]) != false)
                {
                    // Busca o item recem alterado
                    $objAlterado = $this->objModelMensalidadeRepasse
                        ->get(["id_mensalidadeRepasse" => $id])
                        ->fetch(\PDO::FETCH_OBJ);

                    // Retorno de sucesso
                    $dados = [
                        "tipo" => true, // Informa que deu certo
                        "code" => 200, // codigo http
                        "mensagem" => $msg, // Mensagem de exibição
                        "objeto" => [
                            "antes" => $obj, // Objeto antes de ser alterado
                            "atual" => $objAlterado // Objeto com as novas informações
                        ]
                    ];

                }
                else
                {
                    // Msg
                    $dados = ["mensagem" => "Ocorreu um erro ao alterar status de pagamento."];
                } // Error >> Ocorreu um erro ao alterar status de pagamento.
            }
            else
            {
                // Msg
                $dados = ["mensagem" => "O item informado não existe."];
            } // Error >> O item informado não existe.
        }
        else
        {
            // Msg
            $dados = ["mensagem" => "O tipo informado não é válidado."];
        } // Error >> O tipo informado não é válidado.

        // Retorno
        $this->api($dados);

    } // End >> fun::update()

} // End >> Class::Mensalidade