<?php

// NameSpace
namespace Controller\Api;


use DuugWork\Helper\SendCurl;
use Helper\Seguranca;

class Imovel extends \DuugWork\Controller
{
    // Objetos
    private $objHelperSendCurl;
    private $objHelperSeguranca;

    // Método construtor
    public function __construct()
    {
        // Chama o método pai
        parent::__construct();

        // Instancia os objetos
        $this->objHelperSeguranca = new Seguranca();
        $this->objHelperSendCurl = new SendCurl();

        // Seguranca
        $this->objHelperSeguranca->security();

        // Seta o json no header
        $this->objHelperSendCurl->setHeader("Accept","application/json");

    } // End >> fun::__construct()


    public function getAll()
    {
        // Variaveis
        $dados = null;
        $imoveis = null;
        $icones = null;

        // Informações a ser retornadas pelo
        $informacoesBuscaApi = [
            "fields" => [
                "Codigo", "Categoria", "Status", "TipoImovel", "TotalBanheiros",
                "FotoDestaque", "Bairro", "Cidade", "UF", "Dormitorios", "Suites",
                "Vagas", "AreaTotal", "AreaPrivativa", "ValorVenda", "ValorLocacao"
            ],
            "filter" => [
                "Codigo" => ["!=", ""]
            ],
            "paginacao" => [
                "pagina" => 1,
                "quantidade" => 20,
            ]
        ];

        // Configura a url
        $url = URL_API . "imoveis/listar?key=" . TOKEN_VISTA;
        $url .= "&pesquisa=" . json_encode($informacoesBuscaApi);
        $url .= "&showtotal=1";

        // Realiza a requisição na Api do Vista CRM
        $imoveis = $this->objHelperSendCurl->resquest($url, null, "GET", true);

        // Lista os imoveis
        $this->debug($imoveis);

    } // End >> fun::getAll()


} // End >> Class::Imovel