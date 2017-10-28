<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ResultadoSorteio;

use App\Models\Aposta;

use App\Models\ResultadosApostas;

Class SiteController extends Controller
{
    /******************************
     * Método principal do sistema*
     ******************************/
    public function indexResultadoSorteio(ResultadoSorteio $resultadoSorteio, ResultadosApostas $resultadosApostas, Aposta $aposta,$erro=0) 
    {
       // Titulo da página
       $title = 'Home Page Loteria';
       // retorna dados dos sorteios realizados
       $numerosSorteados = $resultadoSorteio->getResultadoSorteio($aposta->caminhoArquivoSorteio);
       // informa sorteio vigente
       $qtdSorteios = ( (count($numerosSorteados)%2) == 0 ) ? count($numerosSorteados)/2 : 0 ;
       // retorna a faixa de idade permitida
       $faixaIdades = $aposta->faixaIdade;
       // retorna as apostas realizadas e seu status
       $resulAposta = $resultadosApostas->getResultadoApostas($aposta->caminhoArquivoAposta);
       
       return view( 'index', compact('title','numerosSorteados','faixaIdades','qtdSorteios', 'resulAposta') );
      
    }
    
    /*
     * Método para cadastrar aposta
     */
    public function cadastrarAposta(Aposta $aposta, Request $request)
    {
        // Valida se uma aposta pode ser realizada
        $returnValidaAposta = $aposta->validaAposta($request->input("sorteio"), $request->input("nome"),$request->input("apelido"),$request->input("idade"),$request->input("numeros"));
        
        if( $returnValidaAposta )
            return redirect()->back()->withErrors(['msg', 'The Message']);
        //Insere uma aposta   
        $return = $aposta->insereAposta($request->input("sorteio"),$request->input("nome"),$request->input("apelido"),$request->input("idade"),$request->input("numeros"));
        
        if( $return )
            return redirect('/');
        else
            return redirect()->back();
    }
      
}

