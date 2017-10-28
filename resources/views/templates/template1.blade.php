<!DOCTYPE html>
<html>
    <head>
        <title>{{$title or 'Título da Página'}}</title>
        
        <!--Bootstrap-->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" >
        
        <script src="{{url('js/jquery.js')}}" type="text/javascript"></script>
         <script src="{{url('js/dataTables.js')}}" type="text/javascript"></script>
         <script src="{{url('js/dataTablesBootstrap.js')}}" type="text/javascript"></script>
    
        <link rel="stylesheet" href="{{url('css/estiloIndex.css')}}">
        <script>
            $(document).ready(function() {
                    $('#tabelaSorteios').DataTable();
                    $('#tabelaResultados').DataTable();
                    
                    $( "#alertErros" ).fadeOut(7000);
                                        
            } );
        </script>
    </head>
    <body>
        
        <div class='container'>
                
                @yield('content')
                @yield('content2')
                
        </div>
        
    </body>
</html>