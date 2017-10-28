<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadosApostas extends Model
{
    /******************************
     * Verifica apostas realizadas*
     ******************************/
    public function getResultadoApostas($caminhoArquivoSorteio)
    {
        $aux = [];
        $count = 0;
        $arquivo = fopen($caminhoArquivoSorteio, 'r');
        
        while(!feof($arquivo))
        {   
           $linha = fgets($arquivo, 1024);
           $pieces = explode(".", $linha);
            
           if ( $linha == '' && count($pieces) < 2 && $count >= 0 ){
               if($count == 0) 
                   $aux = 0;
               break;
            }else{
                $aux[$count]= $pieces[0];
                $count++;
                $aux[$count]= $pieces[2];
                $count++;
                $aux[$count]= $pieces[4];
                $count++;
                $aux[$count]= $pieces[5];
                $count++;
            }
        }
        fclose($arquivo);
        
        return $aux;
        
    }
}