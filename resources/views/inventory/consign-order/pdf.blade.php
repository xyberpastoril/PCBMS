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

@php
    // instantiate the barcode class
    $barcode = new \Com\Tecnick\Barcode\Barcode();

    function generateBarcode($barcode, $string) {
        return $barcode->getBarcodeObj(
        'C128',                     // barcode type and additional comma-separated parameters
        ''.$string,          // data string to encode
        -2,                             // bar width (use absolute or negative value as multiplication factor)
        -50,                             // bar height (use absolute or negative value as multiplication factor)
        'black',                        // foreground color
        array(10, 5, 10, 5)           // padding (use absolute or negative values as multiplication factors)
        )->setBackgroundColor('white'); // background color

    // output the barcode as HTML div (see other output formats in the documentation and examples)
    }
@endphp

@for($i = 0; $i < count($consigned_products); $i++)
<table class="table-bordered">
    <thead>
        <th colspan="4">{{ $consigned_products[$i]->id . ': ' . $consigned_products[$i]->product->name . ' (' . $consigned_products[$i]->particulars . $consigned_products[$i]->product->unit->abbreviation . ')' }}</th>
    </thead>

    @php
        $j = 0;
    @endphp
    
    @for(; $j < $consigned_products[$i]->quantity; $j++)
        @if($j % 4 == 0)
            <tr>
        @endif

        <td style="width:25%">
            {!! generateBarcode($barcode, $consigned_products[$i]->id)->getHtmlDiv() !!}
        </td>

        @if($j % 4 == 3)
            </tr>
        @endif
    @endfor

    @if($j % 4 != 0)
        @for(;$j % 4 != 0; $j++)
            <td style="width:25%"></td>
        @endfor
        </tr>
    @endif
</table>
@endfor