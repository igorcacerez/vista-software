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

// LOCADOR
$Rotas->group("api-locador","api/locador","Api\Locador");
$Rotas->onGroup("api-locador","GET","get/{p}","get");
$Rotas->onGroup("api-locador","GET","get","getAll");
$Rotas->onGroup("api-locador","POST","insert","insert");
$Rotas->onGroup("api-locador","PUT","update/{p}","update");
$Rotas->onGroup("api-locador","DELETE","delete/{p}","delete");


// CONTRATO
$Rotas->group("api-contrato","api/contrato","Api\Contrato");
$Rotas->onGroup("api-contrato","GET","get/{p}","get");
$Rotas->onGroup("api-contrato","GET","get","getAll");
$Rotas->onGroup("api-contrato","POST","insert","insert");
$Rotas->onGroup("api-contrato","DELETE","delete/{p}","delete");
$Rotas->onGroup("api-contrato","PUT","pagar/{p}/{p}","updateStatusMensalidadeRepasse");

