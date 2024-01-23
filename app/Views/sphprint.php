<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        h1,
        h3,
        h5,
        h6 {
            text-align: center;
            padding-right: 200px;
        }

        .row {
            margin-top: 20px;
        }

        .keclogo {
            font-size: 24px;
            font-size: 3vw;
        }

        .kablogo {
            font-size: 2vw;
        }

        .alamatlogo {
            font-size: 1.5vw;
        }

        .kodeposlogo {
            font-size: 1.7vw;
        }

        #tls {
            text-align: right;
        }

        .alamat-tujuan {
            margin-left: 50%;
        }

        .garis1 {
            border-top: 3px solid black;
            height: 2px;
            border-bottom: 1px solid black;
        }

        #logo {
            margin: auto;
            margin-left: 50%;
            margin-right: auto;
        }

        #tempat-tgl {
            margin-left: 120px;
        }

        #camat {
            text-align: center;
        }

        #nama-camat {
            margin-top: 100px;
            text-align: center;
        }

        table {
            font-family: arial, sans-serif;
            /* border-collapse: collapse; */
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>

<body>

    <div>
        <header>
            <div class="row">
                <div id="img" class="col-md-3">
                    <img id="logo" src="https://getasanbersinar.files.wordpress.com/2016/02/logo-kabupaten-semarang-jawa-tengah.png" width="140" height="160" />
                </div>
                <div id="text-header" class="col-md-9">
                    <h3 class="kablogo">PEMERINTAH KABUPATEN SEMARANG</h3>
                    <h1 class="keclogo"><strong>KECAMATAN BERGAS</strong></h1>
                    <h6 class="alamatlogo">Jl. Soekarno-Hatta, No. 68, Telepon/Faximile (0298) 523024</h6>
                    <h5 class="kodeposlogo"><strong>BERGAS 50552</strong></h5>
                </div>
            </div>
        </header>

        <div class="container">
            <hr class="garis1" />
            <div id="alamat" class="row">
                <div id="lampiran" class="col-md-6">
                    Nomor : 005 / <br />
                    Lampiran : - <br />
                    Perihal : Undangan
                </div>
                <div id="tgl-srt" class="col-md-6">
                    <p id="tls">Bergas, 30 April 2018</p>

                    <p class="alamat-tujuan">Kepada Yth. :<br />
                        Kepala Desa</p>

                    <p class="alamat-tujuan">se - Kecamatan Bergas
                    </p>
                </div>
            </div>
            <div id="pembuka" class="row">&emsp; &emsp; &emsp; Menindak lanjuti surat dari Sekretariat Daerah Kabupaten Semarang Nomor : 005/001819/2018 perihal Peraturan Baru mengenai Badan Permusyawaratan Desa (BPD) berdasarkan Perda Nomor 4 Tahun 2018 dan Perbup Nomor 21 Tahun 2018 serta Tahapan Pengisian Anggota BPD, bersama ini kami mengharap atas kehadiran saudara besok pada :</div>
            <div id="tempat-tgl">
                <table>
                    <tr>
                        <td>Hari</td>
                        <td>:</td>
                        <td>Kamis</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>28 Juni 2018</td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td>:</td>
                        <td>08.00 WIB</td>
                    </tr>
                    <tr>
                        <td>Tempat</td>
                        <td>:</td>
                        <td>Aula PP PAUD dan Dikmas Jawa Tengah Jl. Diponegoro No 250 Ungaran</td>
                    </tr>
                    <tr>
                        <td>Catatan</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                </table>
            </div>
            <div id="penutup">Demikian untuk menjadikan perhatian dan atas kehadirannya diucapkan terimakasih.</div>
            <div id="ttd" class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <p id="camat"><strong>CAMAT BERGAS</strong></p>
                    <div id="nama-camat"><strong><u>TRI MARTONO, SH, MM</u></strong><br />
                        Pembina Tk. I<br />
                        NIP. 196703221995031001</div>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="">Nama</th>
                <th class="">Panjang</th>
                <th class="">Lebar</th>
                <th class="">Tinggi</th>
                <th class="">Volume</th>
                <th class="">Satuan</th>
                <th class="">Jml Pesanan</th>
                <th class="">Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($projects['id'])) {
                foreach ($rabs as $rab) {
                    if ($rab['projectid'] === $projects['id']) {
                        foreach ($pakets as $paket) {
                            if ($paket['id'] === $rab['paketid']) {
                                foreach ($mdls as $mdl) {
                                    if ($mdl['id'] === $rab['mdlid']) { ?>
                                        <tr>
                                            <td class=""><?= $mdl['name'] ?></td>
                                            <td class="uk-text-center"><?= $mdl['length'] ?></td>
                                            <td class="uk-text-center"><?= $mdl['width'] ?></td>
                                            <td class="uk-text-center"><?= $mdl['height'] ?></td>
                                            <td class="uk-text-center"><?= $mdl['volume'] ?></td>
                                            <td class="uk-text-center"><?= $mdl['denomination'] ?></td>
                                            <td class="uk-text-center"><?= $rab['qty'] ?></td>
                                            <td class="uk-text-center"><?= "Rp " . number_format($mdl['price'], 0, ',', '.')  ?></td>
                                        </tr>
            <?php
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ?>
        </tbody>
    </table>
</body>

</html>