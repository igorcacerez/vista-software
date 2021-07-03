<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 26/03/2019
 * Time: 18:29
 */

namespace Controller;

use DuugWork\Controller as CI_controller;
use DuugWork\Helper\SendCurl;
use Helper\Apoio;


class Principal extends CI_controller
{

    // Método construtor
    function __construct()
    {
        // Carrega o contrutor da classe pai
        parent::__construct();
    }


    public function _index()
    {
        $dados = array(
            'fields'    =>
                array(
                    'Codigo'

                ),
            'filter' => array(
                "ValorLocacao" => array(1000, 10000)
            )
        );

        $key         =  TOKEN_VISTA; //Informe sua chave aqui
        $postFields  =  json_encode( $dados );
        $url         =  'http://sandbox-rest.vistahost.com.br/imoveis/listar?key=' . $key;
        $url        .=  '&pesquisa=' . $postFields;

        $ch = curl_init($url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER , array( 'Accept: application/json' ) );
        $result = curl_exec( $ch );

        $result = json_decode( $result, true );
        print_r( $result );
    }


    public function Aindex()
    {
        // Instancia o objeto de requisição
        $objHelperSend = new SendCurl();

        // Informações a ser retornadas pelo
        $informacoesBuscaApi = [
            "fields" => [
                "ValorLocacao", "ValorIptu", "ValorCondominio",
                "Bairro", "Cidade", "Endereco", "Numero", "CEP", "UF"
            ]
        ];

        // Configura a url
        $url = URL_API . "imoveis/detalhes?key=" . TOKEN_VISTA;
        $url .= "&pesquisa=" . json_encode($informacoesBuscaApi);
        $url .= "&imovel=00010301";

        $objHelperSend->setHeader("Accept", "application/json");

        $resposta = $objHelperSend->resquest($url, null, "GET", true);

        $this->debug($resposta);
    }


    public function listaCampos()
    {
        $key         =  TOKEN_VISTA; //Informe sua chave aqui
        $url         =  'http://sandbox-rest.vistahost.com.br/imoveis/listarcampos?key=' . $key;

        $ch = curl_init($url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER , array( 'Accept: application/json' ) );
        $result = curl_exec( $ch );

        $result = json_decode( $result, true );

        $this->debug($result);
    }

    public function index()
    {
        $a = new Apoio();
//
//        $d = cal_days_in_month(CAL_GREGORIAN, 7, 2021);
//
//        echo $d . "<br>";
//
//        echo ($d - date("d"));
//
//        echo $a->diasDatas(date("Y-m-d"), "2021-08-01");

       // echo date("Y-m-d", strtotime("+0 days"));


        $primeira = "2021-07-02";

       $b = date("Y-m", strtotime("+1 month", strtotime($primeira))) . "-01";

       echo $a->diasDatas("2021-07-10", $b);
    }

} // END::Class Principal