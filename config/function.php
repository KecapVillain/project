<?php
session_start();
$konek = mysqli_connect("localhost", "root", "", "project");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="./style/sweetalert2.min.css">
    <link rel="stylesheet" href="./style/animate.min.css">
</head>

<body>
    <?php
    //  insert invoice (table)
    function tambah_data($post)
    {
        global $konek;

        $NT = $_POST["NT"];
        $tanggal = date("Y-m-d H:i:s");
        $deskripsi = $_POST["deskripsi"];
        $jumlah = $_POST["QTY"];
        $harga = $_POST["harga"];
        $diskon = $_POST["diskon"];


        $sql = "INSERT INTO invoice (NT,tgl,deskripsi, tglJtauhTempo , QTY , harga , diskon  , waktu)
            VALUES ('$NT','$tanggal', '$deskripsi', '$tanggal' , '$jumlah' ,'$harga' , '$diskon' ,NOW())";
        if ($konek->query($sql) === TRUE) {
            echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Barang Berhasil Di Tambahkan',
            icon: 'success',
            confirmButtonText: 'OK'
          })
    </script>";
        } else {
            echo "error :" . $konek->error;
        }
    }


    // nomor transaksi
    function generateTransactionNumber()
    {
        $prefix = "AKJ"; // Prefix untuk nomor transaksi
        $length = 5; // Panjang nomor transaksi (termasuk prefix)
        $number = mt_rand(10000, 99999); // Nomor acak antara 10000 dan 99999

        return $prefix . str_pad($number, $length - strlen($prefix), "0", STR_PAD_LEFT);
    }

    // Cek apakah nomor transaksi sudah ada dalam session
    if (!isset($_SESSION['transactionNumber'])) {
        $_SESSION['transactionNumber'] = generateTransactionNumber();
    }

    $transactionNumber = $_SESSION['transactionNumber'];




    // =================simpan======================

    function simpan($post)
    {
        global $konek;
        $NT = $_POST["NT"];
        $tanggal = date("Y-m-d H:i:s");
        $nama = $_POST["nama"];
        $_SESSION['nama'] = $nama;
        $deskripsi = $_POST["deskripsiB"];
        $jumlah = $_POST["QTYB"];
        $harga = $_POST["hargaB"];
        $total = $_POST["total"];
        $PPN = $_POST["PPN"];
        $totalDISC = $_POST["totalDISC"];
        $grantotal = $_POST["grantotal"];

        if (empty($nama) || empty($deskripsi) || empty($jumlah) || empty($harga) ||  empty($total)) {

            echo "<script>
    alert('Data tidak boleh kosong!');
    </script>";
        } else {
            $sql = "INSERT INTO invoice_header (NT,tgl,nama, tglJatuhTempo, total, PPN , totalDISC ,granTOTAL ,waktu)
            VALUES ('$NT','$tanggal', '$nama', '$tanggal','$total', '$PPN' , '$totalDISC', '$grantotal' ,NOW())";


            $sql3 = "INSERT INTO invoice_pdf (nama,totalDISC, subtotal, PPN, waktu)
SELECT '$nama','$totalDISC', (QTY * harga), '$PPN' , NOW() FROM invoice";

            $sql2 = "INSERT INTO invoice_body (NT,deskripsi, QTY ,harga, diskon ,subtotal, waktu)
SELECT NT,deskripsi, QTY ,harga, diskon , (QTY * harga), NOW() FROM invoice";


            if ($konek->query($sql) === TRUE && $konek->query($sql2) === TRUE && $konek->query($sql3) === TRUE) {
                $_SESSION['tombol_disable'] = false;
                $_SESSION['tombol_batal'] = true;

                echo "<script>
                
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Barang Berhasil Tersimpan!!',
                    icon: 'success'
                  })
                    </script>";
            } else {
                echo "error :" . $konek->error;
            }
        }
    }


    //==================baru=================
    function baru($post)
    {
        global $konek;
        $select_query = "SELECT COUNT(*) AS count FROM invoice";
        $select_result = $konek->query($select_query);
        $row = $select_result->fetch_assoc();
        $data_count = $row['count'];
        $select_query2 = "SELECT COUNT(*) AS count FROM invoice_pdf";
        $select_result2 = $konek->query($select_query2);
        $row = $select_result2->fetch_assoc();
        $data_count2 = $row['count'];
        if ($data_count > 0 && $data_count2 > 0) {
            $sql = "DELETE FROM invoice";
            $sql2 = "DELETE FROM invoice_pdf";
            mysqli_query($konek, $sql);
            if ($konek->query($sql) === TRUE && $konek->query($sql2) === TRUE) {
                unset($_SESSION['nama']);
                unset($_SESSION['transactionNumber']);
                $_SESSION['tombol_disable'] = true;
                $_SESSION['tombol_batal'] = false;
                header("Location: index.php");
            } else {
                echo "Error: " . $sql . "<br>" . $konek->error;
            }
        } else {
            $link = "index.php";
            echo "
            <script>
            alert('tidak ada data')
            window.location.href = '$link';
            </script>";
        }

        $konek->close();
    }

    //edit deskripsi pada pdf
    function descPDF($post)
    {
        global $konek;
        $pertama = $_POST['pertama'];
        $kedua = $_POST['kedua'];
        $ketiga = $_POST['ketiga'];
        $keempat = $_POST['keempat'];

        $update = "UPDATE deskripsi_pdf SET
    pertama='$pertama',
    kedua='$kedua',
    ketiga='$ketiga',
    keempat='$keempat'
    ";
        if ($konek->query($update) === TRUE) {
            $link = "index.php";
            echo "<script>
        alert('Terupdate');
        window.location.href = '$link';
        </script>";
        } else {
            echo "error" . $konek->error;
        }
    }

    function updateHistory($post)
    {
        $pk = $_GET['pk'];
        global $konek;
        $nama = htmlspecialchars($_POST['nama']);
        $total = htmlspecialchars($_POST['total']);
        $PPN =  htmlspecialchars($_POST['PPN']);
        $totalDISC = htmlspecialchars($_POST['totalDISC']);
        $granTOTAL = htmlspecialchars($_POST['granTOTAL']);

        $sql = "UPDATE invoice_header SET
            nama = '$nama',
            total = '$total',
            PPN = '$PPN',
            totalDISC = '$totalDISC',
            granTOTAL = '$granTOTAL'
            WHERE pk = '$pk'";

        if ($konek->query($sql) === TRUE) {
            echo "<script>
                    window.location.href = 'history.php?message=Data+Berhasil+Terupdate';
                    </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Data Tidak Terupdate!!',
                    icon: 'error'
                  })
                    </script>";
        }
    }
    ?>
</body>
<script src="./style/sweetalert2.min.js"></script>

</html>