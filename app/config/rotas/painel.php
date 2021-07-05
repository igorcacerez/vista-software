<?php

// Login ou Logout
$Rotas->on("GET","login","Principal::login");
$Rotas->on("GET","logout/{p}","Principal::login");

// Sair
$Rotas->on("GET","sair","Principal::sair");

// Página principal
$Rotas->on("GET","painel","Principal::painel");

// Locador
$Rotas->on("GET","locadores","Locador::listar");
$Rotas->on("GET","locador/inserir","Locador::inserir");
$Rotas->on("GET","locador/alterar/{p}","Locador::alterar");
$Rotas->on("GET","locador/get-datatable","Locador::getDataTable");

// Locatário
$Rotas->on("GET","locatarios","Locatario::listar");
$Rotas->on("GET","locatario/inserir","Locatario::inserir");
$Rotas->on("GET","locatario/alterar/{p}","Locatario::alterar");
$Rotas->on("GET","locatario/get-datatable","Locatario::getDataTable");

// Usuario
$Rotas->on("GET","usuarios","Usuario::listar");
$Rotas->on("GET","usuario/inserir","Usuario::inserir");
$Rotas->on("GET","usuario/alterar/{p}","Usuario::alterar");
$Rotas->on("GET","usuario/get-datatable","Usuario::getDataTable");

// Contrato
$Rotas->on("GET","contratos","Contrato::listar");
$Rotas->on("GET","contrato/inserir","Contrato::inserir");
$Rotas->on("GET","contrato/detalhes/{p}","Contrato::detalhes");
$Rotas->on("GET","contrato/get-datatable","Contrato::getDataTable");