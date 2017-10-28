<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
    public $caminhoArquivoAposta = '/var/www/html/miniAplicacoes/loteria/app/Models/Apostas.txt';
    
    public $caminhoArquivoSorteio = '/var/www/html/miniAplicacoes/loteria/app/Models/Sorteios.txt';
    
    public $faixaIdade = [
                                '0' => 'Até 18 anos - Escolha 6 números',
                                '1' => 'Entre 19 e 30 anos - Escolha 8 números',
                                '2' => 'Entre 31 e 50 anos - Escolha 10 números',
                                '3' => 'Entre 51 e 70 anos - Escolha 11 números',
                                '4' => 'Acima de 70 anos - Escolha 12 números'
    ];
    
    private $maxNumerosSorteados = 6;
    
    private $menorNumSorteado = 0;
    
    private $maiorNumSorteado = 19;
    /********************************************
     * Método com as regras para realizar aposta*
     ********************************************/
    public function validaAposta($sorteio , $nome, $apelido, $idade, $numeros)
    {
        //Requer nome preenchido | Maior que 2 e menor que 31 caracteres
        if( strlen($nome) < 3 || strlen($nome) > 30  ) 
                return true;
        //Requer apelido preenchido | Maior que 2 e menor que 9 caracteres
        elseif( (strlen($apelido)<3 || strlen($apelido)>9) )
                return true;
        //Requer $idade preenchido
        elseif( strlen($idade)<1 )
                return true;
        //valida quantidade de números escolhidos por idade
        elseif( 
                    ($idade ==  $this->faixaIdade[0]) && count($numeros)<>6
              ||    ($idade ==  $this->faixaIdade[1]) && count($numeros)<>8
              ||    ($idade ==  $this->faixaIdade[2]) && count($numeros)<>10
              ||    ($idade ==  $this->faixaIdade[3]) && count($numeros)<>11
              ||    ($idade ==  $this->faixaIdade[4]) && count($numeros)<>12
              ) 
                return true;
        //valida se um apostador está realizando mais de ums aposta por sorteio
        elseif( $this->verificaApostador($sorteio, $apelido) )
                return true;
        else
                return false;
    }
    /*
     * Método que realiza insere a aposta
     */
    public function insereAposta($sorteio, $nome, $apelido, $idade, $numeros)
    {
        $nome_arquivo = $this->caminhoArquivoAposta;
        $arquivo      = file($nome_arquivo);
        
        $string_a_ser_inserida = $sorteio   .".";
        $string_a_ser_inserida .= $nome     .".";
        $string_a_ser_inserida .= $apelido  .".";
        $string_a_ser_inserida .= $idade    .".";
        
        for( $i=0;$i<count($numeros);$i++ ){
            
            if( ($i+1)==count($numeros ))
               $string_a_ser_inserida .= $numeros[$i] . ".";
            else
                $string_a_ser_inserida .= $numeros[$i].",";
        
        }
        
        $string_a_ser_inserida .= "Aguardando sorteio" . "\n";
        $resultadoAposta = file_put_contents($nome_arquivo, $string_a_ser_inserida . file_get_contents($nome_arquivo));
        
        $nome_arquivo = $this->caminhoArquivoAposta;
        $arquivo      = file($nome_arquivo);
        //Verifica se houve 5 apostas para realizar sorteio
        if ( count($arquivo)%5 == 0 )
            return $this->realizaSorteio($sorteio);
        elseif ( $resultadoAposta ) 
            return true;
        else
            return 'falha ao inserir aposta e/ou gerar sorteio';
        
    }
    /*****************************
     * Método que realiza sorteio*
     *****************************/
    public function realizaSorteio($sorteio)
    {
        //realiza o sorteio com números aleatórios
        $array = [];
        while(count($array)<$this->maxNumerosSorteados){
            $aux = mt_rand($this->menorNumSorteado, $this->maiorNumSorteado);
            if ( (in_array($aux, $array, true)) == false ) {
                array_push($array, $aux);
            }
        }
                
        sort($array);
        $text = '';
        
        for( $i=0; $i<count($array); $i++ ){
            if( ($i+1)==count($array))
               $text .= $array[$i];
            else
                $text .= $array[$i].",";
        }
        
        $nome_arquivo = $this->caminhoArquivoSorteio;
        $arquivo      = file($nome_arquivo);
        $string_a_ser_inserida = $sorteio   .".";
        $string_a_ser_inserida .= $text . ".";
        $string_a_ser_inserida .= "\r\n";
        // Salva os númetos sorteados no arquivo
        $resultadoSorteio = file_put_contents($nome_arquivo, $string_a_ser_inserida . file_get_contents($nome_arquivo));
        // Verifica se ouve vencedor no sorteio
        $this->verificaVencedor();
        
        return true;
        
    }
    /***************************************
     * Método que verifica se ouve vencedor*
     ***************************************/
    public function verificaVencedor(){
        // Pega o ultimo sorteio realizado
        $arquivoSorteio = fopen($this->caminhoArquivoSorteio, 'r');
            $linhaSorteio = fgets($arquivoSorteio, 1024);
            $piecesSorteio = explode(".", $linhaSorteio);
            $numerosSorteados = explode(",", $piecesSorteio[1]);
        fclose($arquivoSorteio);
        // Pega as ultimas 5 apostas para do sorteio vigente
        $arrayAposta = [];
        $cont = 0;
        $arquivoAposta = fopen($this->caminhoArquivoAposta, 'r');
            while(!feof($arquivoAposta))
            {   
                $cont++;
                $linhaAposta = fgets($arquivoAposta, 1024);
                array_unshift($arrayAposta, $linhaAposta);
                if($cont == 5)
                    break;
            }
        fclose($arquivoAposta);
        // Valida se ouve um apostador com os números sorteados
        $ganhador = 0;
        $acertos = "";
        $auxArrayAposta = [];
                
        for( $i=0; $i<count($arrayAposta);$i++ ){
            // Verifica se tem um vencedor
            if($ganhador == 0){
                // Pega os dados do primeiro vencedor
                $apostador = explode(".", $arrayAposta[$i]);
                $numerosApostador = explode(",", $apostador[4]);
                // verifica se os números apostados são os mesmos sorteados
                for( $j=0; $j<count($numerosSorteados); $j++ ){
                    for( $x=0; $x<count($numerosApostador); $x++ ){
                        if( $numerosSorteados[$j] == $numerosApostador[$x] ){
                           $acertos++;
                        }
                    }
                }
                //Verifica se o apostador acertou os 6 números
                if($acertos == 6){
                     $apostador[5] = '-----Vencedor-----';
                     array_unshift($auxArrayAposta, $apostador[0].".".$apostador[1].".".$apostador[2].".".$apostador[3].".".$apostador[4].".".$apostador[5]);
                     $ganhador = 1;
                }else{
                    $acertos=0;
                    $apostador[5] = '---Vai tentando---';
                     array_unshift($auxArrayAposta, $apostador[0].".".$apostador[1].".".$apostador[2].".".$apostador[3].".".$apostador[4].".".$apostador[5]);
                }
            // Se tivermos um ganhador o mesmo preenche os demais como perdedores   
            }else{
                $apostador = explode(".", $arrayAposta[$i]);
                $apostador[5] = '---Vai tentando---';
                array_unshift($auxArrayAposta, $apostador[0].".".$apostador[1].".".$apostador[2].".".$apostador[3].".".$apostador[4].".".$apostador[5]);
            }
        }
        // Atualiza o arquivo de aposta com os status dos apostadores
        $arquivoAposta = fopen($this->caminhoArquivoAposta, 'r+');
            for ( $i=0; $i< count($auxArrayAposta);$i++)
                fwrite($arquivoAposta, $auxArrayAposta[$i]."\n");
        fclose($arquivoAposta);
        
        return true;
         
    }
    /*********************************************
     * Método que valida um apostador por sorteio*
     *********************************************/
    public function verificaApostador($sorteio, $apelido)
    {
        $cont = 0;
        $arquivoAposta = fopen($this->caminhoArquivoAposta, 'r');
            while(!feof($arquivoAposta)){
                $linhaAposta = fgets($arquivoAposta, 1024);
                $apostador = explode(".", $linhaAposta);
                if($apostador[0]== $sorteio && $apostador[2] == $apelido)
                    return true;
                else
                   $cont++;
                
                if($cont==5)
                    return false;
            }
        fclose($arquivoAposta);
        
    }
              
}