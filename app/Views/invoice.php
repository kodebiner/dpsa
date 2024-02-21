<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Invoice</title>
    <style>
        @page {
            /* size: 7in 9.25in; */
            size: landscape;
            margin: 27mm 16mm 27mm 16mm;
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

        tr:nth-child(even) {
            /* background-color: #dddddd; */
            background-color: white;
        }

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
    </style>
</head>

<?php
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
?>

<body>
    <table style="height: 70px;">
        <tr>
            <th style="width: 55%;">Ditagihkan Kepada</th>
            <th>Kode Customer : XXXX</th>
        </tr>
        <tr>
            <td><?= $client['rsname'] ?></td>
            <td></td>
        </tr>
        <tr>
            <th>PT. <?= $client['rsname'] ?></th>
            <th>Pengirim</th>
        </tr>
        <tr>
            <td>Alamat : <?= $client['address'] ?></td>
            <td>Alamat : Jl. Mataraman No.88, Ringinsari, Maguwoharjo, Depok, Sleman, Yogyakarta</td>
        </tr>
        <tr style="height: 80px; vertical-align: bottom;">
            <td>NPWP : <?= $client['npwp'] ?></td>
            <td>NPWP :</td>
        </tr>
        <tr>
            <td>PIC Customer :</td>
            <th>Bank Detail :</th>
        </tr>
        <tr>
            <td>Bank Detail</td>
            <td>Bank Detail</td>
        </tr>
        <tr>
            <td>AC NO.</td>
            <td>AC NO.</td>
        </tr>
    </table>

    <div class="row">
        <div class="column" style="width: 50%;">
            <table style="border: 1pt solid black;">
                <tr>
                    <th>INVOICE No.</th>
                    <th>Tanggal</th>
                    <th>Halaman</th>
                </tr>
                <tr>
                    <td>No. XXXX/DPSA/Kode Rs/Bulan/Tahun</td>
                    <td><?= $tanggalspk ?></td>
                    <td>Hal <?= $invoice['hal'] ?> dari 4</td>
                </tr>
            </table>
        </div>
        <div class="column" style="width: 45%; margin-left:37px">
            <table>
                <tr>
                    <th>Email</th>
                    <th>Refrensi PT.DPSA</th>
                </tr>
                <tr>
                    <td>Email : dharmaputra888@yahoo.com, dharmaputra04@yahoo.com</td>
                    <td>MR.Ghozali</td>
                </tr>
            </table>
        </div>
    </div>

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
            <td style="width: 30%;"></td>
            <td style="width: 40%; font-weight:bold;text-align:center;">Nilai SPK <?= "Rp. " . number_format($invoice['total'], 0, ',', '.');" "; ?></td>
            <td style="text-align: center; width: 10%;"></td>
            <td style="text-align: right; width: 10%;"></td>
            <td style="text-align: right; width: 10%;"></td>
        </tr>
        <tr style="border:1pt solid #900; border-top-style: none">
            <td style="width: 30%;">XXXX/JANGUM/RSHJTN/II/2023 <?= $tanggalspk ?></td>
            <td style="width: 40%;">Progress</td>
            <td style="text-align: center; width: 10%;"><?= $invoice['termin'] ?>%</td>
            <td style="text-align: right; width: 10%;">
                <table>
                    <?php foreach ($rabs as $rab) { ?>
                    <tr>
                        <td>
                           <?= "Rp. " . number_format($rab['oriprice'], 0, ',', '.');" "; ?></td>
                        </td>
                    </tr>
                    <?php } ?> 
                </table>
            </td>
            <td style="text-align: right; width: 10%; border: 1pt solid;">
            <table>
                    <?php foreach ($rabs as $rab) { ?>
                    <tr>
                        <td>
                           <?= "Rp. " . number_format($rab['price'], 0, ',', '.');" "; ?></td>
                        </td>
                    </tr>
                    <?php } ?> 
                </table>
            </td>
        </tr>
    </table>

    <div class="row">
        <div class="column" style="width: 52%;  margin-right:40px">
            <table style="border: 1pt solid black;">
                <tr>
                    <th>TANGGAL JATUH TEMPO</th>
                </tr>
                <tr>
                    <td><?= $tanggaldateline ?></td>
                </tr>
            </table>
        </div>
        <div class="column" style="width: 46%;">
            <table style="border: 1pt solid black;">
                <tr>
                    <th style="border: 1pt solid black; width:50%;">Total Harga</th>
                    <td style="border: 1pt solid black; border-right-style:none;">Rp.</td>
                    <td style="border: 1pt solid black; border-left-style:none; text-align:right">60.000</td>
                </tr>
                <tr>
                    <th style="border: 1pt solid black; width:50%;">Diskon 0%</th>
                    <td style="border: 1pt solid black; border-right-style:none;">Rp.</td>
                    <td style="border: 1pt solid black; border-left-style:none; text-align:right">-</td>
                </tr>
                <tr>
                    <th style="border: 1pt solid black; width:50%;">Total Harga Dikurangi</th>
                    <td style="border: 1pt solid black; border-right-style:none;">Rp.</td>
                    <td style="border: 1pt solid black; border-left-style:none; text-align:right">60.000</td>
                </tr>
                <tr>
                    <th style="border: 1pt solid black; width:50%;">BIAYA KIRIM / SETTING</th>
                    <td style="border: 1pt solid black; border-right-style:none;">Rp.</td>
                    <td style="border: 1pt solid black; border-left-style:none; text-align:right">-</td>
                </tr>
            </table>
        </div>
    </div>

    <table style="border: 1pt solid black; background-color: #dddddd;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none;  width:22.2%;">Jumlah Ditagihkan (DPP)</th>
            <td style="border: 1pt solid black; border-right-style:none;">Rp.</td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right">60.000</td>
        </tr>
    </table>

    <table style="border: 1pt solid black; background-color: #dddddd; margin-top:5px;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none;  width:22.2%;">PPN 11%</th>
            <td style="border: 1pt solid black; border-right-style:none;">Rp.</td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right">60.000</td>
        </tr>
    </table>

    <table style="border: 1pt solid black; margin-top:5px;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none;  width:22.2%;">PPH 23 2% Rp. -</th>
            <td style="border: 1pt solid black; border-right-style:none;"></td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right"></td>
        </tr>
    </table>

    <table style="border: 1pt solid black; margin-top:5px;">
        <tr>
            <th style="border: 1pt solid black; border-right-style:none;  width:55%;"></th>
            <th style="border: 1pt solid black; border-right-style:none; border-left-style:none;  width:22.2%;">TOTAL / JUMLAH HARGA DI BAYAR</th>
            <td style="border: 1pt solid black; border-right-style:none;">Rp.</td>
            <td style="border: 1pt solid black; border-left-style:none; text-align:right">66,600,000</td>
        </tr>
    </table>

    <table style="width: 50%;">
        <tr>
            <td style="text-align: center;">PT DHARMA PUTRA SEJAHTERA ABADI (PT. DPSA)</td>
        </tr>
        <tr>
            <td style="height: 60px;"></td>
        </tr>
        <tr>
            <td style="text-align: center; text-decoration: underline;">Mr.XXXX</td>
        </tr>
        <tr>
            <td style="text-align: center;">Direktur</td>
        </tr>
    </table>

</body>

</html>