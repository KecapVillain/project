<?php
require "config/function.php";
require "config/koneksi.php";
$pk = $_GET['pk'];
$query = "SELECT * FROM invoice_header WHERE pk = '$pk'";
$konekk = mysqli_query($konek, $query);
$data = mysqli_fetch_assoc($konekk);

if (isset($_POST['save'])) {
    if (updateHistory($_POST) > 0) {
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="./style/sweetalert2.min.css">
  <link rel="stylesheet" href="./style/animate.min.css">
    <title>Update</title>
</head>
<body>
    <form method="post">

        <div class="row mb-3">
            <label for="NT" class="col-sm-2 col-form-label">No.transaksi</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="" name="NT" placeholder="..." required disabled value="<?= $data['NT'] ?>">
            </div>
        </div>
        <div class="row mb-3">
              <label for="tgl" class="col-sm-2 col-form-label">Tanggal</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" name="tgl" placeholder="0" required value="<?= $data['waktu'] ?>" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="input_nama" name="nama" required value="<?= $data['nama'] ?>">
              </div>
            </div>
            
            <div class="row mb-3">
                <label for="total" class="col-sm-2 col-form-label">Total</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="input_total" name="total" oninput="validasiangka1()" value="<?= $data['total'] ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="PPN" class="col-sm-2 col-form-label">PPN</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="input_PPN" name="PPN" value="<?= $data['PPN'] ?>%">
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="totalDISC" class="col-sm-2 col-form-label">Total Diskon</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="input_totalDISC" name="totalDISC" value="<?= $data['totalDISC'] ?>%">
                </div>
            </div>
            <div class="row mb-3">
                <label for="granTOTAL" class="col-sm-2 col-form-label">Gran Total</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="input_granTOTAL" name="granTOTAL" value="<?= $data['granTOTAL'] ?>">
                </div>
            </div>
            <button class="btn btn-primary" name="save">Update</button>
        </form>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script src="./style/sweetalert2.min.js"></script>
  <script>
      function validasiangka1() {
      let pok = document.getElementById("input_total");
      let kop = pok.value;
      pok.value = kop.replace(/\D/g, '')
    }

  </script>
</html>