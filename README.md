<h1 align="center">Vista Soft - Teste</h1>

<p align="center">
    <img src="https://img.shields.io/static/v1?label=license&message=MIT&color=0d7bbd" />
    <img src="https://img.shields.io/static/v1?label=version&message=TESTE&color=0d7bbd" />
</p>



<p align="center">🚀 Desenvolvimento de um sistema administrativo e um site que realize buscas na API da Vista.</p>

<h3>Índice</h3>

<!--ts-->
* [Instalação](#instalação)
* [Bando de dados](#banco-de-dados)
* [Acessos](#acessos)
<!--te-->

## Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:

- [Composer](https://getcomposer.org/)
- Servidor Apache
- PHP 5.6+


## Instalação

Para instalação clone ou baixe o projeto e depois disso rode o seguinte comando. ``composer update``
<br>Depois de rodar o comando acesse o arquivo ````app > config > config.php```` e altere o ````BASE_URL````  para o ambiente de teste como por exemplo ````http://localhost/git/vista-softwarw/````


````php
// URL base do site.
defined('BASE_URL') OR define('BASE_URL', 'http://localhost/git/vista-software/');
````

Esse procedimento deve repetido, dessa vez no seguinte arquivo  ````assets > app > global.js````

````javascript
// Altere as duas URLs
var Dados = {
    "url": "http://localhost/git/vista-software/",
    "urlApi": "http://localhost/git/vista-software/api/"
}
````


## Banco de dados


Crie um no novo banco de dados no seu ambiente e rode o sql de criação das tabelas. O script 
está no seguinte local ````documents > database > create.sql```` <br>
Após acesse o arquivo ````app > config > database.php```` e configure as creedencias do seu banco de dados.

````php
$database = [
    'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'vista'
];
````



## Acessos

Para ver o projeto em funcionamento acesse a seguintes urls: <br>
- [Site Imobiliária](https://woope.me/teste-vista/)
- [Painel Administrativo](https://woope.me/teste-vista/painel)

Credenciais para acesso ao painel administrativo: <br>

- E-mail: igor.cacerez@gmail.com
- Senha: 123

Para acessar a documentação da Api desenvolvida [Clique Aqui](https://documenter.getpostman.com/view/5411264/Tzm3ncnm)


