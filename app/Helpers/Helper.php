<?php

use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;

if (!function_exists('compressImage')) {
    /**
     * Compress an uploaded image until it is under 2 MB.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @return \Illuminate\Http\UploadedFile
     */
    function compressImage(UploadedFile $image)
    {
        $targetSize = 2 * 1024 * 1024; // 2MB
        $img = Image::make($image->getRealPath());
        $quality = 90; // percent
        
        // Iteratively compress until the file size is <= 2 MB or quality is very low
        do {
            $encodedImage = $img->encode('jpg', $quality);
            $size = strlen($encodedImage);
            if ($size <= $targetSize) {
                break;
            }
            $quality -= 5;
        } while ($quality > 10);

        $tempPath = sys_get_temp_dir() . '/' . uniqid('compressed_', true) . '.jpg';
        file_put_contents($tempPath, $encodedImage);

        return new UploadedFile(
            $tempPath,
            $image->getClientOriginalName(),
            'image/jpeg',
            null,
            true // Mark it as a test file so it's accepted by Laravel
        );
    }
}

function formatarDecimal($value)
{
    try {
        return  number_format($value, 2, ',', '.');
    } catch (\Exception $th) {
        throw new \Exception($th, 1);
    }
}

function calcPercentage($value, $percentage)
{
    try {
        return round(($value * $percentage) / 100, 2);
    } catch (\Exception $th) {
        throw new \Exception($th, 1);
    }
}


function renderSingleDecimalTableRow($title, $color = "black", $valor)
{
    echo "<tr>
            <td colspan='5' style='padding-top:10px'></td>
        </tr>
        <tr>
            <td colspan='3'></td>
            <td colspan='2' style='border-top: 2px solid; text-align: left; '>
                <b style='color: {$color}'>{$title} €:</b> " . formatarDecimal($valor) . "
            </td>
        </tr>";
}

/*
  function renderPrintSubTotalSection()
  @param $ValoresPedidosProduto : Collection
  @param $campo : string
  @param $total : float
  @param $subtotal : float
  @param $valorKick : float
  @param $valorMarkup : float
  @returns Object { total, subtotal, html }
*/
function renderPrintSubTotalSection($ValoresPedidosProduto, $campo, $total = 0, $subtotal = 0, $showMarkup = false, $showKick = true, $showTotal = false, $withAts = false)
{
    try {

        $valorKick = 0;
        $valorMarkup = 0;
        $profit = 0;
        $markupKick = 0;


        foreach ($ValoresPedidosProduto as $valor) {
            $subtotal += $valor->$campo;
        }


        foreach ($ValoresPedidosProduto as $valor) {
            $valorKick += $valor->kick > 0 ? calcPercentage($valor->$campo, $valor->kick) : 0;
            $valorMarkup += $valor->markup > 0 ? calcPercentage($valor->$campo, $valor->markup) : 0;
            $markupKick += ($valorMarkup - $valorKick);
            $profit += $valor->profit;
        }

        $subtotal += ($valorMarkup - $valorKick);


        if (isset($valorKick) && $valorKick > 0) {
            $valor = formatarDecimal($valorKick);
            if ($showKick) {
                echo "<tr>
                    <td colspan='3'></td>
                    <td colspan='2' style='border-top: 2px solid; text-align: left;'>
                        <b>Kickback €:</b> {$valor}
                    </td>
                </tr>";
            }
        }

        if (isset($valorMarkup) && $valorMarkup > 0) {
            $valor = formatarDecimal($valorMarkup);
            if ($showMarkup) {
                echo "<tr>
                <td colspan='3'></td>
                <td colspan='2' style='border-top: 2px solid; text-align: left;'>
                    <b>Markup €:</b> {$valor}
                </td>
            </tr>";
            }
        }


        $valor = floor($subtotal * 100) / 100;
        if (($withAts == false && ($showTotal && $valorMarkup <= 0)) or $withAts == true) {
            renderSingleDecimalTableRow('SUBTOTAL', '#000',  $valor);
        }

        return (object)[
            "total" => $subtotal,
            "markup" => $valorMarkup,
            "profit" => $profit + $valorMarkup
        ];
    } catch (\Exception $th) {
        throw new \Exception($th, 1);
    }
}


function renderCabecalhoVouchers($pedido, $usuario)
{

    $imgPath = $usuario->path_image;
    if (preg_match('/_user/', $usuario->path_image)) {
        $imgPath = asset(str_replace('/storage/app/public', '/storage', $usuario->path_image));
    } else {
        $imgPath = asset('/storage/' . $imgPath);
    }

    $logoAts = "storage/LogotipoAtravelCor.png";
    $telFixo = "(+351) 282-457 306";
    $telMobile = "(+351) 282-457 306";

    echo " <div class='container' id='cabeca'>
                <div class='w3-row-padding'>
                    <table>
                        <tr>
                            <td style='width: 50%;'>
                                <img class='w3-margin-bottom' id='editSupplier_img' width='70%' style='width:70%; margin:0 auto;' src='" . asset($logoAts) . "'>
                            </td>
                            <td align='right' style='width: 50%;'>
                                <b><font size='2'>In partnership with:</font></b>
                                <img class='w3-margin-bottom' id='editSupplier_img' width='65%' style='width:65%;'src='{$imgPath}'>
                            </td>
                        </tr>
                    </table>

                    <div class='w3-col l12' style='margin-top:10px; margin-bottom:20px;'>
                        <div class='form-group'>
                            <table width='100%' class='w3-centered'>
                                <tr>
                                    <th style='text-align: left; width:30%;'>TOPICOS E DESCOBERTAS LDA</th>
                                    <td align='right' style='text-align: left; width:25%; font-size: small; width: 30%;'>
                                        Telef.{$telFixo}
                                    </td>
                                    <td style='width: 40%;'></td>
                                </tr>
                                <tr>
                                    <td class='footer' style='text-align: left; color:#24AEC9;'>Av. da Liberdade,</td>
                                    <td style='text-align: left; font-size: small;'>Mobile.{$telMobile}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class='footer' style='text-align: left; color:#24AEC9;'>245, 4ºA </td>
                                    <td style='text-align: left; font-size: small;'>reservations: incoming@atravel.pt</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class='footer' style='text-align: left; color:#24AEC9;'>1250-143 , Lisboa</td>
                                    <td style='text-align: left; font-size: small;'>www.atstravel.pt</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style='text-align: left; font-size: small; color:#24AEC9;'>Licence RNVAT 8019</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style='color:#24AEC9;text-align: left; font-size: small;'>VAT 514 974 567</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class='container'>
                <div class='w3-row-padding w3-padding-32' style='border-top: 2px solid #00bad1;'>
                    <table width='100%' style='margin-bottom: 15px;'>
                        <tr>
                            <td><b>Agency:</b> {$usuario->name}</td>
                            <td><b>Lead Name:</b> {$pedido->lead_name} </td>
                            <td><b>Responsable:</b> {$pedido->responsavel} </td>
                            <td><b>Reference</b> {$pedido->referencia}</td>
                        </tr>
                    </table>
                </div>
            </div>";
}
