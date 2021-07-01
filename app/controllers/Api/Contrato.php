<?php

// NameSpace
namespace Controller\Api;

// Importações
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


    public function insert()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $usuario = null; // Usuario logado
        $post = null; // Recupera os dados enviados via POST
        $salva = null; // Array de inserção no banco de dados
        $imovel = null; // Objeto do imovel recuperado pela API do vista CRM
        $obj = null; // Objeto inserido

        // Seguranca
        $usuario = $this->objHelperSeguranca->security();

        // Recupera os dados post
        $post = $_POST;

        // Verifica se informou os dados obrigatórios
        if(!empty($post["imovel"])
            && !empty($post["id_locatario"])
            && !empty($post["id_locador"])
            && !empty($post["taxaAdministracao"]))
        {
        }

    } // End >> fun::insert()


} // End >> Class::Contrato