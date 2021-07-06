<?php

// NameSpace
namespace Controller\Api;

// Importações
use DuugWork\Helper\SendCurl;

/**
 * Classe responsável por conter os métodos de comunição
 * com a api do vista.
 *
 * Class Imovel
 * @package Controller\Api
 */
class Imovel extends \DuugWork\Controller
{
    // Objetos
    private $objHelperSendCurl;

    // Método construtor
    public function __construct()
    {
        // Chama o método pai
        parent::__construct();

        // Instancia os objetos
        $this->objHelperSendCurl = new SendCurl();

        // Seta o json no header
        $this->objHelperSendCurl->setHeader("Accept","application/json");

    } // End >> fun::__construct()


    /**
     * Método responsável por listar todos os imoveis atraves de uma
     * busca no pela api do vista. O retorno deve ser configurado
     * para facilitar na view.
     * ----------------------------------------------------------------
     * @url api/imovel/get
     * @method GET
     */
    public function getAll($tipo)
    {
        // Variaveis
        $dados = null;
        $imoveis = null;
        $icones = null;
        $aux = null;

        // Pagina
        $pag = (!empty($_GET["pag"]) ? $_GET["pag"] : 1);

        // Informações a ser retornadas pelo
        $informacoesBuscaApi = [
            "fields" => [
                "Codigo", "Categoria", "Status", "TotalBanheiros",
                "FotoDestaque", "Bairro", "Cidade", "UF", "Dormitorios", "Suites",
                "Vagas", "AreaTotal", "AreaPrivativa", "ValorVenda", "ValorLocacao"
            ],
            "filter" => $this->configuraFiltro($tipo, $_POST),
            "order" => [
                "Codigo" => "desc"
            ],
            "paginacao" => [
                "pagina" => $pag,
                "quantidade" => 9,
            ]
        ];

        // Configura a url
        $url = URL_API . "imoveis/listar?key=" . TOKEN_VISTA;
        $url .= "&pesquisa=" . json_encode($informacoesBuscaApi);
        $url .= "&showtotal=1";

        // Verifica se informou o código
        if(!empty($_POST["codigo"]))
        {
            $url .= "&imovel=" . $_POST["codigo"];
        }

        // Realiza a requisição na Api do Vista CRM
        $imoveis = $this->objHelperSendCurl->resquest($url, null, "GET", true);

        // Configura o dados
        $dados = [
            "tipo" => true,
            "code" => 200
        ];

        // Verifica se retornou algo
        if(!empty($imoveis->total))
        {
            // Percorre a lista
            foreach ($imoveis as $imovel)
            {
                // Padroniza os valores
                $ValorVenda = (!empty($imovel->ValorVenda) ? "R$" . number_format($imovel->ValorVenda, 2, ",",".") : 0);
                $ValorLocacao = (!empty($imovel->ValorLocacao) ? "R$" . number_format($imovel->ValorLocacao, 2, ",",".") : 0);

                // Verifica se é um imovel
                if(!empty($imovel->Codigo))
                {
                    // Armazena em um array
                    $objRetorno = [
                        "codigo" => $imovel->Codigo,
                        "status" => $imovel->Status,
                        "imagem" => (!empty($imovel->FotoDestaque) ? $imovel->FotoDestaque : BASE_URL . "assets/theme/site/img/imagem.png"),
                        "cidade" => $imovel->Cidade . ", " . $imovel->Bairro . " - " . $imovel->UF,
                        "bairro" => $imovel->Bairro,
                        "categoria" => $imovel->Categoria,
                        "valor" => (($tipo == "comprar") ? $ValorVenda : $ValorLocacao),
                        "itens" => []
                    ];


                    // Veririfica se possui dormitórios
                    if(!empty($imovel->Dormitorios))
                    {
                        $objRetorno["itens"][] = [
                            "titulo" => "Domitórios",
                            "valor" => $imovel->Dormitorios,
                            "icone" => "fa fa-bed"
                        ];
                    }

                    // Veririfica se possui Garagem
                    if(!empty($imovel->Vagas))
                    {
                        $objRetorno["itens"][] = [
                            "titulo" => "Vagas",
                            "valor" => $imovel->Dormitorios,
                            "icone" => "fas fa-car"
                        ];
                    }

                    // Veririfica se possui Garagem
                    if(!empty($imovel->Suites))
                    {
                        $objRetorno["itens"][] = [
                            "titulo" => "Suites",
                            "valor" => $imovel->Suites,
                            "icone" => "fa fa-bed"
                        ];
                    }

                    // Veririfica se possui Banheiro
                    if(!empty($imovel->Suites))
                    {
                        $objRetorno["itens"][] = [
                            "titulo" => "Banheiros",
                            "valor" => $imovel->TotalBanheiros,
                            "icone" => "fas fa-shower"
                        ];
                    }

                    // Veririfica se possui Area total
                    if(!empty($imovel->AreaTotal) && count($objRetorno["itens"]) < 4)
                    {
                        $objRetorno["itens"][] = [
                            "titulo" => "Total",
                            "valor" => $imovel->AreaTotal,
                            "icone" => "fas fa-arrows-alt"
                        ];
                    }

                    // Veririfica se possui Area Privativa
                    if(!empty($imovel->AreaPrivativa) && count($objRetorno["itens"]) < 4)
                    {
                        $objRetorno["itens"][] = [
                            "titulo" => "Privativos",
                            "valor" => $imovel->AreaPrivativa,
                            "icone" => "fas fa-arrows-alt"
                        ];
                    }


                    // Adiciona ao auxiliar
                    $aux[] = $objRetorno;
                }
            }

            // Adiciona as informações no dados
            $dados["objeto"] = [
                "itens" => $aux,
                "total" => $imoveis->total,
                "numPaginas" => $imoveis->paginas,
                "pagina" => $imoveis->pagina
            ];
        }


        // Retorno
        $this->api($dados);

    } // End >> fun::getAll()


    /**
     * Método responsável por realizar a configuração dos
     * paramentos em caso de filtro.
     * -----------------------------------------------------
     * @param $tipo
     * @param $post
     * @return array|\string[][]
     */
    private function configuraFiltro($tipo, $post)
    {
        // Variaveis
        $retorno = null;

        // Verifica se não vai filtrar por codigo
        if(empty($post["codigo"]))
        {
            // Inicia com o principal
            $retorno = ["Codigo" => ["!=", ""]];

            // Configura o status
            $retorno["Status"] = (($tipo == "comprar") ? "VENDA" : "ALUGUEL");

            // Verifica se possui filtro por categoria
            if(!empty($post["categoria"]))
            {
                // Adiciona
                $retorno["Categoria"] = $post["categoria"];
            }

            // Verifica se possui filtro por bairro
            if(!empty($post["bairro"]))
            {
                // Adiciona
                $retorno["Bairro"] = $post["bairro"];
            }

            // Verifica se possui filtro por dormitorios
            if(!empty($post["dormitorios"]))
            {
                // Adiciona
                $retorno["Dormitorios"] = $post["dormitorios"];
            }

            // Verifica se possui filtro por faixa de preço
            if(!empty($post["valorVenda"]) && $tipo == "comprar")
            {
                // Separa o inicial do final
                $valor = explode("-", $post["valorVenda"]);

                // Reliza o filtro
                $retorno["ValorVenda"] = $valor;
            }

            // Verifica se possui filtro por faixa de preço
            if(!empty($post["valorAluguel"]) && $tipo == "alugar")
            {
                // Separa o inicial do final
                $valor = explode("-", $post["valorAluguel"]);

                // Reliza o filtro
                $retorno["ValorLocacao"] = $valor;
            }
        }

        // Retorno
        return $retorno;
    } // End >> fun::configuraFiltro()

} // End >> Class::Imovel