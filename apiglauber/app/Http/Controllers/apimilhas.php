<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Voos;
use PhpParser\Node\Stmt\Switch_;
use App\Http\Controllers\Grupos;

class apimilhas extends Controller
{
    //
   
    public function index(){

        $recebe = Http::get('http://prova.123milhas.net/api/flights');

        $dados_recebidos = json_decode($recebe);

        $grupo_api = new Grupos();

        $grupo_api->cria_grupos($dados_recebidos);

    }

}
