<?php ob_start();
require "config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<style>
    .table-costume {
        border-collapse: collapse;
        width: 85%;
        padding-right: 15%;
    }

    .table-costume th {
        padding: 0px 30px 0px 30px;
        width: 15%;
        font-size: 12px;
    }

    .table-costume td {
        word-wrap: break-word;
        padding: 5px;
        font-size: small;

    }
</style>

<body>
    <h2 style="text-align: center;">
        History Penjualan
    </h2>
    <table class="table-costume" border="2" style="margin: auto;" id="data-table">
        <tr>
            <th>NO</th>
            <th>No. note</th>
            <th>tgl</th>
            <th>Nama</th>
            <th>Total</th>
            <th>PPN</th>
            <th>Total Diskon</th>
            <th>Gran Total</th>
        </tr>
        <?php
        $awal = @$_GET['tgl_awal'];
        $akhir = @$_GET["tgl_akhir"];
        $quer = "SELECT * FROM invoice_header WHERE waktu BETWEEN '$awal' AND '$akhir'";
        $hasil = mysqli_query($konek, $quer);
        $row = mysqli_num_rows($hasil);
        $total = 0;
        $totalDisc = 0;
        $ulang = 1;
        if ($row > 0) {
            while ($dis_h = mysqli_fetch_assoc($hasil)) { ?>
                <tr>
                    <td><?= $ulang ?></td>
                    <td><?= $dis_h['NT'] ?></td>
                    <td data-date="<?= $dis_h['waktu'] ?>"><?= $dis_h['waktu'] ?></td>
                    <td><?= $dis_h['nama'] ?></td>
                    <td><?= number_format($dis_h['total']) ?></td>
                    <td><?= $dis_h['PPN'] ?>%</td>
                    <td><?= $dis_h['totalDISC'] ?>%</td>
                    <td><?= number_format($dis_h['granTOTAL']) ?></td>
                </tr>
        <?php $ulang++;
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'>Data tidak ada</td></tr>";
        }

        ?>
    </table>
    <hr>

</body>

</html>
<?php
require __DIR__ . '/vendor/autoload.php';
$html = ob_get_contents();
ob_end_clean();

use Spipu\Html2Pdf\Html2Pdf;

require "./vendor/spipu/html2pdf/src/Html2Pdf.php";
$pdf = new Html2Pdf('P', 'A4', 'en');
$pdf->pdf->setTitle("History-Transaksi");
$pdf->WriteHTML($html);
$pdf->Output("History-Transaksi.pdf", 'I');
?>