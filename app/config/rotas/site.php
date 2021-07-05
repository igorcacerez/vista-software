<?php

// Erro 404
$Rotas->onError("404", "Principal::erro404");

// Página inicial do site
$Rotas->on("GET","","Principal::index");