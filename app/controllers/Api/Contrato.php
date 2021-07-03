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
 * da api do locador.
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

        // Verifica se informou os dados obrigatórios
        if(!empty($post["imovel"])
            && !empty($post["id_locatario"])
            && !empty($post["id_locador"])
            && !empty($post["taxaAdministracao"])
            && !empty($post["dataInicio"])
            && !empty($post["dataFim"]))
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
                                    "Codigo", "ValorLocacao", "ValorIptu", "ValorCondominio",
                                    "Bairro", "Cidade", "Endereco", "Numero", "CEP", "UF"
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
                                    "dataFim" => $post["dataFim"]
                                ];

                                // Adiona os valores, os que não forem informados deixa como zero
                                $salva["valorAluguel"] = (!empty($imovel->ValorLocacao) ? $imovel->ValorLocacao : 0);
                                $salva["valorIptu"] = (!empty($imovel->ValorIptu) ? $imovel->ValorIptu : 0);
                                $salva["valorCondominio"] = (!empty($imovel->ValorCondominio) ? $imovel->ValorCondominio : 0);

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
        $valorRepasse += ($contrato->valorAluguel - ($contrato->valorAluguel * ($contrato->taxaAdministrativa / 100)));

        // Data da primeira mensalidade
        $dataMensalidade = date("Y-m-") . "01";

        // Verifica se o dia inicial do contrato é maior que 1
        if(date("d",strtotime($dataMensalidade)) > 1)
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

        // Percorre o numero de mensalidades que deve ser gerado
        for ($i = 0; $i <= 11; $i++)
        {
            // Realiza a soma para o vencimento
            $dataMensalidade = date("Y-m-d", strtotime("+{$i} days", strtotime($dataMensalidade)));

            // Adiciona ao array de inserção as datas de vencimento e repasse
            $salva["dataVencimento"] = $dataMensalidade;
            $salva["dataRepasse"] = date("Y-m-d", strtotime("+{$locador->diasRepasse} days"));

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