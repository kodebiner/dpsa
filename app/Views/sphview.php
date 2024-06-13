<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen SPH</title>
    <style>
        html {
            font-size: 8pt
        }

        hr {
            margin: 0;
        }

        table,
        th,
        td {
            /* border: 1pt solid black; */
            border-collapse: collapse;
            padding: 3px;
        }

        .img2 {
            float: left;
            text-align: center;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
            /* margin: auto;  */
            width: 50%;
        }
    </style>
</head>

<?php

$dateline = new DateTime($projects['tahun'], new DateTimeZone('Asia/Jakarta'));
$dateFormatted =
    IntlDateFormatter::formatObject(
        $dateline,
        'd MMMM y',
        'id'
    );
$tanggalsph = ucwords($dateFormatted);

// $date = date_create($projects['tahun']);
// $Year   = date_format($date, 'Y');
// $number = date_format($date, 'n');
// function sphnum($number)
// {
//     $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
//     $returnValue = '';
//     while ($number > 0) {
//         foreach ($map as $roman => $int) {
//             if ($number >= $int) {
//                 $number -= $int;
//                 $returnValue .= $roman;
//                 break;
//             }
//         }
//     }
//     return $returnValue;
// }
// $roman = sphnum($number);

// $sphnum = str_pad($projects['no_sph'], 3, '0', STR_PAD_LEFT);

// $numsph = $sphnum . "/DPSA/" . $sphdata['marketing'] . "/SPH/" . $client['rscode'] . "/" . $roman . "/" . $Year;
?>

<body>
    <?php
    $nosph = $projects['no_sph'];
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=SPH_$nosph.xls");
	?>
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

    <!-- <hr style="border: 1pt solid black;"> -->

    <table style="width:100%; margin-top:10px">
        <tr>
            <th style="width:60%; text-align:left; font-weight:normal;">Kepada Yth.</th>
            <th></th>
            <th style="width:10%; text-align:left; font-weight:normal;">Nomor</th>
            <th style="width:30%; text-align:left; font-weight:normal;"> : <?= $nosph ?></th>
        </tr>
        <tr>
            <td>Direktur RS <?= $client['rsname'] ?></td>
            <td></td>
            <td>Perihal</td>
            <td> : Surat Penawaran Harga</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Kode Marketing</td>
            <td> :  <?= $sphdata['marketing'] ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Pekerjaan</td>
            <td> : <?= $projects['name'] ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Lokasi</td>
            <td> : <?= $client['rsname'] ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Tanggal</td>
            <td> : <?= $tanggalsph ?></td>
        </tr>
    </table>

    <div>
        <div>
            <div>
                <div>
                    <font style="font-weight: bold; text-decoration: underline;"><?= $sphdata['clientpic'] ?> Kepala Jangum</font> <br><br>Dengan hormat,
                </div>
                <p>Bersama dengan ini, Kami dari PT, Dharma Putra Sejahtera Abadi, berkeinginan mengajukan penawaran produk untuk Rumah Sakit <?= $client['rsname'] ?> <br> dengan perincian sebagai berikut :</p>
            </div>

            <table style="width:100%; border: 1pt solid black;">
                <tr style="border: 1pt solid black;">
                    <!-- <th style="border: 1pt solid black; width: 50px;" rowspan="2">No</th> -->
                    <th style="border: 1pt solid black;" rowspan="2">Ruang</th>
                    <th style="border: 1pt solid black;" rowspan="2">Jenis Furniture</th>
                    <td colspan="6" style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black">Dimensi & Qty</td>
                    <td colspan="2" style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black">Harga</td>
                    <th rowspan="2" style="border: 1pt solid black;">Keterangan</th>
                </tr>
                <tr style="border: 1pt solid black;">
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">P</td>
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">L</td>
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">T</td>
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">VOL</td>
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">SAT</td>
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">JML</td>
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">Satuan</td>
                    <td style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black;">Total</td>
                </tr>

                <?php
                // $x = 1;
                if(!empty($sphrabs)){
                    foreach ($sphrabs as $mdl){ 
                        $denom = "";
                        $price = "";
                        // $total = [];
                        if ($mdl['denom'] === "1") {
                            // $price  = $mdl['qty'] * $mdl['price'];
                            $denom  = "Unit";
                        } elseif ($mdl['denom'] === "2") {
                            // $price  = $mdl['length'] * $mdl['price'];
                            $denom  = "M";
                        } elseif ($mdl['denom'] === "3") {
                            // $luas   =   $mdl['height'] * $mdl['length'];
                            $price  =   $mdl['price'] * $luas;
                            $denom  = "M2";
                        } elseif ($mdl['denom'] === "4") {
                            // $price  = $mdl['qty'] * $mdl['price'];
                            $denom  = "Set";
                        }?>
                    
                        <tr class="uk-text-center">
                        <!-- <td style="border: 1pt solid black; column-width: 50px; text-align:center;"></?php echo $x++; ?></td> -->
                        <td style="border: 1pt solid black;"><?= $mdl['kategori'] ?></td>
                        <td style="border: 1pt solid black;"><?= $mdl['name'] ?></td>
                        <td style="border: 1pt solid black; text-align:center;"><?= $mdl['length'] ?></td>
                        <td style="border: 1pt solid black; text-align:center;"><?= $mdl['width'] ?></td>
                        <td style="border: 1pt solid black; text-align:center;"><?= $mdl['height'] ?></td>
                        <td style="border: 1pt solid black; text-align:center;"><?= $mdl['volume'] ?></td>
                        <td style="border: 1pt solid black; text-align:center;"><?= $mdl['denom'] ?></td>
                        <td style="border: 1pt solid black; text-align:center;"><?= $mdl['qty'] ?></td>
                        <td style="border: 1pt solid black;"><?= "Rp." . number_format($mdl['mdlprice'], 0, ',', '.')  ?></td>
                        <td style="border: 1pt solid black;"><?= "Rp." . number_format($mdl['price'], 0, ',', '.')  ?></td>
                        <td style="border: 1pt solid black;"><?= $mdl['keterangan'] ?></td>
                        </tr>
                    <?php }
                } ?>
                

                <tr class="uk-text-center">
                    <!-- <td style="border: 1pt solid black;"></td> -->
                    <td style="border: 1pt solid black;">&nbsp;</td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                </tr>

                <?php if (!empty($custom)) { ?>
                    <tr class="uk-text-center">
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black; font-weight: bold;">KUSTOM PESANAN</td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                        <td style="border: 1pt solid black;"></td>
                    </tr>
                    <?php foreach ($custom as $cusrab) { ?>
                        <tr style="border: 1pt solid black;">
                            <!-- <td style="border: 1pt solid black;"></td> -->
                            <td class="uk-text-left"></td>
                            <td style="border: 1pt solid black;"><?= $cusrab['name'] ?></td>
                            <td style="border: 1pt solid black; text-align:center;"><?= $cusrab['length'] ?></td>
                            <td style="border: 1pt solid black; text-align:center;"><?= $cusrab['width'] ?></td>
                            <td style="border: 1pt solid black; text-align:center;"><?= $cusrab['height'] ?></td>
                            <td style="border: 1pt solid black; text-align:center;"><?= $cusrab['volume'] ?></td>
                            <td style="border: 1pt solid black; text-align:center;">
                                <?php if ($cusrab['denomination']=== "1") {
                                    echo "Unit";
                                } elseif ($cusrab['denomination']=== "2") {
                                    echo "M";
                                } elseif ($cusrab['denomination']=== "3") {
                                    $price  =   $mdl['price'] * $luas;
                                    echo "M2";
                                } elseif ($cusrab['denomination']=== "4") {
                                    echo "Set";
                                }?>
                            </td>
                            <td style="border: 1pt solid black; text-align:center;"><?= $cusrab['qty'] ?></td>
                            <td style="border: 1pt solid black;">
                            <?php if (!empty($cusrab['price'])) {
                                    echo "Rp." . number_format($cusrab['price'], 0, ',', '.');
                                } ?>
                            </td>
                            <td style="border: 1pt solid black;">
                            <?php if (!empty($cusrab['price'])) {
                                    echo "Rp." . number_format($cusrab['price'] * $cusrab['qty'], 0, ',', '.');
                                } ?>
                            </td>
                            <td style="border: 1pt solid black; text-align:right;"></td>
                        </tr>
                    <?php } ?>
                <?php } ?>


                <tr style="border: 1pt solid black;">
                    <!-- <td style="border: 1pt solid black;"></td> -->
                    <td class="uk-text-left">Total</td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black; text-align:right;">
                        <?php if (!empty($sphdata)) {
                            echo "Rp." . number_format($sphdata['total'], 0, ',', '.');
                        } ?>
                    </td>
                </tr>
                <!-- <tr style="border: 1pt solid black;">
                    <td style="border: 1pt solid black;"></td>
                    <td class="uk-text-left">Biaya Kirim</td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td class="uk-text-right">500.000</td>
                </tr> -->
                <!-- <tr style="border: 1pt solid black;">
                    <td style="border: 1pt solid black;"></td>
                    <td class="uk-text-left"> Total + Biaya Kirim</td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;"></td>
                    <td class="uk-text-right"> 1.500.000</td>
                </tr> -->
                <tr style="border: 1pt solid black;">
                    <!-- <td style="border: 1pt solid black;"></td> -->
                    <td style="border: 1pt solid black;" class="uk-text-left" colspan="9">PPN</td>
                    <td style="border: 1pt solid black; text-align:right" class="uk-text-center"><?= $sphdata['ppn'] ?> %</td>
                    <td style="border: 1pt solid black; text-align:right" class="uk-text-right"><?= "Rp." .number_format($sphdata['ppnval'], 0, ',', '.'); ?></td>
                </tr>
                
                <tr style="border: 1pt solid black;">
                    <!-- <td style="border: 1pt solid black;"></td> -->
                    <td style="border: 1pt solid black;" class="uk-text-left" colspan="10">Grand Total</td>
                    <td style="border: 1pt solid black; text-align:right" class="uk-text-right"><?= "Rp." .number_format($sphdata['totalsph'], 0, ',', '.'); ?></td>
                </tr>
                <tr style="border: 1pt solid black;">
                    <!-- <td style="border: 1pt solid black;"></td> -->
                    <td class="uk-text-left" colspan="11" style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:left;">Terbilang : <?= ucwords($sphdata['terbilang']) ?></td>
                </tr>
            </table>

            <dl>
                <dt>Catatan :</dt>
                <dd>Harga diatas sudah termasuk Biaya transport</dd>
                <dd>Harga diatas sudah termasuk Pajak PPN</dd>
                <dd>Harga diatas tidak termasuk biaya setting</dd>
                <dt>Tatacara Pembayaran :</dt>
                <dd>Progres I (30%) : Alat umum (furniture) masih dalam proses produksi dan masih dalam proses rekanan/Vendor JV</dd>
                <dd>Progres III (35%) : Alat umum (furniture) sebagian sudah terkirim dan terpasang di lokasi milik RS Hermina, dan sebagian lainnyaa masih dalam proses produksi dan masih berada di lokasi rekanan/Vendor JV</dd>
                <dd>Progres III (30%) : Alat umum (furniture) seluruhnya sudah terkirim dan terpasang di lokasi RS Hermina</dd>
                <dd>Retensi (5%) : Tagihan sertelah masa retensi selesai, yaitu 3 bulan setelah Alat umum (furniture) selesai terpasang 100% yang didasarkan oleh Berita Acara Serah Terima (BAST) dan telah ditandatangani oleh perwakilan RS Hermina dan rekanan / Vendor JV</dd>
            </dl>

            <div>
                <p class="uk-text-left">Demikian kami sampaikan, atas perhatian dan kerjasama yang telah terjalin dengan baik selama ini, kami ucapkan banyak terima kasih</p>
            </div>

            <div>
                <table style="width:100%; text-align:left;">
                    <tr>
                        <th style="width:70%"></th>
                        <th>Yogyakarta, 13 Januari 2024</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center;">
                            <font style="text-decoration: underline;"> <?= $sphdata['direktur'] ?> </font> <br> Direktur
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</body>

</html>