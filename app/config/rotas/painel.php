<?php

// Login ou Logout
$Rotas->on("GET","login","Principal::login");
$Rotas->on("GET","logout/{p}","Principal::login");

// Sair
$Rotas->on("GET","sair","Principal::sair");

// Página principal
$Rotas->on("GET","painel","Principal::painel");