<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$konek = mysqli_connect($hostname, $username, $password, $dbname);

    $pk = $_GET['pk'];
    $sql = "DELETE FROM invoice WHERE pk=$pk";
    mysqli_query($konek, $sql);
    if ($konek->query($sql) === TRUE) {
        $link = "index.php";
      echo "
      <script>
      alert('Delete successfully')
      window.location.href = '$link';
      </script>";
      
    } else {
      echo "Error: " . $sql . "<br>" . $konek->error;
    }


$konek->close()

?>