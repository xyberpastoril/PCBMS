<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF</title>
    <style>
        table{
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .table-total {
            color:darkgreen;
        }
        .table-bordered th, .table-bordered td{
            text-align:left;
            border: 1px solid black;
            border-collapse: collapse;
            padding: 2px;
        }
        /* th,td{
            
        } */
        .border-bottom{
            border-bottom: 1px solid black;
        }
        .blank_row
        {
            height: 15px ;
        }
        tfoot, .border-top-3
        {
            border-top:3px solid black;
        }
        .text-start
        {
            text-align:left;
        }
        .text-center{
            text-align: center;
        }
        .text-end
        {
            text-align:right;
        }
    </style>
    @stack('style')
</head>
<body>
    <p>
        Visayas State University<br>
        Office of the Vice President of Research, Extension, and Innovation<br>
        <strong>Pasalubong Center</strong><br>
    </p>
    {{-- section page title --}}
    <p>
        <strong>@yield('page_title')</strong>
        @if(isset($date_from) && isset($date_to))
        <br>
        {{ $date_from }} - {{ $date_to }}
        @endif
    </p>

    <main>
        {{-- section content --}}
        @yield('content')
    </main>


</body>
</html>