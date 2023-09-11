<?php require "config/function.php";
require "config/koneksi.php";
$sql = "SELECT * FROM deskripsi_pdf";
$query = mysqli_query($konek, $sql);
$data = mysqli_fetch_array($query);

$select_query = "SELECT * FROM invoice";
$select_result = $konek->query($select_query);
$select_query_B = "SELECT * FROM invoice_body";
$select_result_B = $konek->query($select_query_B);
$select_query_H = "SELECT * FROM invoice_header";
$select_result_H = $konek->query($select_query_H);
$select_query_PDF = "SELECT * FROM invoice_pdf ";
$select_result_PDF = $konek->query($select_query_PDF);
$penerima = mysqli_fetch_array($select_result_H);

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
    <form action="" method="post">

        <label for="start-date">Tanggal Awal:</label>
        <input type="date" name="tgl_awal" value="<?= date('Y-m-01');?>">

        <label for="end-date">Tanggal Akhir:</label>
        <input type="date" name="tgl_akhir" value="<?= date('Y-m-d');?>">
        <button type="submit" name="filter">cari</button>
    </form>
    <table class="table-costume" border="2" style="margin: auto;">
        <tr>
            <th>NO</th>
            <th>No. note</th>
            <th>tgl</th>
            <th>Deskripsi</th>
            <th>Quantity</th>
            <th>Harga</th>
            <th>Disc</th>
        </tr>
        <?php

        if (isset($_POST["filter"])) {
            echo "<button>reset</button>";
            $awal = $_POST['tgl_awal'];
            $akhir = $_POST["tgl_akhir"];
            if (isset($awal) && isset($akhir) && !empty($awal) && !empty($akhir)) {
                $quer = "SELECT * FROM invoice_body WHERE waktu BETWEEN '$awal' AND '$akhir'";
                $hasil = mysqli_query($konek, $quer);
                $total = 0;
                $totalDisc = 0;
                $ulang = 1;
                if (mysqli_num_rows($hasil) > 0) {
                    while ($dis_b = mysqli_fetch_assoc($select_result_B)) {
                        echo "<tr id='data-list'>";
                        echo "<td>" . $ulang . "</td>";
                        echo "<td>" . $dis_b['NT'] . "</td>";
                        echo "<td data-date='" . $dis_b['waktu'] . "'>" . $dis_b['waktu'] . "</td>";
                        echo "<td>" . $dis_b['deskripsi'] . "</td>";
                        echo "<td>" . $dis_b['QTY'] . "</td>";
                        echo "<td>" . number_format($dis_b['harga']) . "</td>";
                        echo "<td>" . $dis_b['diskon'] . "%</td>";
                        echo "</tr>";
                        $ulang++;
                    }
                } else {
                    echo " <tr>";
                    echo '<td colspan="7" style="text-align: center;">tidak ada data</td>';
                    echo "</tr>";
                }
            }
        }
        ?>
    </table>
    <hr>


</body>
<script>
</script>

</html>