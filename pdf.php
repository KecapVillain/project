<?php ob_start();
 // terbilang pada pdf
    function terbilang($angka){
        $angka;
        $bilangan = array(
            '',
            'Satu',
            'Dua',
            'Tiga',
            'Empat',
            'Lima',
            'Enam',
            'Tujuh',
            'Delapan',
            'Sembilan',
            'Sepuluh',
            'Sebelas'
        );

        // cara kerja : 
        // contoh angka 198
        // jadi 198 akan masuk ke kondisi "< 200" maka terbilang (198 - 100) = 98, dan 98 akan masuk ke kondisi < 100
        // lalu terbilang (98 / 10) = 9.8 (dan konteks nya adalah terbilang otomatis terbilang tidak menghitung koma) 
        //jadi 9.8 terpisah menjadi 9 dan 8 lalu 9 dan 8 akan di ambil dari array variable bilangan 
        // maka jadinya sembilan PULUH (kata puluh dari percabangan < 100) delapan 
        $terbilang = '';
        if ($angka < 12) {
            $terbilang = $bilangan[$angka];
        } elseif ($angka < 20) {
            if ($angka == 1) {
                $terbilang = 'satu';
            } else {
                $terbilang = terbilang($angka - 10) . ' belas';
            }
        } elseif ($angka < 100) {
            $terbilang = terbilang($angka / 10) . ' puluh ' . terbilang($angka % 10);
        } elseif ($angka < 200) {
            $terbilang = ' seratus ' . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = terbilang($angka / 100) . ' ratus ' . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = ' seribu ' . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = terbilang($angka / 1000) . ' ribu ' . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang = terbilang($angka / 1000000) . ' juta ' . terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $terbilang = terbilang($angka / 1000000000) . ' milyar ' . terbilang($angka % 1000000000);
        } else {
            $terbilang = 'Angka terlalu besar untuk diterjemahkan';
        }

        return $terbilang;
    }


require "config/koneksi.php";
$sql = "SELECT * FROM deskripsi_pdf";
$query = mysqli_query($konek, $sql);
$data = mysqli_fetch_array($query);

$select_query = "SELECT * FROM invoice";
$select_result = $konek->query($select_query);
$select_query_B = "SELECT * FROM invoice_body";
$select_result_B = $konek->query($select_query_B);
$select_query_H = "SELECT * FROM invoice_header ORDER BY waktu DESC LIMIT 1;";
$select_result_H = $konek->query($select_query_H);
$select_query_PDF = "SELECT * FROM invoice_pdf ";
$select_result_PDF = $konek->query($select_query_PDF);
$penerima = mysqli_fetch_array($select_result_H);
$row = mysqli_num_rows($select_result);

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
        Faktur Penjualan
    </h2>

    <?php
    if ($row > 0) { ?>

        <table class="table-costume" style="font-size: 16px;">
            <tr>
                <td>Tanggal </td>
                <td>:</td>
                <td style="width: 36%;"><?= date('d-m-Y') ?></td>
                <td>Kepada Yth</td>
                <td>:</td>
                <td><?php 
                    echo $penerima['nama']; 
                 ?></td>
            </tr>
            <tr>
                <td>Lokasi </td>
                <td>:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Sales</td>
                <td>:</td>
                <td>( Login )</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Jth Tempo </td>
                <td>:</td>
                <td><?= date('d-m-Y') ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    <?php } else {  ?>

<table class="table-costume" style="font-size: 16px;">
<tr>
    <td>Tanggal </td>
    <td>:</td>
    <td style="width: 36%;"><?= date('d-m-Y') ?></td>
    <td>Kepada Yth</td>
    <td>:</td>
    <td>Tidak ada</td>
</tr>
<tr>
    <td>Lokasi </td>
    <td>:</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>Sales</td>
    <td>:</td>
    <td>( Login )</td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>Jth Tempo </td>
    <td>:</td>
    <td><?= date('d-m-Y') ?></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
</table>

   <?php } ?>
    <table class="table-costume" border="2" style="margin: auto;">
        <tr>
            <th>NO</th>
            <th>Kode</th>
            <th>Deskripsi</th>
            <th>Quantity</th>
            <th>Harga</th>
            <th>Disc</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $total = 0;
        $totalDisc = 0;
        if ($row > 0) {

            $ulang = 1;
            while ($dis_b =  mysqli_fetch_assoc($select_result_B)) {
                while ($dis_pdf =  mysqli_fetch_assoc($select_result_PDF)) {
                    while ($dis =  mysqli_fetch_assoc($select_result)) {
                        $subtotalPDF = $dis['QTY'] * $dis['harga'];
                        $total += $subtotalPDF;


                        $totalDisc += $dis["diskon"];
                        $totaldantotalDisc = $total * ($totalDisc / 100); // hasil total * diskon
                        $hasilTDTD =  $total - $totaldantotalDisc;
                        $ppn = $hasilTDTD * 0.02;
                        $granTotal = $hasilTDTD + $ppn;
                        $terbilang = terbilang(round($granTotal));
                        
        ?>
                        <tr>
                            <td><?= $ulang ?></td>
                            <td><?= $dis['NT'] ?></td>
                            <td><?= $dis['deskripsi'] ?></td>
                            <td><?= $dis['QTY'] ?></td>
                            <td><?= number_format($dis['harga']); ?></td>
                            <td><?= $dis['diskon'] ?>%</td>
                            <td><?= number_format($subtotalPDF)
                                ?></td>


                        </tr>

        <?php $ulang++;
                    }
                }
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'>Data tidak ada</td></tr>";
        } ?>

    </table>
    <hr>

    <?php

    if ($row > 0) { ?>

        <table class="table-costume">
            <tr>
                <td style="width: 87.5%; font-size: 12px;">Terbilang : <?= $terbilang ?> </td>
                <td style="font-size: 12px;">subtotal :</td>
                <td style=" width: 85px; font-size: 12px;"><?= number_format($total); ?> </td>
            </tr>
            <tr>
                <td style="width: 87.5%; font-size: 12px;"></td>
                <td style="font-size: 12px; padding-left: 11px;">Diskon :</td>
                <td style=" width: 85px; font-size: 12px; "> <?= $totalDisc ?>% </td>
            </tr>
            <tr>
                <td style="width: 87.5%; font-size: 12px;">Description :</td>
                <td style="font-size: 12px; padding-left: 24px;">PPN :</td>
                <td style=" width: 85px; font-size: 12px;"> 2% </td>
            </tr>
            <tr>
                <td style="width: 87.5%; font-size: 12px;"> </td>
                <td style="font-size: 12px;  padding-left: 22px;">Total :</td>
                <td style=" width: 85px; font-size: 12px;"> <?= number_format($granTotal); ?> </td>
            </tr>
        </table>

    <?php } else { ?>
        <table class="table-costume">
            <tr>
                <td style="width: 87.5%; font-size: 12px;">Terbilang : Data Kosong </td>
                <td style="font-size: 12px;">subtotal :</td>
                <td style=" width: 85px; font-size: 12px;"> data kosong </td>
            </tr>
            <tr>
                <td style="width: 87.5%; font-size: 12px;"></td>
                <td style="font-size: 12px; padding-left: 11px;">Diskon :</td>
                <td style=" width: 85px; font-size: 12px; "> data kosong </td>
            </tr>
            <tr>
                <td style="width: 87.5%; font-size: 12px;">Description : </td>
                <td style="font-size: 12px; padding-left: 24px;">PPN :</td>
                <td style=" width: 85px; font-size: 12px;"> 2% </td>
            </tr>
            <tr>
                <td style="width: 87.5%; font-size: 12px;"> </td>
                <td style="font-size: 12px;  padding-left: 22px;">Total :</td>
                <td style=" width: 85px; font-size: 12px;"> data kosong </td>
            </tr>
        </table>
    <?php   } ?>

<?php if ($row > 0) { ?>

    <table class="table-costume" style="margin: auto;">
        <tr>
            <td style="width: 10%; text-align: center;">Penerima</td>
            <td style="width: 25%;"></td>
            <td style="width: 10%; text-align: center;">GUDANG,</td>
            <td style="width: 25%;"></td>
            <td style="width: 10%; text-align: center;">SALES,</td>
            <td style="width: 25%;"></td>
            <td style="width: 10%; text-align: center;">Admin,</td>
        </tr>
        <tr>
            <td></td>
            <!-- <td>1. </td> -->
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <!-- <td>2. </td> -->
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <!-- <td>3.</td> -->
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="text-align: center;">
            <td>
            <?php 
                    echo $penerima['nama']; 
             ?>
            </td>
            <td></td>
            <td>Ga tau</td>
            <td></td>
            <td>Login</td>
            <td></td>
            <td>Ga tau</td>
        </tr>
    </table>
<?php } else { ?>
    <table class="table-costume" style="margin: auto;">
    <tr>
        <td style="width: 10%; text-align: center;">Penerima</td>
        <td style="width: 25%;"></td>
        <td style="width: 10%; text-align: center;">GUDANG,</td>
        <td style="width: 25%;"></td>
        <td style="width: 10%; text-align: center;">SALES,</td>
        <td style="width: 25%;"></td>
        <td style="width: 10%; text-align: center;">Admin,</td>
    </tr>
    <tr>
        <td></td>
        <!-- <td>1. </td> -->
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <!-- <td>2. </td> -->
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <!-- <td>3.</td> -->
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>
            <hr>
        </td>
        <td></td>
        <td>
            <hr>
        </td>
        <td></td>
        <td>
            <hr>
        </td>
        <td></td>
        <td>
            <hr>
        </td>
    </tr>
</table>

<?php } ?>
    <table class="table-costume">
        <tr>
            <td style="width: 120%;">Perhatian</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>1. <?= $data['pertama'] ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>2. <?= $data['kedua'] ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>3. <?= $data['ketiga'] ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>4. <?= $data['keempat'] ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</body>

</html>
<?php
require __DIR__ . '/vendor/autoload.php';
$html = ob_get_contents();
ob_end_clean();

use Spipu\Html2Pdf\Html2Pdf;

require "./vendor/spipu/html2pdf/src/Html2Pdf.php";
$pdf = new Html2Pdf('L', 'A5', 'en');
$pdf->pdf->setTitle("Transaksi");
$pdf->WriteHTML($html);
$pdf->Output("Data_Transaksi.pdf", 'I');
?>