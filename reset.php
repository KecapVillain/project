<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$konek = mysqli_connect($hostname, $username, $password, $dbname);

$select_query = "SELECT COUNT(*) AS count FROM invoice ";
$select_result = $konek->query($select_query);
$row = $select_result->fetch_assoc();
$data_count = $row['count'];
    if ($data_count>0) {
    $sql = "DELETE FROM invoice ";
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

$konek->close()

?>