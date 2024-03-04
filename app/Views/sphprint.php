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

<body>

    <table style="width:100%; margin-top:0;">
        <tr>
            <th style="width:30%; text-align:right;" rowspan="2"><img style="margin: 5px;" src="./img/logo.png" width="100"></img></th>
            <td style="font-weight:bold; padding:0px;">PT. DHARMA PUTRA SEJAHTERA ABADI <br> Interior & Furniture Manufaktur </td>
        </tr>
        <tr>
            <td>Jl. Mataraman No.88, Ringinsari, Maguwoharjo, Depok, Sleman, Yogyakarta. <br> Telepon : (0274) 2800089 Fax : (0274) 4332246<br> Email : dharmaputra888@yahoo.com, dharmaputra04@yahoo.com<br> Website : www.dharmaputrainterior.com</td>
        </tr>
    </table>

    <hr style="border: 1pt solid black;">

    <table style="width:100%;">
        <tr>
            <th style="width:60%; text-align:left; font-weight:normal;">Kepada Yth.</th>
            <th style="width:10%; text-align:left; font-weight:normal;">Nomor</th>
            <th style="width:30%; text-align:left; font-weight:normal;"> : 006/DPSA/ARP/PNWRN/RSHMND/2024</th>
        </tr>
        <tr>
            <td>Direktur RS Hermina Manado</td>
            <td>Perihal</td>
            <td> : Surat Penawaran Harga</td>
        </tr>
        <tr>
            <td></td>
            <td>Pekerjaan</td>
            <td> : Lukisan Dinding</td>
        </tr>
        <tr>
            <td></td>
            <td>Lokasi</td>
            <td> : RS Hermina Manado</td>
        <tr>
            <td></td>
            <td>Tanggal</td>
            <td> : 13 Januari 2024</td>
        </tr>
    </table>

    <div>
        <div>
            <div>
                <div>
                    Up. Ibu Bunga(Kepala Jangum) <br><br>Dengan hormat,
                </div>
                <p>Bersama dengan ini, Kami dari PT, Dharma Putra Sejahtera Abadi, berkeinginan mengajukan penawaran harga furniture Lukisan untuk Rumah Sakit Hermina Manado <br> dengan perincian sebagai berikut :</p>
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
                                            $total[] = $price; ?>
                                            <tr class="uk-text-center">
                                                <td style="border: 1pt solid black;">1.</td>
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
                    <td style="border: 1pt solid black;">
                        <?php if (!empty($total)) {
                            echo "Rp." . number_format(array_sum($total), 0, ',', '.');
                        } ?>
                    </td>
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

            <table style="width:100%">
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
                    <td style="text-align: center;">Direktur</td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>