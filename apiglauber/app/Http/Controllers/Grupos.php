<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Voos;
use App\Http\Controllers\Resultados;
use PhpParser\Node\Stmt\Switch_;

class Grupos extends Controller
{
    //
    public $Id_grupo;
    public $Preco_total;
    public $Voos_ida;
    public $Voos_volta;

    function cria_grupos($dados_recebidos){

        $obj = new Voos();
        $arr_obj[] = $obj;

        for($i=0; $i<count($dados_recebidos); $i++){ // repete 1 vez pra cada voo recebido do link http://prova.123milhas.net/api/flights

            $recebido_atual = new Voos();

            $recebido_atual->setTipo_tarifa($dados_recebidos[$i]->fare);
            $recebido_atual->setId_voo($dados_recebidos[$i]->id);

            if($dados_recebidos[$i]->outbound == 1){
                $recebido_atual->setOutbound($dados_recebidos[$i]->outbound);
                $recebido_atual->setPrecoIda($dados_recebidos[$i]->price);
                $recebido_atual->setVoos_ida($dados_recebidos[$i]->id);
            }

            if($dados_recebidos[$i]->inbound == 1){
                $recebido_atual->setInbound($dados_recebidos[$i]->inbound);
                $recebido_atual->setPrecoVolta($dados_recebidos[$i]->price);
                $recebido_atual->setVoos_volta($dados_recebidos[$i]->id);
            }

            $arr_temporario[] = $recebido_atual;

            for($j=0; $j<count($arr_obj); $j++){ // repete 1 vez pra cada grupo gerado, os grupos vão sendo gerados ao longo da execução

                if($arr_obj[$j]->getTipo_tarifa() == ""){ // se o primeiro grupo estiver vazio, ele já recebe o 1º voo

                    $arr_obj[$j] = $recebido_atual;
                    $recebido_atual->setAlocado("sim");

                } else { // se já tem algum, ele começa as comparações

                    if($arr_obj[$j]->getTipo_tarifa() == $recebido_atual->getTipo_tarifa()){ 

                        if($recebido_atual->getOutbound() == 1){
                    
                            if($arr_obj[$j]->getOutbound() == "" or $recebido_atual->getOutbound() == $arr_obj[$j]->getOutbound() and $recebido_atual->getPrecoIda() == $arr_obj[$j]->getPrecoIda()  ){
    
                                $arr_obj[$j]->setOutbound($recebido_atual->getOutbound());
                                $arr_obj[$j]->setPrecoIda($recebido_atual->getPrecoIda());
                                $arr_obj[$j]->setVoos_ida($recebido_atual->getId_voo());
                                $recebido_atual->setAlocado("sim");
    
                            } else {
    
                                    // aqui ele identificou um novo grupo, e adicionou o id no campo combinações
                                    $recebido_atual->setCombinacoes($j);
                                
                            }
                            
    
                        }
    
                        if($recebido_atual->getInbound() == 1){
    
                            if($arr_obj[$j]->getInbound() == "" or $recebido_atual->getInbound() == $arr_obj[$j]->getInbound() and $recebido_atual->getPrecoVolta() == $arr_obj[$j]->getPrecoVolta()  ){
    
                                $arr_obj[$j]->setInbound($recebido_atual->getInbound());
                                $arr_obj[$j]->setPrecoVolta($recebido_atual->getPrecoVolta());
                                $arr_obj[$j]->setVoos_volta($recebido_atual->getId_voo());
                                $recebido_atual->setAlocado("sim");
    
                            } else {
    
                                // aqui ele identificou um novo grupo, e adicionou o id no campo combinações
                                $recebido_atual->setCombinacoes($j);
                                
                                
                            }
                        }


                    }

                }

            }

            if($recebido_atual->getAlocado() == "nao"){ // aqui verifica se esse voo foi alocado em algum grupo
                
                for($k=0; $k<count($recebido_atual->combinacoes); $k++){ // se não foi alocado, cria novos grupos a partir dos ID combinados

                    $novo_obj = new Voos();

                    $novo_obj->setTipo_tarifa($arr_obj[$recebido_atual->combinacoes[$k]]->getTipo_tarifa());
                    $novo_obj->setId_voo($arr_obj[$recebido_atual->combinacoes[$k]]->getId_voo());


                    if($recebido_atual->getOutbound() == 1){
                        $novo_obj->setOutbound($recebido_atual->getOutbound());
                        $novo_obj->setPrecoIda($recebido_atual->getPrecoIda());
                        $novo_obj->setVoos_ida($recebido_atual->getId_voo());
                        $novo_obj->setInbound($arr_obj[$recebido_atual->combinacoes[$k]]->getInbound());
                        $novo_obj->setPrecoVolta($arr_obj[$recebido_atual->combinacoes[$k]]->getPrecoVolta());
                        for($l=0; $l<count($arr_obj[$recebido_atual->combinacoes[$k]]->voos_volta); $l++){
                            $novo_obj->setVoos_volta($arr_obj[$recebido_atual->combinacoes[$k]]->voos_volta[$l]);
                        }
                    }
    
                    if($recebido_atual->getInbound() == 1){
                        $novo_obj->setInbound($recebido_atual->getInbound());
                        $novo_obj->setPrecoVolta($recebido_atual->getPrecoVolta());
                        $novo_obj->setVoos_volta($recebido_atual->getId_voo());
                        $novo_obj->setOutbound($arr_obj[$recebido_atual->combinacoes[$k]]->getOutbound());
                        $novo_obj->setPrecoIda($arr_obj[$recebido_atual->combinacoes[$k]]->getPrecoIda());
                        for($m=0; $m<count($arr_obj[$recebido_atual->combinacoes[$k]]->voos_ida); $m++){
                            $novo_obj->setVoos_ida($arr_obj[$recebido_atual->combinacoes[$k]]->voos_ida[$m]);
                        }
                    }

                    for($n=0; $n<count($arr_obj); $n++){

                        $o = 0;

                        // aqui ele verifica se o grupo não é repetido
                        if($arr_obj[$n]->getPrecoIda() == $novo_obj->getPrecoIda() and $arr_obj[$n]->getPrecovolta() == $novo_obj->getPrecovolta()){

                            $o = 1;

                        }

                    }

                    if($o == 0){ // se não for repetido, adiciona o novo grupo

                        $arr_obj[] = $novo_obj;

                    }

                }

                if(count($recebido_atual->combinacoes) == 0){ // se o voo não foi alocado em nenhum grupo, e nem combinou com nenhum outro, criará um grupo totalmente novo

                    $novo_obj = new Voos();

                    $novo_obj->setTipo_tarifa($recebido_atual->getTipo_tarifa());
                    $novo_obj->setId_voo($recebido_atual->getId_voo());

                    if($recebido_atual->getOutbound() == 1){
                        $novo_obj->setOutbound($recebido_atual->getOutbound());
                        $novo_obj->setPrecoIda($recebido_atual->getPrecoIda());
                        $novo_obj->setVoos_ida($recebido_atual->getId_voo());
                    }
    
                    if($recebido_atual->getInbound() == 1){
                        $novo_obj->setInbound($recebido_atual->getInbound());
                        $novo_obj->setPrecoVolta($recebido_atual->getPrecoVolta());
                        $novo_obj->setVoos_volta($recebido_atual->getId_voo());
                    }

                    $arr_obj[] = $novo_obj;

                }

            }
            
        }

        // aqui todos os grupos já estão formados
        $n_grupos = count($arr_obj);

        for($z=0; $z<$n_grupos; $z++){ // atualiza o Preço Total e cria um ID para cada grupo

            $arr_obj[$z]->setPrecoTotal($arr_obj[$z]->getPrecoIda(), $arr_obj[$z]->getPrecoVolta());

            $indice_grupo = $z + 1;
            $arr_obj[$z]->setId_grupo($indice_grupo);

        }

        usort($arr_obj, function($a, $b){ // ordena pelo preço mais barato

            if($a->getPrecoTotal() == $b->getPrecoTotal()) return 0;
            return (($a->getPrecoTotal() < $b->getPrecoTotal()) ? -1 : 1);

        });

        $voos_unicos = 0;
        $menor_valor = "";
        $id_menor_valor = "";
        $imprime_resultado = new Resultados();


        for($z=0; $z<$n_grupos; $z++){ // aqui vai formar algumas informações solicitadas

            $vi = implode(" - ", $arr_obj[$z]->voos_ida);
            $vo = implode(" - ", $arr_obj[$z]->voos_volta);
            
            $imprime_grupo = new Grupos;
            $imprime_grupo->Id_grupo = $arr_obj[$z]->getId_grupo();
            $imprime_grupo->Preco_total = $arr_obj[$z]->getPrecoTotal();
            $imprime_grupo->Voos_ida = $vi;
            $imprime_grupo->Voos_volta = $vo;

            $imprime_resultado->Grupos_gerados[$z] = $imprime_grupo;
            
            if(count($arr_obj[$z]->voos_ida) == 1 and count($arr_obj[$z]->voos_volta) == 1){ // Verifica os voos únicos
                $voos_unicos = $voos_unicos + 1;
            }

            if($menor_valor == "" or $arr_obj[$z]->getPrecoTotal() < $menor_valor){ // pega o grupo de menor preço total e seu ID
                $menor_valor = $arr_obj[$z]->getPrecoTotal();
                $id_menor_valor = $arr_obj[$z]->getId_grupo();
            }

        }

        $imprime_resultado->Voos_consultados = $dados_recebidos;
        $imprime_resultado->Total_de_grupos = $n_grupos;
        $imprime_resultado->Total_voos_unicos = $voos_unicos;
        $imprime_resultado->Preco_mais_barato = $menor_valor;
        $imprime_resultado->Id_mais_barato = $id_menor_valor;

        echo "<pre>" . json_encode($imprime_resultado, JSON_PRETTY_PRINT) . "</pre>";

        
    }


}
