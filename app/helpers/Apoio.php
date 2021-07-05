<?php

/**
 * Classe responsável por conter métodos que auxiliam no desenvolvimento
 * de softwares.
 */

// NameSpace
namespace Helper;

// Inicia a classe
use DuugWork\Helper\SendCurl;

class Apoio
{
    /**
     * Método responsável por validar se o usuário está logado,
     * recuperando a sua session.
     * ----------------------------------------------------------
     * @return null
     */
    public function seguranca()
    {
        // Recupera os dados da sessao
        $user = (!empty($_SESSION["usuario"]) ? $_SESSION["usuario"] : null);
        $token = (!empty($_SESSION["token"]) ? $_SESSION["token"] : null);


        // Verifica se possui algo
        if(!empty($user->id_usuario))
        {
            // Verifica se o token está valido
            if($token->data_expira > date("Y-m-d H:i:s"))
            {
                // Add o token ao usuario
                $user->token = $token;

                // Retorna o usuario
                return $user;
            }
            else
            {
                // Deleta a session
                session_destroy();

                // Redireciona para a tela de logout
                header( "Location: " . BASE_URL . "logout/" . $user->email);
            } // Error - Token Expirado
        }
        else
        {
            // Redireciona para a tela de login
            header( "Location: " . BASE_URL . "login");
        } // Error - usuario não logado

    } // End >> fun::seguranca()


    /**
     * Método responsável por formatar um numero na casa do milhar, deixando
     * em siglas K,M,B,T,Q
     * ---------------------------------------------------------------------
     * @param null|int $numero
     * @return string
     */
    public function formatNumero($numero = null)
    {
        // Variaveis
        $cont = 0;
        $array  = ["","K","M","B","T","Q"];

        // Divide o numero por mil
        while ($numero >= 1000)
        {
            $numero = $numero / 1000;
            $cont++;
        }


        // Verifica se o numero não é inteiro
        if(is_int($numero) == false)
        {
            // Deixa com duas casas decimais
            $numero = number_format($numero,2,".");
        }

        // Retorna o numero com a letra
        return $numero . $array[$cont];
    }


    /**
     * DIAS ENTRE 02 DATAS
     * @author Norberto ALcântara
     * @copyright (c) Célula Nerd, 2019
     *
     * @param $data_inicial
     * @param $data_final
     * @return int
     */
    public function diasDatas($data_inicial, $data_final)
    {
        $diferenca = strtotime($data_final) - strtotime($data_inicial);
        $dias = floor($diferenca / (60 * 60 * 24));
        return $dias;
    } // End >> diasDatas()


    /**
     * Método responsável por formatar um determinado numero de
     * telefone ou celular, adionando mascara.
     * --------------------------------------------------------
     * @param $value
     * @return string|string[]|null
     * --------------------------------------------------------
     * @author igorcacerez
     */
    public function formatTelCel($value)
    {
        // Limpa os dados
        $value = preg_replace("/\D/", '', $value);

        // Verifica se possui algo
        if(!empty($value))
        {
            // Verifica se é fixo
            if (strlen($value) === 10)
            {
                return preg_replace("/(\d{2})(\d{4})(\d{4})/", "(\$1) \$2-\$3", $value);
            }

            return preg_replace("/(\d{2})(\d{5})(\d{4})/", "(\$1) \$2-\$3", $value);
        }
        else
        {
            return null;
        }

    } // End >> fun::formatTelCel()


    /**
     * Método responsável por realizar a busca de bairros na api do
     * vista crm e salvar os resultados em uma session para que na
     * proxima necessidade não precise realziar uma consulta na api.
     * --------------------------------------------------------------
     * @return array|mixed
     */
    public function retornaBairros()
    {
        // Variaveis
        $bairros = null;
        $informacoesBuscaApi = null;
        $url = null;

        // Verifica se não possui uma busca salva
        if(empty($_SESSION["bairros"]))
        {
            // Instancia o objeto de requisição
            $objHelperSend = new SendCurl();

            // Informações a ser retornadas pelo
            $informacoesBuscaApi = [
                "fields" => [
                    "Bairro"
                ],

                // Filtra para não exibir vazio nem ...
                "advFilter" => [
                    "Bairro" => ["!=", ""],
                    "And" => [
                        "Bairro" => ["!=","..."]
                    ]
                ]
            ];

            // Configura a url
            $url = URL_API . "imoveis/listarConteudo?key=" . TOKEN_VISTA;
            $url .= "&pesquisa=" . json_encode($informacoesBuscaApi);

            // Configura o cabeçalho da requisição
            $objHelperSend->setHeader("Accept", "application/json"); // Aceita resposta em Json

            // Realiza a requisição na Api do Vista CRM
            $bairros = $objHelperSend->resquest($url, null, "GET", true);

            // Evita erros e recupera o conteudo
            if(!empty($bairros->Bairro))
            {
                // Recupera apenas os itens
                $bairros = $bairros->Bairro;

                // Salva na session
                $_SESSION["bairros"] = $bairros;
            }
        }
        else
        {
            // Recupera os itens da session
            $bairros = $_SESSION["bairros"];
        } // Possui salvo na session

        // Retorna os bairros salvos
        return $bairros;

    } // End >> fun::retornaBairros()


    public function retornaCategorias()
    {
        // Variaveis
        $categorias = null;
        $informacoesBuscaApi = null;
        $url = null;

        // Verifica se não possui uma busca salva
        if(empty($_SESSION["categorias"]))
        {
            // Instancia o objeto de requisição
            $objHelperSend = new SendCurl();

            // Informações a ser retornadas pelo
            $informacoesBuscaApi = [
                "fields" => [
                    "Categoria"
                ],

                // Não retorna se tiver vazio
                "filter" => [
                    "Categoria" => ["!=", ""]
                ]
            ];

            // Configura a url
            $url = URL_API . "imoveis/listarConteudo?key=" . TOKEN_VISTA;
            $url .= "&pesquisa=" . json_encode($informacoesBuscaApi);

            // Configura o cabeçalho da requisição
            $objHelperSend->setHeader("Accept", "application/json"); // Aceita resposta em Json

            // Realiza a requisição na Api do Vista CRM
            $categorias = $objHelperSend->resquest($url, null, "GET", true);

            // Evita erros e recupera o conteudo
            if(!empty($categorias->Categoria))
            {
                // Recupera apenas os itens
                $categorias = $categorias->Categoria;

                // Salva na session
                $_SESSION["categorias"] = $categorias;
            }
        }
        else
        {
            // Recupera os dados da session
            $categorias = $_SESSION["categorias"];
        } // Já possui a busca salva na session

        // Retorna
        return $categorias;

    } // End >> fun::retornaCategorias()

} // End >> Class::Apoio()