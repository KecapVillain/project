<?php
require "config/function.php";
require "config/koneksi.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="style/sweetalert2.min.css">
</head>
<?php
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '$message'
            }).then(function (){;
            window.location.href = 'history.php';
            })
            </script>";
}
if (isset($_GET['error'])) {
    $message = $_GET['error'];
    echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '$message'
            }).then(function (){;
            window.location.href = 'history.php';
            })
            </script>";
}

?>
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

    <hr>
    <form action="" method="post">
        <!-- <select name="database" id="">
        <option selected>pilih</option>
        <option value="semua" <?php if (@$_POST['database'] == "semua") {
                                    echo "selected";
                                } ?>>semua</option>
        <option value="invoice_body" <?php if (@$_POST['database'] == "invoice_body") {
                                            echo "selected";
                                        } ?>>badan</option>
    <option value="invoice_header">kepala</option>
</select> -->
        <label for="start-date">Tanggal Awal:</label>
        <input type="date" required name="tgl_awal" value="<?php echo @$_POST['tgl_awal'] . date('Y-m-01'); ?>">
        <label for="end-date">Tanggal Akhir:</label>
        <input type="date" name="tgl_akhir" required value="<?= @$_POST['tgl_akhir'] . date('Y-m-d'); ?>">
        <button type="submit" name="filter" class="btn btn-primary">cari</button>
        <?php if (isset($_POST['filter'])) {
            echo "<a href='history.php'>reset</a>";
        } ?>
    </form>

    <?php

    ?>

    <table class="table-costume" style="margin: auto; width: 100%;" id="data-table">
        <tr>
            <th>NO</th>
            <th>No. note</th>
            <th>tgl</th>
            <th>Nama</th>
            <th>total</th>
            <th>PPN</th>
            <th>total diskon</th>
            <th>Gran total</th>
            <th colspan="2">Aksi</th>
        </tr>
        <?php
        if (isset($_POST["filter"])) {
            $awal = $_POST['tgl_awal'];
            $akhir = $_POST["tgl_akhir"];
            // $database = $_POST['database'];
            // $queryBody = "SELECT * FROM invoice_body  WHERE waktu BETWEEN '$awal' AND '$akhir'";
            // $queryHeader = "SELECT * FROM invoice_header  WHERE waktu BETWEEN '$awal' AND '$akhir'";
            // $semua = "$queryBody UNION $queryHeader";
            if (isset($awal) && isset($akhir) && !empty($awal) && !empty($akhir)) {
                // if ($database == "semua") {
                //     $quer =  $semua;
                // }
                //     elseif($database =="invoice_body"){
                //         $quer = $queryBody;
                //         $selected = "selected";
                //     }
                //     elseif($database =="invoice_header"){
                //         $quer = $queryHeader;
                //     }
                $quer = "SELECT * FROM invoice_header WHERE waktu BETWEEN '$awal' AND '$akhir'";
                $hasil = mysqli_query($konek, $quer);
                $linkPDF = "pdfHistory.php?tgl_awal= " .  $awal . "&tgl_akhir= " . $akhir . "&filter=true";
                $linkExcel = "excelHistory.php?tgl_awal= " .  $awal . "&tgl_akhir= " . $akhir . "&filter=true";
                $total = 0;
                $totalDisc = 0;
            } else {
                $quer = "SELECT * FROM invoice_header WHERE waktu BETWEEN '$awal' AND '$akhir'";
                $hasil = mysqli_query($konek, $quer);
                $linkPDF = "pdfHistory.php";
                $linkExcel = "excelHistory.php";
            }

        ?>
            <a href="<?= $linkPDF ?>" target="_blank" rel="noopener noreferrer">Cetak PDF</a>
            <a href="<?= $linkExcel ?>" target="_blank" rel="noopener noreferrer">Cetak Excel</a>



            <?php
            $ulang = 1;

            if (mysqli_num_rows($hasil) > 0) {
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
                        <td><a href="updateHistory.php?pk=<?= $dis_h['pk']?>" name="update" class="btn btn-primary">Update</a></td>
                        <td><a name="update" class="btn btn-danger" onclick="confirmHapus(<?= $dis_h['pk'] ?>)">Delete</a></td>
                    </tr>
        <?php $ulang++;
                }
            } else {
                echo " <tr>";
                echo '<td colspan="7" style="text-align: center;">tidak ada data</td>';
                echo "</tr>";
            }
        }

        ?>
    </table>
    <hr>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="style/sweetalert2.min.js"></script>
<script>
    function confirmHapus(pk) {
        Swal.fire({
            title: 'Yakin Untuk Mengahapus?',
            text: "History yang di hapus tidak akan kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'hapusHistory.php?pk=' + pk;
            }
        })
    }
</script>

</html>