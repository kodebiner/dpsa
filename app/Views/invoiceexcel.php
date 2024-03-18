<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Invoice</title>
    <link rel="stylesheet" href="/css/theme.css">
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.17.11/dist/js/uikit.min.js"></script>
    </link>
    <style>
        /* @page { */
        /* size: 7in 9.25in; */
        /* size: landscape; */
        /* margin: 27mm 16mm 27mm 16mm; */
        /* } */

        @page {
            size: landscape;
            margin-top: 2.54cm;
            margin-bottom: 2.54cm;
            margin-left: 3.175cm;
            margin-right: 3.175cm;
        }

        html {
            font-size: 5pt;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            /* border: 1px solid #dddddd; */
            text-align: left;
            padding: 8px;
        }

        /* tr:nth-child(even) {
            /* background-color: #dddddd; */
        /* background-color: white; */
        /* } */

        * {
            box-sizing: border-box;
        }

        .row {
            margin-left: -5px;
            margin-right: -5px;
            margin-top: 5px;
        }

        .column {
            float: left;
            /* width: 50%; */
            padding: 5px;
        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        #watermark {
            background-blend-mode: lighten;
            background-image: url('/img/logo.png');
            background-repeat: no-repeat;
            background-size: 600px;
            background-position: center;
            background-color: rgba(255, 255, 255, 0.5);
        }

        #coverblack {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .kop tr {
            line-height: 0px;
        }
    </style>
</head>

<?php
if(!empty($invoice)){
    $dateTimeObj = new DateTime($invoice['dateinv'], new DateTimeZone('Asia/Jakarta'));
    $dateFormatted =
        IntlDateFormatter::formatObject(
            $dateTimeObj,
            'd MMMM y',
            'id'
        );
    $tanggalspk = ucwords($dateFormatted);

    $dateline = new DateTime($invoice['dateline'], new DateTimeZone('Asia/Jakarta'));
    $dateFormatted =
        IntlDateFormatter::formatObject(
            $dateline,
            'd MMMM y',
            'id'
        );
    $tanggaldateline = ucwords($dateFormatted);
}else{
    $tanggalspk         = "";
    $tanggaldateline    = "";
}

?>

<body id="watermark">

    <!-- <table style="width:100%">
        <tr>
            <td style="text-align:right; width:45%; height: 100px;"><img src="img/logo.png" width="100px" height="100px"></td>
            <td>
                <font style="font-weight:bold; font-size: 8px;"> PT. DHARMA PUTRA SEJAHTERA ABADI</font><br>
                <font style="font-weight:bold; font-size: 8px;"> Interior & Furniture Manufaktur</font><br>
                <font style="font-size: 5px;">Jl. Mataraman No.88, Ringinsari, Maguwoharjo, Depok, Sleman, Yogyakarta.</font><br>
                <font style="font-size: 5px;">Telephone : (0274) 2800089 Fax : (0274) 4332246</font><br>
                <font style="font-size: 5px;">Email : dpsa@gmail.com</font><br>
                <font style="font-size: 5px;">Website : dharmaputra04.com</font><br>
            </td>
        </tr>
    </table> -->

    <!-- <hr> -->

    <?php
    $noinv = $invoice['noinv'];
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=invoice'.$noinv.'.xls");
	?>

    <table style="height: 70px;">
        <tr>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th style="width: 55%;">Ditagihkan Kepada</th>
            <th></th>
            <th style="padding-left:20px">Kode Customer : <?php if(!empty($client)){ echo $client['rscode'] ;} ?></th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>PT. <?php if(!empty($client)){ echo $client['rsname'] ;} ?></th>
            <td></td>
            <th style="padding-left:20px">Pengirim</th>
        </tr>
        <tr>
            <td>Alamat :<?php if(!empty($client)){ echo $client['address'] ;} ?></td>
            <td></td>
            <td style="padding-left:20px">Alamat : <?php if(!empty($invoice)){ echo $invoice['alamat'] ;} ?></td>
        </tr>
        <tr style="height: 80px; vertical-align: bottom;">
            <td>NPWP : <?php if(!empty($client)){ echo $client['npwp'] ;} ?></td>
            <td></td>
            <td style="padding-left:20px">NPWP : <?php if(!empty($invoice)){ echo $invoice['npwpdpsa'] ;} ?></td>
        </tr>
        <tr>
            <td>PIC Customer : <?php if(!empty($invoice)){ echo $invoice['pic'] ;} ?></td>
            <td></td>
            <th style="padding-left:20px">Bank Detail</th>
        </tr>
        <tr>
            <td>Bank Detail : <?php if(!empty($client)){ echo $client['bank'] ;} ?></td>
            <td></td>
            <td style="padding-left:20px">Bank Detail :<?php if(!empty($invoice)){ echo $invoice['refbank'] ;} ?></td>
        </tr>
        <tr>
            <td>AC NO. <?php if(!empty($client)){ echo $client['no_rek'] ;} ?></td>
            <td></td>
            <td style="padding-left:20px">AC NO. <?php if(!empty($invoice)){ echo $invoice['refacc'] ;} ?></td>
        </tr>
    </table>

    <table style="height: 70px;">
        <tr>
            <th></th>
        </tr>
    </table>

    <div class="row">
        <div class="column" style="width: 50%;">
            <table>
                <tr>
                    <th style="border: 1pt solid black; border-bottom-style: none;">INVOICE No.</th>
                    <th style="border: 1pt solid black; border-bottom-style: none;">Tanggal</th>
                    <th style="padding-left:20px">Email</th>
                    <th></th>
                    <th>Refrensi PT.DPSA</th>
                </tr>
                <tr>
                    <td style="border: 1pt solid black; border-top-style: none;">No. <?php if(!empty($invoice)){ echo $invoice['noinv'] ;} ?></td>
                    <td style="border: 1pt solid black; border-top-style: none;"><?= $tanggalspk ?></td>
                    <td style="padding-left:20px">Email : <?php if(!empty($invoice)){ echo $invoice['email'] ;} ?></td>
                    <td></td>
                    <td><?php if(!empty($invoice)){ echo $invoice['referensi'] ;} ?></td>
                </tr>
            </table>
        </div>
    </div>

    <table>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table style="margin-top: 5px;">
        <tr style="border: 1pt solid;">
            <th style="text-align: center; width: 30%; border: 1pt solid;">No./Tanggal SPK</th>
            <th style="text-align: center; width: 40%; border: 1pt solid;">Deskripsi</th>
            <th style="text-align: center; width: 10%; border: 1pt solid;">Termin Invoice</th>
            <th style="text-align: center; width: 10%; border: 1pt solid;">Harga Satuan <br> @Rp.</th>
            <th style="text-align: center; width: 10%; border: 1pt solid;">Total Harga <br> (Rp.)</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr style="border:1pt solid #900; border-bottom-style: none">
            <td style="width: 30%; border:1pt solid; border-bottom-style: none;"></td>
            <td style="width: 40%; font-weight:bold;text-align:center;">Nilai SPK <?php if(!empty($invoice)){ echo "Rp. " . number_format($invoice['total'], 0, ',', '.');" ";}  ?></td>
            <td style="text-align: center; border:1pt solid; border-bottom-style: none; width: 10%;"></td>
            <td style="text-align: right; border:1pt solid; border-bottom-style: none; width: 10%;"></td>
            <td style="text-align: right; border:1pt solid; border-bottom-style: none; width: 10%;"></td>
        </tr>
        <tr style="border:1pt solid; border-top-style: none;">
            <td style="width: 30%; border:1pt solid; border-top-style: none;"><?php if(!empty($invoice)){ echo $invoice['no_spk'] ;} ?></td>
            <td style="width: 40%; border:1pt solid; border-top-style: none; text-align:center;">Progress <?php if(!empty($invoice)){ echo $invoice['progress']."%" ;} ?> <?php if(!empty($projects)){ echo $projects['name'] ;} ?></td>
            <td style="text-align: center; border:1pt solid; border-top-style: none; width: 10%;"><?php if(!empty($invoice)){ echo $invoice['termin']."%" ;} ?></td>
            <td style="text-align: center; border:1pt solid; border-top-style: none; width: 10%;"><?php if(!empty($invoice)){ echo "Rp. " . number_format($invoice['total'], 0, ',', '.');" ";}  ?></td>
            <td style="text-align: right; border:1pt solid; border-top-style: none; width: 10%;"><?php if(!empty($invoice)){ echo "Rp. " . number_format((((int)$invoice['termin'] / 100) * $invoice['total']), 0, ',', '.');" ";}  ?></td>
        </tr>
    </table>

    <div class="row">
        <div class="column" style="width: 46%;">
            <table>
                <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="column">
            <table>
                <tr>
                    <th style="border: 1pt solid black; border-bottom-style:none;">TANGGAL JATUH TEMPO</th>
                    <th></th>
                    <th style="border: 1pt solid black; width:50%;">Total Harga</th>
                    <td style=" border: 1pt solid black; border-right-style: none; text-align:left"></td>
                    <td style=" border: 1pt solid black; text-align:right; border-left-style: none;"><?php if(!empty($invoice)){ echo "Rp. ".number_format((($invoice['termin'] / 100) * $invoice['total']), 0, ',', '.');" ";}  ?></td>
                </tr>
                <tr>
                    <td style="border: 1pt solid black; border-top-style:none;"><?= $tanggaldateline ?></td>
                </tr>
            </table>
        </div>

        <div class="column" style="width: 46%;">
            <table>
                <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>

    <?php
        if(!empty($rabcustom)){
            foreach ($rabcustom as $cusrab) { ?>
                    <table style="border: 1pt solid black; background-color: #dddddd; margin-top:5px;">
                        <tr>
                            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
                            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none;  width:22.2%;"><?php if(!empty($cusrab)){ echo $cusrab['name'];}  ?></th>
                            <td style="border: 1pt solid black; border-right-style:none;"></td>
                            <td style="border: 1pt solid black; border-left-style:none; text-align:right"><?php if(!empty($cusrab)){ echo number_format($cusrab['price'], 0, ',', '.');" ";}  ?></td>
                        </tr>
                    </table>
            <?php }
        } 
    ?>

    <table style="border: 1pt solid black; background-color: #dddddd; margin-top:5px;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none; width:22.2%;">JUMLAH DITAGIHKAN (DPP)</th>
            <td style="border: 1pt solid black; border-left-style: none;"></td>
            <td style="border: 1pt solid black; border-right-style:none;"></td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right"><?php if(!empty($invoice)){ echo "Rp. ".number_format((((int)$invoice['termin'] / 100) * $invoice['total']), 0, ',', '.');" ";}  ?></td>
        </tr>
    </table>

    <table style="border: 1pt solid black; background-color: #dddddd; margin-top:5px;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none;  width:22.2%;">PPN <?php if(!empty($invoice)){ echo $invoice['ppn'];}  ?> %</th>
            <td style="border: 1pt solid black; border-left-style: none;"></td>
            <td style="border: 1pt solid black; border-right-style:none;"></td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right"><?php if(!empty($invoice)){ echo "Rp. ".number_format($invoice['totalterm'], 0, ',', '.');" ";}  ?></td>
        </tr>
    </table>

    <table style="border: 1pt solid black; margin-top:5px;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none;  width:22.2%;">PPH 23 <?php if(!empty($invoice)){ echo $invoice['pph'];}  ?>% </th>
            <td style="border: 1pt solid black; border-left-style: none;"></td>
            <td style="border: 1pt solid black; border-right-style:none;"></td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right"><?php if(!empty($invoice)){ echo "Rp. ".number_format($invoice['pphtermin'], 0, ',', '.');" ";}  ?></td>
        </tr>
    </table>

    <table style="border: 1pt solid black; margin-top:5px; background-color: #dddddd;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none; width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; font-weight:bold; border-left-style:none;">TOTAL / JUMLAH HARGA DI BAYAR</th>
            <td style="border: 1pt solid black; border-left-style: none;"></td>
            <td style="border: 1pt solid black; border-right-style:none;"></td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right;"><?php if(!empty($invoice)){ echo "Rp. ".number_format($invoice['totalterm'] + $invoice['pphtermin'] + array_sum(array_column($rabcustom,'price')) + (((int)$invoice['termin'] / 100) * $invoice['total']), 0, ',', '.');" ";}  ?></td>
        </tr>
    </table>

    <table style="width: 50%; margin-top:50px">
        <tr>
            <td></td>
        </tr>
        <tr>
        <tr>
            <td style="text-align: center;">PT DHARMA PUTRA SEJAHTERA ABADI (PT. DPSA)</td>
        </tr>
        <tr>
            <td style="height: 60px;"></td>
        </tr>
        <tr>
            <td style="text-align: center; text-decoration: underline;">Mr.<?php if(!empty($invoice)){ echo $invoice['direktur'];}  ?></td>
        </tr>
        <tr>
            <td style="text-align: center;">Direktur</td>
        </tr>
    </table>

</body>

</html>