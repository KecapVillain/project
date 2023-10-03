<?php ob_start();
// terbilang pada pdf
function terbilang($angka)
{
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

    .bug {
border-right: none;
    }
</style>

<body>
    <table class="table-costume" border="2">
        <tr>
            <td style="width: 24%; padding: 10px; text-align: center;">No Nota</td>
            <td style="width: 24%; padding: 10px; text-align: center;">Tanggal</td>
            <td style="width: 24%; padding: 10px; text-align: center;">Keterangan</td>
            <td colspan="2" style="padding: 10px; text-align: center;">Jumlah</td>
        </tr>
        <tr>
            <td style="text-align:center;"><?= 'awdawd' ?></td>
            <td style="text-align:center;"><?= date('d-m-Y') ?></td>
            <td><?= 'awdawdwa' ?></td>
            <td class="bug">Rp.</td>
            <td style="width: 16%;">
                <table class="table-costume" border="2">
                    <tr>
                        <td style="text-align:right; width: 117%;">oakdoakwdk</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br><br>
    <table class="table table-costume" border="2">
        <tr>
            <td>
                Rp
            </td>
            <td style="width: 100%;">
                <table class="table table-costume">
                    <tr>
                        <td style="text-align: right; width: 117%; font-size: 14px;">418313018321318-31-2309-1293000</td>
                    </tr>
                </table>
            </td>
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