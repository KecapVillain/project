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
<label for="start-date">Tanggal Awal:</label>
<input type="date" id="start-date">

<label for="end-date">Tanggal Akhir:</label>
<input type="date" id="end-date">
    <br>

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
        $total = 0;
        $totalDisc = 0;
            $ulang = 1;
            while ($dis_b =  mysqli_fetch_assoc($select_result_B)) {

        ?>
                        <tr id="data-list">
                            <td ><?= $ulang ?></td>
                            <td ><?= $dis_b['NT'] ?></td>
                            <td data-date="<?= $dis_b['waktu'] ?>"><?= $dis_b['waktu'] ?></td>
                            <td><?= $dis_b['deskripsi'] ?></td>
                            <td><?= $dis_b['QTY'] ?></td>
                            <td><?= number_format($dis_b['harga']); ?></td>
                            <td><?= $dis_b['diskon'] ?>%</td>
                        </tr>

        <?php $ulang++;
                    }
            ?>
    </table>
    <hr>


</body>
<script>
// Ambil elemen input tanggal
const startDateInput = document.getElementById('start-date');
const endDateInput = document.getElementById('end-date');

// Ambil semua baris data
const dataRows = document.querySelectorAll('tr');

// Tambahkan event listener ke elemen input tanggal
startDateInput.addEventListener('input', filterData);
endDateInput.addEventListener('input', filterData);

// Fungsi untuk menerapkan filter berdasarkan rentang tanggal
function filterData() {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    // Loop melalui semua baris data, mulai dari indeks 1 untuk melewati baris header
    for (let i = 1; i < dataRows.length; i++) {
        const row = dataRows[i];
        const rowDataDate = new Date(row.querySelector('td[data-date]').getAttribute('data-date'));

        // Periksa apakah tanggal pada baris data berada dalam rentang yang dipilih
        if (rowDataDate >= startDate && rowDataDate <= endDate) {
            row.style.display = ''; // Tampilkan baris
        } else {
            row.style.display = 'none'; // Sembunyikan baris
        }
    }
}
</script>
</html>