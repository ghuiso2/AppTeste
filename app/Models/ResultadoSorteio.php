<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoSorteio extends Model
{
    /*****************************
     * Verifica números sorteados*
     *****************************/
    public function getResultadoSorteio($caminhoSorteio) 
    {
        $aux = [];
        $count = 0;
        $arquivo = fopen($caminhoSorteio, 'r');
        
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
                $aux[$count]= $pieces[1];
                $count++;
            }
        }
        
        fclose($arquivo);
        
        return $aux;
    
    }
}