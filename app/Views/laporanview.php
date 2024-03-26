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

// $dateline = new DateTime($projects['tahun'], new DateTimeZone('Asia/Jakarta'));
// $dateFormatted =
//     IntlDateFormatter::formatObject(
//         $dateline,
//         'd MMMM y',
//         'id'
//     );
// $tanggalsph = ucwords($dateFormatted);
?>

<body>
    <?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Laporan.xls");
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
    <table>
        <tr></tr>
        <tr>
            <td>Total Jumlah Proyek :</td>
            <td style="text-align: left; font-weight:bold;"> <?= $total ?></td>
            <td>
                Total SPK :
                <?php
                    $spkvalue = [];
                    foreach ($projects as $project) {
                        $spkvalue[] = $projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'];
                    }
                ?>
            </td>
            <td  style="text-align: left; font-weight:bold;"> <?= "Rp." . number_format(array_sum($spkvalue), 0, ',', '.') ?></td>
            <td>
                <?php
                $selesai = 0;
                foreach ($projects as $project) {
                    if ($projectdata[$project['id']]['dateline'] < $projectdata[$project['id']]['now']) {
                        $selesai += 1;
                    }
                }
                ?>
                Total Proyek Selesai : 
            </td>
            <td  style="text-align: left; font-weight:bold;"> <?= $selesai ?></td>
            <td> Total Proyek Dalam Proses :</td>
            <td  style="text-align: left; font-weight:bold;"> <?= $total - $selesai ?></td>
        </tr>
    </table>
    <tr></tr>
    <table class="uk-table uk-table-justify uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th class="uk-width-large">Nama Proyek</th>
                <th class="uk-width-medium">Klien</th>
                <th class="uk-width-medium">Marketing</th>
                <th class="uk-width-medium">Nilai SPK</th>
                <th class="uk-text-center uk-width-medium">Status Proyek</th>
                <th class="uk-text-center uk-width-medium">Tanggal Proyek</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project) { ?>
                <tr>
                    <td class=""><?= $project['name'] ?></td>
                    <td class=""><?= $projectdata[$project['id']]['klien']['rsname'] ?></td>
                    <td class=""><?= $projectdata[$project['id']]['marketing']->username ?></td>
                    <td class=""><?= "Rp." . number_format($projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'], 0, ',', '.')  ?></td>
                    <td class="uk-text-center">
                        <?php if ($projectdata[$project['id']]['dateline'] < $projectdata[$project['id']]['now']) {
                            echo 'Selesai';
                        } else {
                            echo 'Dalam Proses';
                        } ?>
                    </td>
                    <td class="uk-text-center"><?= $project['created_at'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>