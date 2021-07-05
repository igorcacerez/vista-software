<?php

// NameSpace
namespace Controller\Api;

// Importações
use DuugWork\Helper\SendCurl;
use Helper\Apoio;
use Helper\Seguranca;
use Model\MensalidadeRepasse;


/**
 * Classe responsável por realizar todos os processos
 * da api do contrato.
 *
 * Class Contrato
 * @package Controller\Api
 */
class Contrato extends \DuugWork\Controller
{
    // Objetos
    private $objModelContrato;
    private $objModelLocador;
    private $objModelLocatario;
    private $objModelMensalidadeRespasse;
    private $objHelperSeguranca;

    // Método construtor
    public function __construct()
    {
        // Chama o método construtor
        parent::__construct();

        // Instancia os objetos
        $this->objModelContrato = new \Model\Contrato();
        $this->objModelLocador = new \Model\Locador();
        $this->objModelLocatario = new \Model\Locatario();
        $this->objModelMensalidadeRespasse = new MensalidadeRepasse();
        $this->objHelperSeguranca = new Seguranca();

    } // End >> fun::__construct()


    /**
     * Método responsável por realizar a uma busca de um
     * determinado contrato, que possua o id informado.
     * ------------------------------------------------------------
     * @param $id [Id do contrato]
     * ------------------------------------------------------------
     * @url api/contrato/get/[ID]
     * @method GET
     */
    public function get($id)
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $obj = null; // Contrato Encontrado

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Realiza a busca do contrato
        $obj = $this->objModelContrato
            ->get(["id_contrato" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se encontrou
        if(!empty($obj))
        {
            // Busca o locador
            $obj->locador = $this->objModelLocador
                ->get(["id_locador" => $obj->id_locador])
                ->fetch(\PDO::FETCH_OBJ);

            // Busca o locatário
            $obj->locatario = $this->objModelLocatario
                ->get(["id_locatario" => $obj->id_locatario])
                ->fetch(\PDO::FETCH_OBJ);

            // Busca todas as mensalidades
            $obj->mensalidades = $this->objModelMensalidadeRespasse
                ->get(["id_contrato" => $obj->id_contrato], "id_contrato ASC")
                ->fetchAll(\PDO::FETCH_OBJ);
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
     * Método responsável por realizar a uma busca de contratos
     * podendo filtrar por condições, ordenar e utilziar limites
     * de exibição por página.
     * ------------------------------------------------------------
     * @url api/contrato/get
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
        if($limite > 0)
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
     * Método responsável por:
     *
     *  - Receber as informações ncessárias para cadastro de um
     *  novo cadastro no banco.
     *  - Verificar se todos os campos obrigatórios foram informados.
     *  - Validar se o Locatario e Locador são registros validos
     *  - Validar se as dadas de inicio e fim são corelativas.
     *  - Buscar na Api do Vista CRM as informaçoes do imovel.
     *  - Validar se houve retorno sobre o imovel pela api.
     *  - Inserir o contrato com os dados coletados no banco de dados.
     *  - Gerar as 12 mensalidades para o contrato adicionado.
     *
     * ----------------------------------------------------------------------
     * @url api/contrato/insert
     * @method POST
     */
    public function insert()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $post = null; // Recupera os dados enviados via POST
        $salva = null; // Array de inserção no banco de dados
        $imovel = null; // Objeto do imovel recuperado pela API do vista CRM
        $obj = null; // Objeto inserido
        $informacoesBuscaApi  = null; // Array de informações que devem ser retornadas pela Api
        $url = null; // Url de busca na Api do Vista

        // Objetos
        $objHelperSendCurl = null; // Objeto de apoio que facilita requisições Curl

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Recupera os dados post
        $post = $_POST;

        // Limpa o id do imovel
        $post["imovel"] = (!empty($post["imovel"]) ? preg_replace('/[^0-9]/', '', $post["imovel"]) : null);

        // Verifica se informou os dados obrigatórios
        if(!empty($post["imovel"])
            && !empty($post["id_locatario"])
            && !empty($post["id_locador"])
            && !empty($post["taxaAdministracao"])
            && !empty($post["dataInicio"])
            && !empty($post["dataFim"])
            && !empty($post["valorAluguel"]))
        {
            // Verifica se a data de inicio é maior que a data atual
            if($post["dataInicio"] >= date("Y-m-d"))
            {
                // Verifica se a data de fim é maior que a data de inicio
                if($post["dataFim"] > $post["dataInicio"])
                {
                    // Verifica se o locatario informado existe
                    if($this->objModelLocatario->get(["id_locatario" => $post["id_locatario"]])->rowCount() > 0)
                    {
                        // Verifica se o locador informado existe
                        if($this->objModelLocador->get(["id_locador" => $post["id_locador"]])->rowCount() > 0)
                        {
                            // Instancia o objeto de requisição
                            $objHelperSend = new SendCurl();

                            // Informações a ser retornadas pelo
                            $informacoesBuscaApi = [
                                "fields" => [
                                    "Codigo", "Bairro", "Cidade", "Endereco", "Numero", "CEP", "UF"
                                ]
                            ];

                            // Configura a url
                            $url = URL_API . "imoveis/detalhes?key=" . TOKEN_VISTA;
                            $url .= "&pesquisa=" . json_encode($informacoesBuscaApi);
                            $url .= "&imovel=" . $post["imovel"];

                            // Configura o cabeçalho da requisição
                            $objHelperSend->setHeader("Accept", "application/json"); // Aceita resposta em Json

                            // Realiza a requisição na Api do Vista CRM
                            $imovel = $objHelperSend->resquest($url, null, "GET", true);

                            // Verifica se a requisição deu certo e retornou os dados do imovel
                            if(empty($imovel->status) && !empty($imovel->Codigo))
                            {
                                // Verifica se já possui um contrato com o imovel
                                $aux = $this->objModelContrato
                                    ->get(["dataFim >" => $post["dataInicio"], "imovel" => $post["imovel"]])
                                    ->rowCount();

                                // Faz a verificação se encontrou algo
                                if($aux == 0)
                                {
                                    // Configura a taxa de administracao para o padrão americano de pontuação
                                    $post["taxaAdministracao"] = str_replace(".","",$post["taxaAdministracao"]);
                                    $post["taxaAdministracao"] = str_replace(",",".",$post["taxaAdministracao"]);

                                    // Configura o valor do aluguel de administracao para o padrão americano de pontuação
                                    $post["valorAluguel"] = str_replace(".","",$post["valorAluguel"]);
                                    $post["valorAluguel"] = str_replace(",",".",$post["valorAluguel"]);

                                    // Verifica se possui iptu
                                    if(!empty($post["valorIptu"]))
                                    {
                                        // Configura para o padrão americano de pontuação
                                        $post["valorIptu"] = str_replace(".","",$post["valorIptu"]);
                                        $post["valorIptu"] = str_replace(",",".",$post["valorIptu"]);
                                    }
                                    else
                                    {
                                        // Força ser 0
                                        $post["valorIptu"] = 0;
                                    }

                                    // Verifica se possui condomino
                                    if(!empty($post["valorCondominio"]))
                                    {
                                        // Configura para o padrão americano de pontuação
                                        $post["valorCondominio"] = str_replace(".","",$post["valorIptu"]);
                                        $post["valorCondominio"] = str_replace(",",".",$post["valorIptu"]);
                                    }
                                    else
                                    {
                                        // Força ser 0
                                        $post["valorCondominio"] = 0;
                                    }

                                    // Configura a taxa de administracao para o padrão americano de pontuação
                                    $post["taxaAdministracao"] = str_replace(".","",$post["taxaAdministracao"]);
                                    $post["taxaAdministracao"] = str_replace(",",".",$post["taxaAdministracao"]);

                                    // Configura o array de inserção no banco de dados
                                    $salva = [
                                        "id_locatario" => $post["id_locatario"],
                                        "id_locador" => $post["id_locador"],
                                        "imovel" => $post["imovel"],
                                        "taxaAdministracao" => $post["taxaAdministracao"],
                                        "cep" => preg_replace('/[^0-9]/', '', $imovel->CEP),
                                        "cidade" => $imovel->Cidade,
                                        "estado" => $imovel->UF,
                                        "Endereco" => $imovel->Endereco,
                                        "Numero" => $imovel->Numero,
                                        "dataInicio" => $post["dataInicio"],
                                        "dataFim" => $post["dataFim"],
                                        "valorAluguel" => $post["valorAluguel"],
                                        "valorCondominio" => $post["valorCondominio"],
                                        "valorIptu" => $post["valorIptu"],
                                    ];

                                    // Insere o contrato no banco de dados
                                    $obj = $this->objModelContrato
                                        ->insert($salva);

                                    // Verifica se inseriu corretamente
                                    if(!empty($obj))
                                    {
                                        // Busca o objeto recem inserido no banco de dados
                                        $obj = $this->objModelContrato
                                            ->get(["id_contrato" => $obj])
                                            ->fetch(\PDO::FETCH_OBJ);

                                        try
                                        {
                                            // Gera as mensalidade
                                            $this->gerarMensalidades($obj);

                                            // Array de retorno
                                            $dados = [
                                                "tipo" => true, // Informa que deu certo
                                                "code" => 200, // codigo http
                                                "mensagem" => "Contrato adicionado com sucesso.", // Mensagem de exibição
                                                "objeto" => $obj // Retorna o objeto adicionado
                                            ];
                                        }
                                        catch (\Exception $e)
                                        {
                                            // Deleta o contrato adicionado
                                            $this->objModelContrato
                                                ->delete(["id_contrato" => $obj->id_contrato]);

                                            // Informa do erro ocorrido
                                            $dados = ["mensagem" => $e->getMessage()];
                                        } // Error >> Ocorreu algum erro ao gerar as mensalidades.
                                    }
                                    else
                                    {
                                        // Msg
                                        $dados = ["mensagem" => "Ocorreu um erro ao inserir o contrato."];
                                    } // Error >> Ocorreu um erro ao inserir o contrato.
                                }
                                else
                                {
                                    // Msg
                                    $dados = ["mensagem" => "O imovel já possui um contrato no período informado."];
                                } // Error >> O imovel já possui um contrato no período informado.
                            }
                            else
                            {
                                // Msg
                                $dados = ["mensagem" => $imovel->message];
                            } // Error >> Informa o erro na requisição
                        }
                        else
                        {
                            // Msg
                            $dados = ["mensagem" => "O locador informado não existe."];
                        } // Error >> O locador informado não existe.
                    }
                    else
                    {
                        // Msg
                        $dados = ["mensagem" => "O locatário informado não existe."];
                    } // Error >> O locatário informado não existe.
                }
                else
                {
                    // Msg
                    $dados = ["mensagem" => "A data de encerramento de um contrato deve ser maior que sua data de início"];
                } // Error >> A data de encerramento de um contrato deve ser maior que sua data de início
            }
            else
            {
                // Msg
                $dados = ["mensagem" => "A data de inicio de um contrato deve ser maior que a data atual."];
            } // Error >> A data de inicio de um contrato deve ser maior que a data atual.
        }
        else
        {
            // Msg
            $dados = ["mensagem" => "Campos obrigatórios não foram informados."];

        } // Error >> Campos obrigatórios não foram informados.

        // Retorno
        $this->api($dados);

    } // End >> fun::insert()



    /**
     * Método responsável por receber o id de um determinadado
     * contrato e deletar o mesmo do banco de dados.
     * ------------------------------------------------------------
     * @param $id [Id do contrato a ser deletado]
     * ------------------------------------------------------------
     * @url api/contrato/delete/[ID]
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
        $obj = $this->objModelContrato
            ->get(["id_contrato" => $id])
            ->fetch(\PDO::FETCH_OBJ);

        // Verifica se encontrou o objeto informado
        if(!empty($obj))
        {
            // Deleta as mensalidades
            if($this->objModelMensalidadeRespasse->delete(["id_contrato" => $id]) != false)
            {
                // Deleta o objeto
                if($this->objModelContrato->delete(["id_contrato" => $id]) != false)
                {
                    // Array de retorno
                    $dados = [
                        "tipo" => true, // Informa que deu certo
                        "code" => 200, // codigo http
                        "mensagem" => "Contrato deletado com sucesso.", // Mensagem de exibição
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
                $dados = ["mensagem" => "Ocorreu um erro ao deletar as mensalidades."];
            } // Error >> Ocorreu um erro ao deletar as mensalidades.
        }
        else
        {
            // Msg
            $dados = ["mensagem" => "O item informado não existe ou já foi deletado."];
        } // Error >> O item informado não existe ou já foi deletado.

        // Retorno
        $this->api($dados);

    } // End >> fun::delete()




    /**
     * Método responsável por realizar todos os calculos necessário para
     * gerar as 12 mensalidades de um contrato.
     *--------------------------------------------------------------------
     * Regra:
     *
     * O vencimento é sempre dia 01 do mês, portanto se a data de início
     * do contrato não for dia 01 o primeiro aluguel será proporcional aos
     * dias utilizados.
     * --------------------------------------------------------------------
     * @param $contrato
     * @return bool
     * @throws \Exception
     */
    private function gerarMensalidades($contrato)
    {
        // Variaveis
        $salva = null; // Array de inserção no banco de dados
        $valorTotal = null; // Valor total da mensalidade
        $valorRepasse = null; // Valor a ser repassado ao locador
        $dataMensalidade = null; // Data de vencimento da mensalidade
        $valorTotalPorDia = null; // Valor total por dia
        $valorRepassePorDia = null; // Valor do repasse por dia
        $diasMes = null; // Numero de dias que possui o mês (30, 31, 28 ou 29)
        $diasUtilizados = null; // Total de dias que o cliente utilizou o imovel no mes
        $locador = null; // Armazena o objeto do locador do contrato
        $obj = null; // Armazena o objeto mensalidadeRepasse adicionado no banco

        // Busca o locador do contrato
        $locador = $this->objModelLocador
            ->get(["id_locador" => $contrato->id_locador])
            ->fetch(\PDO::FETCH_OBJ);

        // Armazena o valor total
        $valorTotal = ($contrato->valorAluguel + $contrato->valorIptu + $contrato->valorCondominio);

        // Realiza o calculo do repasse
        // Sera repassa o valor do IPTU + O valor do aluguel - a taxa administrativa (porcentagem)
        $valorRepasse = $contrato->valorIptu;
        $valorRepasse += ($contrato->valorAluguel - ($contrato->valorAluguel * ($contrato->taxaAdministracao / 100)));

        // Data da primeira mensalidade
        $dataMensalidade = date("Y-m", strtotime("+1 month", strtotime($contrato->dataInicio)));
        $dataMensalidade .= "-01";

        // Verifica se o dia inicial do contrato é maior que 1
        if(date("d",strtotime($contrato->dataInicio)) > 1)
        {
            // Utiliza o objeto de Apoio
            $objHelperApoio = new Apoio();

            // Quantos dias possui o mês de inicio do contrato
            $diasMes = cal_days_in_month(
                CAL_GREGORIAN,
                date("m",strtotime($contrato->dataInicio)),
                date("Y",strtotime($contrato->dataInicio))
            );

            // Realiza o calculo de cada item por dia
            $valorTotalPorDia = ($valorTotal / $diasMes);
            $valorRepassePorDia = ($valorRepasse / $diasMes);

            // Realiza o calculo de quantos dias o cliente utilizou
            $diasUtilizados = $objHelperApoio->diasDatas($contrato->dataInicio, $dataMensalidade);

            // Realiza o calculo relativo aos dias utilizados do valor total e repasse
            // Adiciona os itens no array de inserção
            $salva = [
                "valorTotal" => ($valorTotalPorDia * $diasUtilizados),
                "valorRepasse" => ($valorRepassePorDia * $diasUtilizados)
            ];
        }
        else
        {
              // Inicia o array de inserção
            $salva = [
                "valorTotal" => $valorTotal,
                "valorRepasse" => $valorRepasse
            ];

        } // O contrato inicia no dia 1, então possui a cobrança completa

        // Adiciona o id do contrato no array de inserção
        $salva["id_contrato"] = $contrato->id_contrato;

        // Força os valores terem no maximo 2 digitos após a virgula
        $salva["valorTotal"] = number_format($salva["valorTotal"], 2, ".", "");
        $salva["valorRepasse"] = number_format($salva["valorRepasse"], 2, ".", "");

        // Percorre o numero de mensalidades que deve ser gerado
        for ($i = 0; $i <= 11; $i++)
        {
            // Verifica se é diferente da primeira mensalidade
            if($i > 0)
            {
                // Realiza a soma para o vencimento
                $dataMensalidade = date("Y-m-d", strtotime("+1 month", strtotime($dataMensalidade)));

                // Adiciona o valor
                // Força os valores terem no maximo 2 digitos após a virgula
                $salva["valorTotal"] = number_format($valorTotal, 2, ".", "");
                $salva["valorRepasse"] = number_format($valorRepasse, 2, ".", "");
            }


            // Adiciona ao array de inserção as datas de vencimento e repasse
            $salva["dataVencimento"] = $dataMensalidade;
            $salva["dataRepasse"] = date("Y-m-d", strtotime("+{$locador->diasRepasse} days", strtotime($dataMensalidade)));

            // Insere no banco de dados
            $obj = $this->objModelMensalidadeRespasse
                ->insert($salva);

            // Verifica se deu algum erro
            if($obj == false)
            {
                // Deleta todas as mensalidades geradas
                $this->objModelMensalidadeRespasse
                    ->delete(["id_contrato" => $contrato->id_contrato]);

                // Informa do erro
                throw new \Exception("Ocorreu um erro ao gerar as mensalidades.");
            }
        }

        // Retorno de sucesso
        return true;

    } // End >> fun::gerarMensalidades()


} // End >> Class::Contrato