<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="/css/theme.css"> -->
    <!-- </link> -->
    <!-- <script src="js/uikit.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.17.11/dist/css/uikit.min.css" /> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/uikit@3.17.11/dist/js/uikit.min.js"></script> -->
    <style>
        table,
        th,
        td {
            /* border: 1pt solid black; */
            border-collapse: collapse;
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

<body>

    <!-- <div style="margin: auto; width: 100%;border: 3px solid green;padding: 10px;">
        <div class="clearfix">
            <img class="img2" src="./img/logo.png" alt="logo" width="100">
            <p>PT. DHARMA PUTRA SEJAHTERA ABADI</p>
            <p>Interior & Furniture Manufaktur</p>
            <p>Jl. Mataraman No.88, Ringinsari, Maguwoharjo, Depok, Sleman, Yogyakarta.</p>
        </div>
    </div> -->

    <table style="width:100%;">
        <tr>
            <th style="width:30%; text-align:right;" rowspan="2"><img style="margin: 5px;" src="./img/logo.png" width="100"></img></th>
            <td style="font-weight:bold" ;>PT. DHARMA PUTRA SEJAHTERA ABADI <br> Interior & Furniture Manufaktur </td>
        </tr>
        <tr>
            <td style="font-size:10pt" ;>Jl. Mataraman No.88, Ringinsari, Maguwoharjo, Depok, Sleman, Yogyakarta. <br> Telepon : <br> Email :<br> Website : </td>
        </tr>
    </table>

    <hr style="border: 1pt solid black;">

    <table style="width:100%;">
        <tr>
            <th style="width:70%; text-align:left; font-weight:normal;">Kepada Yth.</th>
            <th style="width:10%; text-align:left; font-weight:normal; font-size:10pt">Nomor</th>
            <th style="width:20%; text-align:left; font-weight:normal; font-size:10pt"> : 1234</th>
        </tr>
        <tr>
            <td style="font-size:10pt"></td>
            <td style="font-size:10pt;">Perihal</td>
            <td style="font-size:10pt"> : Penawaran</td>
        </tr>
        <tr>
            <td style="font-size:10pt"></td>
            <td style="font-size:10pt">Pekerjaan</td>
            <td style="font-size:10pt"> : Renovasi</td>
        </tr>
        <tr>
            <td style="font-size:10pt"></td>
            <td style="font-size:10pt;">Lokasi</td>
            <td style="font-size:10pt"> : RS Hermina</td>
        <tr>
            <td style="font-size:10pt"></td>
            <td style="font-size:10pt;">Tanggal</td>
            <td style="font-size:10pt"> : 23 Januari 2024</td>
        </tr>
    </table>

    <div class="uk-section uk-section-default uk-margin-remove uk-padding-remove">
        <div class="uk-container uk-container-large">
            <div>
                <div class="uk-text-left">Dengan hormat,</div>
                <p class="uk-text-left">Bersama dengan ini, Kami dari PT, Dharma Putra Sejahtera Abadi, berkeinginan mengajukan penawaran harga furniture Lukisan untuk Rumah Sakit Hermina Manado <br> dengan perincian sebagai berikut :</p>
            </div>

            <table style="width:100%; border: 1pt solid black;">
                <tr style="border: 1pt solid black;">
                    <th style="border: 1pt solid black;" rowspan="2">No</th>
                    <th style="border: 1pt solid black;" rowspan="2">Ruang</th>
                    <th style="border: 1pt solid black;" rowspan="2">Jenis Furniture</th>
                    <td colspan="6" style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black">Dimensi & Qty</td>
                    <td colspan="2" style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:center; border: 1pt solid black">Harga</td>
                    <th style="border: 1pt solid black;" rowspan="2">Keterangan</th>
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
                if (!empty($projects['id'])) {
                    foreach ($rabs as $rab) {
                        if ($rab['projectid'] === $projects['id']) {
                            foreach ($pakets as $paket) {
                                if ($paket['id'] === $rab['paketid']) {
                                    foreach ($mdls as $mdl) {
                                        if ($mdl['id'] === $rab['mdlid']) {
                                            $denom = "";
                                            $price = "";
                                            // $total = [];
                                            if ($mdl['denomination'] === "1") {
                                                $price  = $rab['qty'] * $mdl['price'];
                                                $denom  = "Unit";
                                            } elseif ($mdl['denomination'] === "2") {
                                                $price  = $mdl['length'] * $mdl['price'];
                                                $denom  = "M";
                                            } elseif ($mdl['denomination'] === "3") {
                                                $luas   =   $mdl['height'] * $mdl['length'];
                                                $price  =   $mdl['price'] * $luas;
                                                $denom  = "M2";
                                            } elseif ($mdl['denomination'] === "4") {
                                                $price  = $rab['qty'] * $mdl['price'];
                                                $denom  = "Set";
                                            }
                                            $total[] = $price;
                ?>

                                            <tr class="uk-text-center">
                                                <td style="border: 1pt solid black;"></td>
                                                <td style="border: 1pt solid black;"><?= $projects['name'] ?></td>
                                                <td style="border: 1pt solid black;"><?= $mdl['name'] ?></td>
                                                <td style="border: 1pt solid black;"><?= $mdl['length'] ?></td>
                                                <td style="border: 1pt solid black;"><?= $mdl['width'] ?></td>
                                                <td style="border: 1pt solid black;"><?= $mdl['height'] ?></td>
                                                <td style="border: 1pt solid black;"><?= $mdl['volume'] ?></td>
                                                <td style="border: 1pt solid black;"><?= $denom ?></td>
                                                <td style="border: 1pt solid black;"><?= $rab['qty'] ?></td>
                                                <td style="border: 1pt solid black;"><?= "Rp." . number_format($mdl['price'], 0, ',', '.')  ?></td>
                                                <td style="border: 1pt solid black;"><?= "Rp." . number_format($price, 0, ',', '.')  ?></td>
                                                <td style="border: 1pt solid black;"><?= $mdl['keterangan'] ?></td>
                                            </tr>
                <?php   }
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
                <tr class="uk-text-center">
                    <td style="border: 1pt solid black;">-</td>
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
                    <td style="border: 1pt solid black;"></td>
                </tr>
                <tr style="border: 1pt solid black;">
                    <td style="border: 1pt solid black;"></td>
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
                    <td class="uk-text-right"><?= "Rp." . number_format(array_sum($total), 0, ',', '.')  ?></td>
                </tr>
                <tr style="border: 1pt solid black;">
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
                </tr>
                <tr style="border: 1pt solid black;">
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
                </tr>
                <tr style="border: 1pt solid black;">
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;" class="uk-text-left" colspan="9">PPN</td>
                    <td style="border: 1pt solid black;" class="uk-text-center">11%</td>
                    <td style="border: 1pt solid black;" class="uk-text-right"> 1.500.000</td>
                </tr>
                <tr style="border: 1pt solid black;">
                    <td style="border: 1pt solid black;"></td>
                    <td style="border: 1pt solid black;" class="uk-text-left" colspan="10">Grand Total</td>
                    <td style="border: 1pt solid black;" class="uk-text-right">1.500.000</td>
                </tr>
                <tr style="border: 1pt solid black;">
                    <td style="border: 1pt solid black;"></td>
                    <td class="uk-text-left" colspan="11" style="display: table-cell; vertical-align: inherit; font-weight: bold;text-align:left;">Terbilang : Satu Juta Lima Ratus Ribu Rupiah</td>
                </tr>
            </table>
            <dl class="uk-description-list" style="font-size: 12pt;">
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

            <div class="uk-child-width-1-2 uk-text-center" uk-grid>
                <div>
                    <div class="uk-text-left"></div>
                </div>
                <div>
                    <div class="uk-child-width-1-2 uk-text-center" uk-grid>
                        <div>
                            <div class="uk-text-left"></div>
                        </div>
                        <div>
                            <div class="uk-text-center">Yogyakarta, 13 Januari 2024</div>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-large-top">
                    <div class="uk-text-left"></div>
                </div>
                <div class="uk-margin-large-top">
                    <div class="uk-child-width-1-2 uk-text-center" uk-grid>
                        <div>
                            <div class="uk-text-left uk-width-1-6"></div>
                        </div>
                        <div>
                            <hr>
                            <div class="uk-text-center">Direktur</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</body>

</html>