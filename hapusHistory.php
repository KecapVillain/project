<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$konek = mysqli_connect($hostname, $username, $password, $dbname);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="./style/sweetalert2.min.css">
<link rel="stylesheet" href="./style/animate.min.css">
</head>
<body>

<?php
    $pk = $_GET['pk'];
    $sql = "DELETE FROM invoice_header WHERE pk=$pk";
    mysqli_query($konek, $sql);
    if ($konek->query($sql) === TRUE) {
        $link = "history.php?message=Data+Berhasil+Di+Hapus!";
      echo "
      <script>
           window.location.href = '$link';
      </script>";
      
    } else {
      echo "Error: " . $sql . "<br>" . $konek->error;
    }



?>
  

</body>
<script src="./style/sweetalert2.min.js"></script>

</html>