<?php

// NameSpace
namespace Controller\Api;

// Importações
use DuugWork\Controller;
use Helper\Seguranca;
use Model\MensalidadeRepasse;

/**
 * Classe responsável por realizar todos os processos
 * da api para retorno de dados preparados para a exibição
 * em grafico.
 *
 * Class Grafico
 * @package Controller\Api
 */
class Grafico extends Controller
{
    // Objetos
    private $objModelContrato;
    private $objModelMensalidadeRepasse;
    private $objHelperSeguranca;

    /**
     * Armazena os meses do ano de acordo
     * com o numero do mês
     *
     * @var string[]
     */
    private $mesArray = [
        "01" => "Janeiro",
        "02" => "Fevereiro",
        "03" => "Março",
        "04" => "Abril",
        "05" => "Maio",
        "06" => "Junho",
        "07" => "Julho",
        "08" => "Agosto",
        "09" => "Setembro",
        "10" => "Outubro",
        "11" => "Novembro",
        "12" => "Dezembro",
    ];

    // Método construtor
    public function __construct()
    {
        // Chama o pai do construtor
        parent::__construct();

        // Instancia os objetos
        $this->objModelContrato = new \Model\Contrato();
        $this->objModelMensalidadeRepasse = new MensalidadeRepasse();
        $this->objHelperSeguranca = new Seguranca();

        // Seguranca
        $this->objHelperSeguranca->security();

    } // End >> fun::__construct()


    /**
     * Método responsável por retornar os lucros da imabiliaria
     * relativo a meses. De uma forma amigavel para preenchimento
     * em gráficos.
     * ------------------------------------------------------------
     * @url api/grafico/lucross
     * @method GET
     */
    public function lucroImobiliaria()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $obj = null; // Obejto de busca no banco
        $lucros = null; // Array que armazena os lucros buscados
        $labels = null; // Meses relativos aos lucros
        $itenMes = null; // Auxiliar que configura o item de busca pela data
        $data = null; // Configura a data respectiva
        $mes = null; // Numero do mes da data utlizada
        $where = null; // Condição de busca no banco
        $valorLucro = null; // Armazena o lucro retornado e formatado

        // Percorre o for de meses
        for($i = -2; $i <= 3; $i++)
        {
            // Configura o buscador
            $itenMes = ($i > 0) ? "+" . $i : "" . $i;

            // Configura a data de busca
            $data = date("Y-m", strtotime("{$itenMes} month")) . "-01";

            // Recupera o mes
            $mes = date("m", strtotime($data));

            // Configura o where
            $where = [
                "YEAR(dataVencimento)" => date("Y", strtotime($data)),
                "MONTH(dataVencimento)" => $mes
            ];

            // Realiza a busca
            $obj = $this->objModelMensalidadeRepasse
                ->get(
                    $where,
                    null,
                    null,
                    "SUM(valorTotal) as somatoriaValorTotal, SUM(valorRepasse) as somatoriaValorRepasse"
                )
                ->fetch(\PDO::FETCH_OBJ);

            // Recupera o valor do lucro
            // Para evitar erros
            $valorLucro = ($obj->somatoriaValorTotal > 0) ? ($obj->somatoriaValorTotal - $obj->somatoriaValorRepasse) : 0;

            // Armazena o valor formatado na variavel
            $lucros[] = number_format($valorLucro, 2, ".", "");

            // Armazena a label (mes)
            $labels[] = $this->mesArray["{$mes}"];
        }

        // Retorna
        $dados = [
            "tipo" => true,
            "code" => 200,
            "objeto" => [
                "lucros" => $lucros,
                "meses" => $labels
            ]
        ];

        // Retorno
        $this->api($dados);

    } // End >> fun::lucroImobiliaria()


    /**
     * Método responsável por retorar os contratos assinados com
     * a imobiliaria dos ultimos 6 meses.
     * ------------------------------------------------------------
     * @url api/grafico/contratos
     * @method GET
     */
    public function contratosPorMes()
    {
        // Variaveis
        $dados = null; // Retorno para a view
        $obj = null; // Obejto de busca no banco
        $contratos = null; // Array que armazena os contratos buscados
        $labels = null; // Meses relativos aos lucros
        $itenMes = null; // Auxiliar que configura o item de busca pela data
        $data = null; // Configura a data respectiva
        $mes = null; // Numero do mes da data utlizada
        $where = null; // Condição de busca no banco
        $valorLucro = null; // Armazena o lucro retornado e formatado

        // Percorre o for de meses
        for($i = -3; $i <= 2; $i++)
        {
            // Configura o buscador
            $itenMes = ($i > 0) ? "+" . $i : "" . $i;

            // Configura a data de busca
            $data = date("Y-m", strtotime("{$itenMes} month")) . "-01";

            // Recupera o mes
            $mes = date("m", strtotime($data));

            // Configura o where
            $where = [
                "YEAR(dataInicio)" => date("Y", strtotime($data)),
                "MONTH(dataInicio)" => $mes
            ];

            // Realiza a busca
            $obj = $this->objModelContrato
                ->get(
                    $where,
                    null,
                    null,
                    "COUNT(*) as total"
                )
                ->fetch(\PDO::FETCH_OBJ);

            // Recupera o valor e valida para evitar erros
            $contratos[] = (!empty($obj->total)) ? $obj->total : 0;

            // Armazena a label (mes)
            $labels[] = $this->mesArray["{$mes}"];
        }

        // Retorna
        $dados = [
            "tipo" => true,
            "code" => 200,
            "objeto" => [
                "contratos" => $contratos,
                "meses" => $labels
            ]
        ];

        // Retorno
        $this->api($dados);

    } // End >> fun::contratosPorMes

} // End >> Class::Grafico