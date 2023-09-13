<?php require "config/function.php";
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
    <form action="" method="post">
        <label for="start-date">Tanggal Awal:</label>
        <input type="date" required name="tgl_awal" value="<?php echo @$_POST['tgl_awal'] . date('Y-m-01'); ?>">
        <label for="end-date">Tanggal Akhir:</label>
        <input type="date" name="tgl_akhir" required value="<?= @$_POST['tgl_akhir'] . date('Y-m-d'); ?>">
        <button type="submit" name="filter">cari</button>
        <?php if (isset($_POST['filter'])) {
            echo "<a href='history.php'>reset</a>";
        } ?>
    </form>



    <table class="table-costume" border="2" style="margin: auto;" id="data-table">
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
            $awal = $_POST['tgl_awal'];
            $akhir = $_POST["tgl_akhir"];
            if (isset($awal) && isset($akhir) && !empty($awal) && !empty($akhir)) {
                $quer = "SELECT * FROM invoice_body WHERE waktu BETWEEN '$awal' AND '$akhir'";
                $hasil = mysqli_query($konek, $quer);
                $linkpdf = "pdfHistory.php?tgl_awal= " .  $awal . "&tgl_akhir= " . $akhir . "&filter=true";
                $total = 0;
                $totalDisc = 0;
            } else {
                $quer = "SELECT * FROM invoice_body WHERE waktu BETWEEN '$awal' AND '$akhir'";
                $hasil = mysqli_query($konek, $quer);
                $linkpdf = "pdfHistory.php";
            }
        ?>
            <a href="<?= $linkpdf ?>" target="_blank" rel="noopener noreferrer">Cetak</a>'



        <?php
            $ulang = 1;
            if (mysqli_num_rows($hasil) == 0) { ?>
            <tr>
                <td>
                    kosong
                </td>
            </tr>
           <?php }else{
                if (mysqli_num_rows($hasil) > 0) {
                    while ($dis_b = mysqli_fetch_assoc($hasil)) {
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