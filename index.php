<?php
require "config/function.php";
require "config/koneksi.php";
$sql = "SELECT * FROM deskripsi_pdf";
$query = mysqli_query($konek, $sql);
$data = mysqli_fetch_array($query);


// Alert Berhasil di hapus
if (isset($_GET['message'])) {
  $message = $_GET['message'];
  echo "
  <script>
      Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: '$message'
      }).then(function (){;
      window.location.href = 'index.php';
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
      window.location.href = 'index.php';
      })
      </script>";
}

if (!isset($_SESSION['tombol_disable'])) {
  $_SESSION['tombol_disable'] = false;
}

if (!isset($_SESSION['tombol_batal'])) {
  $_SESSION['tombol_batal'] = true;
}

if (isset($_POST['proses'])) {
  if (tambah_data($_POST) > 0) {
  }
}

if (isset($_POST['simpanPisah'])) {
  if (simpan($_POST) > 0) {
  }
}

if (isset($_POST['baru+'])) {
  if (baru($_POST) > 0) {
  }
}

if (isset($_POST['prosesDescPDF'])) {
  if (descPDF($_POST) > 0) {
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
  <title>oawd</title>
</head>
<style>
  body {
    max-width: 100%;
  }
</style>

<body>

  <!-- Button trigger modal -->

  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" name="awal">
    tambah barang
  </button>

  <!-- Modal -->
  <form method="post">
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <input type="text" value="<?= $transactionNumber; ?>" name="NT" hidden>

            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">Deskripsi</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="" name="deskripsi" placeholder="..." required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="input_jumlah" class="col-sm-2 col-form-label">Jumlah</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="QTY" placeholder="0" id="input_jumlah" oninput="validasiangka()" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="input_harga" class="col-sm-2 col-form-label">harga</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="input_harga" name="harga" placeholder="0" oninput="validasiangka1()" required>
              </div>
            </div>

            <div class="row mb-3">
              <label for="input_diskon" class="col-sm-2 col-form-label">diskon</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="input_diskon" name="diskon" oninput="validasidiskon()" placeholder="0%">
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="proses">Tambah</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <form method="post">
    <div class="row mb-3" style="width: 70%;">
      <label for="" class="col-sm-2 col-form-label">No Transaksi</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="NT" placeholder="0" id="" readonly value="<?= $transactionNumber ?>" style="">
      </div>
    </div>
    <div class="row mb-3" style="width: 70%;">
      <label for="" class="col-sm-2 col-form-label">tanggal</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="tgl" placeholder="0" id="" readonly value="<?php $tanggal = date("d-m-Y");
                                                                                                  echo $tanggal; ?>" style="">
      </div>
    </div>
    <p>nama : <input type="text" name="nama" value="<?php $name = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
                                                    echo isset($_SESSION['nama']) ? $_SESSION['nama'] : ''; ?>" required onkeypress="return event.keyCode != 13;"></p>
    <p>TGL Jatuh Tempo : <?php $tanggal = date("d-m-Y");
                          echo $tanggal; ?></p>

    <h2>data baru di input : </h2>


    <table class="table">
      <thead>
        <tr>
          <th>NO.Transaksi</th>
          <th>Deskripsi</th>
          <th>QTY</th>
          <th>harga</th>
          <th>Diskon</th>
          <th>subtotal</th>
          <?php if ($_SESSION['tombol_disable']) { ?>
            <th>aksi</th>
          <?php } else {
            echo "";
          } ?>

        </tr>
      </thead>
      <tbody>
        <?php $query2 = "SELECT * FROM invoice";
        $selek = $konek->query($query2);
        $totalDisc = 0;
        $ppn = 0;
        $total = 0;
        $granTotal = 0;
        $granTotal2 = 0;
        ?>
        <?php



        while ($display = mysqli_fetch_assoc($selek)) { ?>
          <tr>
            <?php
            $subtotal =  $display['QTY'] * $display['harga'];
            $total += $subtotal;

            $totalDisc += $display["diskon"];
            $totaldantotalDisc = $total * ($totalDisc / 100); // hasil total * diskon
            $hasilTDTD =  $total - $totaldantotalDisc;
            $ppn = $hasilTDTD * 0.02;
            $granTotal = $hasilTDTD + $ppn;
            ?>
            <td><?= $display['NT'] ?></td>
            <td style="width: 13%;"><input type="text" readonly value="<?= $display['deskripsi']; ?>" style="width: 100%; border: none;" name="deskripsiB"></td>
            <td style="width: 13%;"><input type="text" readonly value="<?= $display['QTY']; ?>" style="width: 100%; border: none;" name="QTYB"></td>
            <td style="width: 13%;"><input type="text" readonly value="<?= number_format($display['harga']); ?>" style="width: 100%; border: none;"></td>
            <input type="text" readonly value="<?= $display["harga"]; ?>" style="width: 100%; border: none;" name="hargaB" hidden>
            <td style="width: 13%;"><input type="text" readonly value="<?php echo $display['diskon'] . "%"; ?>" style="width: 100%; border: none;" name="diskonB"></td>
            <td style="width: 13%;"><input type="text" readonly value="<?= number_format($subtotal); ?>" style="width: 100%; border: none;"></td>
            <input type="text" readonly value="<?= $subtotal; ?>" style="width: 100%; border: none;" name="subtotalB" hidden>
            <?php if ($_SESSION['tombol_disable']) { ?>
              <td><a class='btn btn-danger' onclick='confirmHapus(<?= $display["pk"] ?>)'>Hapus</a> </td>
            <?php } else {
              echo "";
            } ?>
          </tr>
        <?php
        }
        ?>

        <tr style="border: white;">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <!-- Contoh mengatur padding lebih spesifik -->
          <td style="padding-left: 5px; padding-right: 5px;">Total :</td>
          <input type="text" name="total" id="" value="<?= $total; ?>" readonly style="width: 100%; border: none;" hidden>
          <td style="width: 10%; padding-left: 5px; padding-right: 5px;"><input type="text" name="" id="" value="<?= number_format($total); ?>" readonly style="width: 100%; border: none;"></td>

        </tr>
        <tr style="border: white;">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td style="padding-left: 5px; padding-right: 5px;">PPN :</td>
          <td style="width: 10%; padding-left: 5px; padding-right: 5px;"><input type="text" name="PPN" id="" value="2%" readonly style="width: 100%; border: none;"></td>
        </tr>
        <tr>
          <td style="border: white;"></td>
          <td style="border: white;"></td>
          <td style="border: white;"></td>
          <td></td>
          <td style="padding-left: 5px; padding-right: 5px;">Disc :</td>
          <td style="width: 10%; padding-left: 5px; padding-right: 5px;"><input type="text" name="totalDISC" id="" value="<?= $totalDisc ?>%" readonly style="width: 100%; border: none;"></td>
        </tr>
        <tr style="border: white;">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td style="padding-left: 5px; padding-right: 5px; ">Grantotal :</td>
          <input type="text" name="grantotal" id="" value="<?= $granTotal; ?>" readonly style="width: 100%; border: none;" hidden>
          <td style="padding-left: 5px; padding-right: 5px;">Rp. <?= number_format($granTotal); ?></td>
        </tr>

      </tbody>
    </table>
    <div style="padding-left: 5px; padding-right: 5px;">

      <?php if ($_SESSION['tombol_disable']) {
                                            echo "";
                                          } else {
                                            echo '<button type="submit" name="baru+">baru</button> ';
                                          } ?>

      <?php if ($_SESSION['tombol_disable']) {
        echo '<button type="submit" name="simpanPisah" class="btn btn-primary">simpan</button>';
      } else {
        echo "";
      } ?>
      
      <?php if ($_SESSION['tombol_disable']) {
        echo " <a class='btn btn-danger' onclick='confirmReset()'>batal</a> ";
      } else {
        echo "";
      } ?>
      <a href="pdf.php" target="_blank" rel="noopener noreferrer">Cetak</a>

    </div>
  </form>
<br>

  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#example2" name="awal2">
    Edit deskripsi
  </button>

  <!-- Modal -->
  <form method="post">
    <div class="modal fade" id="example2" tabindex="-1" aria-labelledby="example2ModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="example2ModalLabel">edit deskripsi pdf</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">No 1: <div id="counter1"></div> </label>
              <div class="col-sm-10">
                <textarea name="pertama" id="panjangkata1" value="" cols="30" rows="2" maxlength="120" class="form-control" oninput="length1()"><?= $data['pertama'] ?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">No 2: <div id="counter2"></div> </label>
              <div class="col-sm-10">
                <textarea name="kedua" id="panjangkata2" cols="30" rows="2" maxlength="120" class="form-control" oninput="length2()"><?= $data['kedua'] ?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">No 3: <div id="counter3"></div> </label>
              <div class="col-sm-10">
                <textarea name="ketiga" id="panjangkata3" cols="30" rows="2" maxlength="120" class="form-control" oninput="length3()"><?= $data['ketiga'] ?></textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">No 4: <div id="counter4"></div> </label>
              <div class="col-sm-10">
                <textarea name="keempat" id="panjangkata4" cols="30" rows="2" maxlength="120" class="form-control" oninput="length3,()"><?= $data['keempat'] ?></textarea>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="prosesDescPDF">Edit</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Modal -->

  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emxaple3" name="awal2">
    Email
  </button>

  <form action="pdf-Email.php" method="post">
    <div class="modal fade" id="emxaple3" tabindex="-1" aria-labelledby="example3ModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="example3ModalLabel">Email</h1>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">Email </label>
              <div class="col-sm-10">
                <input type="email" name="emailOrang" value="cri.bhaskara@gmail.com" class="form-control" required></input>
              </div>
            </div>
            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">Subject </label>
              <div class="col-sm-10">
                <input type="text" name="subject" class="form-control" required></input>
              </div>
            </div>
            <div class="row mb-3">
              <label for="" class="col-sm-2 col-form-label">body</label>
              <div class="col-sm-10">
                <input type="text" name="bodyEmail" class="form-control" required></input>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="Email">kirim</button>
          </div>
        </div>
      </div>
    </div>
  </form>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script src="./style/sweetalert2.min.js"></script>
  <script>
    function confirmHapus(pk) {
      Swal.fire({
        title: 'Yakin Untuk Mengahapus?',
        text: "Barang yang di hapus tidak akan kembali!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'hapus.php?pk=' + pk;
        }
      })
    }

    function confirmReset() {
      Swal.fire({
        title: 'Yakin Untuk Di Reset?',
        text: "Barang yang di Reset tidak akan kembali!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Reset!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'resetInvoice.php';
        }
      })
    }

    function validasiangka() {
      let pok = document.getElementById("input_jumlah");
      let kop = pok.value;
      pok.value = kop.replace(/\D/g, '')
    }

    function validasiangka1() {
      let pok = document.getElementById("input_harga");
      let kop = pok.value;
      pok.value = kop.replace(/\D/g, '')
    }

    function validasidiskon() {
      let pok = document.getElementById("input_diskon");
      let kop = pok.value;
      pok.value = kop.replace(/[^0-9%]/g, '')
    }

    function length1() {
      let mkan = document.getElementById('panjangkata1');
      let counter = document.getElementById('counter1');
      let max = mkan.maxLength;
      let countmax = mkan.value.length;
      counter.innerHTML = countmax + "/" + max;
    }

    function length2() {
      let mkan = document.getElementById('panjangkata2');
      let counter = document.getElementById('counter2');
      let max = mkan.maxLength;
      let countmax = mkan.value.length;
      counter.innerHTML = countmax + "/" + max;
    }

    function length3() {
      let mkan = document.getElementById('panjangkata3');
      let counter = document.getElementById('counter3');
      let max = mkan.maxLength;
      let countmax = mkan.value.length;
      counter.innerHTML = countmax + "/" + max;
    }

    function length4() {
      let mkan = document.getElementById('panjangkata4');
      let counter = document.getElementById('counter4');
      let max = mkan.maxLength;
      let countmax = mkan.value.length;
      counter.innerHTML = countmax + "/" + max;
    }
    window.addEventListener('load', length1);
    window.addEventListener('load', length2);
    window.addEventListener('load', length3);
    window.addEventListener('load', length4);
  </script>

</body>

</html>