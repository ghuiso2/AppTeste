@extends('templates.template1')

@section('content')

@if($errors->any())
    <div class = "alert alert-danger alertErros" id="alertErros" >
        <center><h4>Regras:</h4></center>
        <p>Só é permitido uma aposta por pessoa em um sorteio.</p>
        <p>O nome deve conter entre 3 e 30 caracteres.</p>
        <p>O apelido deve conter entre 3 e 8 carateres.</p>
        <p>Selecione sua faixa de idade.</p>
        <p>Selecione a quantidade de números de acordo com sua idade.</p>
    </div>
@endif

<div class='cabecalho'>Loteria - Sorteio {{$qtdSorteios+1}}</div>

<div class='cadastrarAposta'>
    
    <h3>Cadastrar Aposta</h3>

    <form class="form" method="post" action="{{url('/cadastrarAposta')}}">
        {!! csrf_field() !!}
        <div class="form-group">
            <input type="text" name="nome" placeholder="Nome:" class="form-control">
        </div>
         
        <div class="form-group">
            <input type="text" name="apelido" placeholder="Apelido:" class="form-control">
        </div>

        <div class="form-group">
            <select class="form-control" name="idade">
                <option value="">Selecione sua faixa de idade:</option>
                @foreach($faixaIdades as $faixaIdade)
                <option value="{{$faixaIdade}}">{{$faixaIdade}}</option> 
                @endforeach
            </select>
        </div>
            <label> Escolha seus números:</label>
        <div class="form-group">
            <input type="checkbox" name="numeros[]" value="0">00
            <input type="checkbox" name="numeros[]" value="1">01
            <input type="checkbox" name="numeros[]" value="2">02
            <input type="checkbox" name="numeros[]" value="3">03
            <input type="checkbox" name="numeros[]" value="4">04
            <input type="checkbox" name="numeros[]" value="5">05
            <input type="checkbox" name="numeros[]" value="6">06
            <input type="checkbox" name="numeros[]" value="7">07
            <input type="checkbox" name="numeros[]" value="8">08
            <input type="checkbox" name="numeros[]" value="9">09
        </div>
        <div class="form-group">
            <input type="checkbox" name="numeros[]" value="10">10
            <input type="checkbox" name="numeros[]" value="11">11
            <input type="checkbox" name="numeros[]" value="12">12
            <input type="checkbox" name="numeros[]" value="13">13
            <input type="checkbox" name="numeros[]" value="14">14
            <input type="checkbox" name="numeros[]" value="15">15
            <input type="checkbox" name="numeros[]" value="16">16
            <input type="checkbox" name="numeros[]" value="17">17
            <input type="checkbox" name="numeros[]" value="18">18
            <input type="checkbox" name="numeros[]" value="19">19
        </div>
            <input type="hidden" name="sorteio" value="{{$qtdSorteios+1}}">
        <button class="btn btn-primary btn-add">Cadastrar</button>

    </form>
</div>

<div class='ultimosSorteios'>
    
    <h3>Ultimos sorteios</h3>

    <table id='tabelaSorteios' class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Numeros Sorteados</th>
            </tr>
        </thead>
        <tbody> 
            @for( $i=0; $i<count($numerosSorteados);$i++)
            <tr>
                <td>{{$numerosSorteados[$i]}}</td>
                <div style="display:none;">{{$i++}}</div>
                <td>{{$numerosSorteados[$i]}}</td>
            </tr>
            @endfor
            </tbody>   
    </table> 
</div>
<div class='resultados'>
    
    <h3>Jogadores</h3>

    <table id='tabelaResultados' class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Sorteio</th>
                <th>apelido</th>
                <th>Numeros Sorteados</th>
                <th>Resultado Sorteio</th>
            </tr>
        </thead>
        <tbody> 
            
            @for( $i=0; $i<count($resulAposta);$i++ )
            <tr>
                <td>{{$resulAposta[$i]}}</td>
                    <div style="display:none;">{{$i++}}</div>
                <td>{{$resulAposta[$i]}}</td>
                    <div style="display:none;">{{$i++}}</div>
                <td>{{$resulAposta[$i]}}</td>
                    <div style="display:none;">{{$i++}}</div>
                <td>{{$resulAposta[$i]}}</td>
            </tr>
            @endfor
            </tbody>   
    </table>

</div>

@endsection