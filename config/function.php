<?php
session_start();
$konek = mysqli_connect("localhost", "root", "", "project");


$databaru = false;
$databatal = false;


//  insert invoice (table)

function tambah_data($post){
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
            
            }
            else{
                echo "error :". $konek->error;
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

function simpan($post){
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

if (empty($nama) || empty($deskripsi) || empty($jumlah) || empty($harga) ||  empty($total) ) {

    echo"<script>
    alert('Data tidak boleh kosong!');
    </script>";
    
}else{
    $sql = "INSERT INTO invoice_header (NT,tgl,nama, tglJatuhTempo, total, PPN , totalDISC ,granTOTAL ,waktu)
            VALUES ('$NT','$tanggal', '$nama', '$tanggal','$total', '$PPN' , '$totalDISC', '$grantotal' ,NOW())";
            
            
$sql3 = "INSERT INTO invoice_pdf (nama,totalDISC, subtotal, PPN, waktu)
SELECT '$nama','$totalDISC', (QTY * harga), '$PPN' , NOW() FROM invoice";

$sql2 = "INSERT INTO invoice_body (NT,deskripsi, QTY ,harga, diskon ,subtotal, waktu)
SELECT NT,deskripsi, QTY ,harga, diskon , (QTY * harga), NOW() FROM invoice";


            if ($konek->query($sql) === TRUE && $konek->query($sql2) === TRUE && $konek->query($sql3) === TRUE) {
                global $databaru,$databatal,$datacetak;
                $databaru = true;
                $databatal = true;
           
                    echo "<script>
                
                    alert('tersimpan!');

                    </script>";
                    unset($_SESSION['transactionNumber']);
                
                
                    }
                    else{
                        echo "error :". $konek->error;
                    }
                     
                }
}


//==================baru=================
function baru($post){

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
        $_SESSION = [];
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;

        $link = "index.php";
        echo "
      <script>
      alert('reset successfully')
      window.location.href = '$link';
      </script>";
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


function resets($post){
    global $konek;
$select_query = "SELECT COUNT(*) AS count FROM invoice WHERE waktu >= NOW() - INTERVAL 526000 MINUTE";
$select_result = $konek->query($select_query);
$row = $select_result->fetch_assoc();
$data_count = $row['count'];
    if ($data_count>0) {
    $sql = "DELETE FROM invoice WHERE waktu >= NOW() - INTERVAL 526000 MINUTE";
    mysqli_query($konek, $sql);
    if ($konek->query($sql) === TRUE) {
        $link = "index.php";
      echo "
      <script>
      alert('reset successfully')
      window.location.href = '$link';
      </script>";
      
    } else {
      echo "Error: " . $sql . "<br>" . $konek->error;
    }
          
        }
        else{
            $link = "index.php";
            echo "
            <script>
            alert('tidak ada data')
            window.location.href = '$link';
            </script>";
        }

$konek->close();
}


// terbilang pada pdf

function terbilang($angka) {
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
        $terbilang = terbilang($angka - 10) . ' belas';
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


 //edit deskripsi pada pdf
function descPDF($post){
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
        echo"<script>
        alert('Terupdate');
        window.location.href = '$link';
        </script>";
    }
    else{
        echo "error". $konek->error;
    }
}


