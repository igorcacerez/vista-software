<?php

// USUARIO
$Rotas->group("api-usuario","api/usuario","Api\Usuario");
$Rotas->onGroup("api-usuario","POST","login","login");
$Rotas->onGroup("api-usuario","GET","get/{p}","get");
$Rotas->onGroup("api-usuario","GET","get","getAll");
$Rotas->onGroup("api-usuario","POST","insert","insert");
$Rotas->onGroup("api-usuario","PUT","update/{p}","update");
$Rotas->onGroup("api-usuario","DELETE","delete/{p}","delete");

// LOCATARIO
$Rotas->group("api-locatario","api/locatario","Api\Locatario");
$Rotas->onGroup("api-locatario","GET","get/{p}","get");
$Rotas->onGroup("api-locatario","GET","get","getAll");
$Rotas->onGroup("api-locatario","POST","insert","insert");
$Rotas->onGroup("api-locatario","PUT","update/{p}","update");
$Rotas->onGroup("api-locatario","DELETE","delete/{p}","delete");
