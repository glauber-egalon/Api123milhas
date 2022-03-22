<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Resultados extends Controller
{
    //
    public $Voos_consultados = array();
    public $Grupos_gerados = array();
    public $Total_de_grupos;
    public $Total_voos_unicos;
    public $Preco_mais_barato;
    public $Id_mais_barato;

}
