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
    <?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Laporan.xls");
	?>

    <table class="uk-table uk-table-justify uk-table-middle uk-table-divider">
        <tr></tr>
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

    <table>
        <tr></tr>
        <tr>
            <td style="font-weight:bold;">Total Jumlah Proyek</td>
            <td style="text-align: left; font-weight:bold;">: <?= $total ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">
                <?php
                $selesai = 0;
                foreach ($projects as $project) {
                    if ($projectdata[$project['id']]['dateline'] < $projectdata[$project['id']]['now']) {
                        $selesai += 1;
                    }
                }
                ?>
                Total Proyek Selesai 
            </td>
            <td style="text-align: left; font-weight:bold;">: <?= $selesai ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;"> Total Proyek Dalam Proses </td>
            <td style="text-align: left; font-weight:bold;">: <?= $total - $selesai ?></td>
        </tr> 
        <tr>
            <td style="font-weight:bold;">
                Total SPK 
                <?php
                    $spkvalue = [];
                    foreach ($projects as $project) {
                        $spkvalue[] = $projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'];
                    }
                ?>
            </td>
            <td style="text-align: left; font-weight:bold;">: <?= "Rp." . number_format(array_sum($spkvalue), 0, ',', '.') ?></td>
        </tr>
    </table>
</body>

</html>