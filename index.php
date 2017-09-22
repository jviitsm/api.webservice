<?php

require 'bootstrap.php';

//Rotas dos clientes
require 'Routes/CidadaoRoutes.php';
require 'Routes/EmpresaRoutes.php';
require 'Routes/LoginRoutes.php';
require 'Routes/DenunciaRoutes.php';

$app->run();