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
     $select_query = "SELECT COUNT(*) AS count FROM invoice";
     $select_result = $konek->query($select_query);
     $row = $select_result->fetch_assoc();
     $data_count = $row['count'];
     if ($data_count > 0) {
         $sql = "DELETE FROM invoice";
         mysqli_query($konek, $sql);
         if ($konek->query($sql) === TRUE) {
             $link = "index.php?message=Data+Berhasil+Di+Reset";
             echo "
   <script>
   window.location.href = '$link';
   </script>";
         } else {
             echo "Error: " . $sql . "<br>" . $konek->error;
         }
     } else {
         $link = "index.php?error=Tidak+Ada+Data+Untuk+Di+Reset";
         echo "
         <script>
         window.location.href = '$link';
         </script>";
     }

     $konek->close();
 

?>
  

</body>
<script src="./style/sweetalert2.min.js"></script>

</html>