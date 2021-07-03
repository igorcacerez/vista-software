<?php

/**
 * Classe responsável por conter métodos que auxiliam no desenvolvimento
 * de softwares.
 */

// NameSpace
namespace Helper;

// Inicia a classe
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

} // End >> Class::Apoio()