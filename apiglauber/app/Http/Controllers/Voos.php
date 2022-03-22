<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Voos extends Controller
{
    public $id_grupo;
    private $id_voo;
    private $alocado = "nao";
    public $combinacoes = array();
    private $tipo_tarifa;
    private $preco_ida;
    private $preco_volta;
    private $preco_total;
    private $outbound;
    private $inbound;
    public $voos_ida = array();
    public $voos_volta = array();

    public function getId_grupo(){
        return $this->id_grupo;
    }

    public function setId_grupo($id_grupo){
        $this->id_grupo = $id_grupo;
    }

    public function getId_voo(){
        return $this->id_voo;
    }

    public function setId_voo($id_voo){
        $this->id_voo = $id_voo;
    }

    public function getAlocado(){
        return $this->alocado;
    }

    public function setAlocado($alocado){
        $this->alocado = $alocado;
    }

    public function setCombinacoes($combinacoes){
        $this->combinacoes[] = $combinacoes;
    }

    public function getTipo_tarifa(){
        return $this->tipo_tarifa;
    }

    public function setTipo_tarifa($tipo_tarifa){
        $this->tipo_tarifa = $tipo_tarifa;
    }

    public function getPrecoIda(){
        return $this->preco_ida;
    }

    public function setPrecoIda($preco){
        $this->preco_ida = $preco;
    }

    public function getPrecoVolta(){
        return $this->preco_volta;
    }

    public function setPrecoVolta($preco){
        $this->preco_volta = $preco;
    }

    public function getPrecoTotal(){
        return $this->preco_total;
    }

    public function setPrecoTotal($a, $b){
        $this->preco_total = $a + $b;
    }

    public function getOutbound(){
        return $this->outbound;
    }

    public function setOutbound($outbound){
        $this->outbound = $outbound;
    }

    public function getInbound(){
        return $this->inbound;
    }

    public function setInbound($inbound){
        $this->inbound = $inbound;
    }

    public function setVoos_ida($voos_ida){
        $this->voos_ida[] = $voos_ida;
    }

    public function setVoos_volta($voos_volta){
        $this->voos_volta[] = $voos_volta;
    }

   

    

}
