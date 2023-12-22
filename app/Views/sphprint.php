<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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

        /* tr:nth-child(even) {
            background-color: #dddddd;
        } */
    </style>
</head>

<body>
    <h2>Data SPH Proyek <?=$projects['name']?></h2>
    <h4>Klien : <?=$client['rsname']?></h4>
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
                                            <td class="uk-text-center"><?="Rp " . number_format($mdl['price'],0,',','.')  ?></td>
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