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




} // End >> Class::Imovel