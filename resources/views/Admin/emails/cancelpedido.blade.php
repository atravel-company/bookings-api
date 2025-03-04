<html>
<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
<style>
    @media print {
        tr.head{
            background-color: #24AEC9!important;
            color: white!important;
            font-size:14px!important;
            -webkit-print-color-adjust: exact; 
        }
        .align-center{
            text-align:center!important;
        }
        .table-body{
            border-bottom:1px #4e4e4e solid;
            font-size:10px!important;
        }
        tr.head th{
            padding:3px!important;
        }
        .footer{
            font-size:10px!important;
            line-height: 1;
        }
    }

    tr.head{
        background-color: #24AEC9;
        color: white;
        font-size:14px!important;
    }
    tr.head th{
        padding:5px!important;
    }
    .align-center{
        text-align:center!important;
    }
    .table-body td{
        border-bottom:1px #4e4e4e solid;
        font-size:10px!important;
    }

    .footer{
        font-size:10px!important;
        line-height: 1;
    }
</style>
<link href="./css/w3.css" rel="stylesheet">
    <head></head>
    <body>
        <div class="w3-row w3-padding">
            <table class="w3-table w3-striped w3-centered" {{-- style="max-width: 100%" --}}>
                <tr>
                    <th>&nbsp;</th>
                    <th style="float:right; clear:right; text-align: right; margin-bottom: 20px">
                        <img width=190 src="https://atsportugal.com/public/storage/LogotipoAtravelCor.png">
                    </th>
                </tr>       
            </table>
        </div>
        <br><br>
        <div class="w3-row w3-padding">
            Caros colegas,<br>

            <strong><b>Informamos que a Agência {{$usuario->name}} cancelou a reserva com a referência: {{$pedido->referencia}} e o Lead Name: {{$pedido->lead_name}}<br> Por favor proceda a estas alterações nesta reserva.</b></strong>
        </div>
        <br>
        <hr>
        <div class="w3-row w3-padding">
            <table class="w3-table w3-striped w3-centered" width="549px">
                <tr>
                    <td class="footer" style="text-align: left;">ATravel</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">Av. da Liberdade,</td>
                </tr>    
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">TOPICOS E DESCOBERTAS - licence RNVAT 8019</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">243, 4ºA </td>
                </tr> 
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">VAT 514 974 567</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">1250-143 Lisboa</td>
                </tr> 
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">Reg. Na C.R.C. de Portimão sob o nº 3628/000822</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">sales@atravel.pt</td>
                </tr>
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">Sociedade por Quotas</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">www.atsportugal.com</td>
                </tr>
            </table>
        </div>
        <br>
    </body>
</html>