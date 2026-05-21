<?php
    include "header.php";
    include "connection.php";
    $t=0;
if (isset($_POST['submit'])) 
{
    $starttime=$_POST['starttime'];
    $endtime=$_POST['endtime'];

$sql = "SELECT * FROM sales where created_at>='$starttime' && created_at<'$endtime'";
$res = $conn -> query ($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
</head>
<body>
<div class="container table-wrapper">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-4">
  <label for="starttime" class="form-label">Start (date and time):</label>
  <input type="datetime-local" id="starttime" name="starttime" class="form-control d-inline-block w-auto me-2">

  <label for="endtime" class="form-label">End (date and time):</label>
  <input type="datetime-local" id="endtime" name="endtime" class="form-control d-inline-block w-auto me-2">
  <input type="submit" name="submit" class="btn btn-primary">
</form>
<button type="button" class="btn btn-secondary mb-3" onclick="window.print();return false;"><i class="fas fa-file-pdf"></i> Print PDF Report</button>
<h5>Sales Report</h5>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Product Name</th>
      <th scope="col">Units Sold</th>
      <th scope="col">Total Earnings</th>
    </tr>
  </thead>
  <tbody>
 <?php
 if(isset($_POST['submit']))
 {
          if (mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $t = $t + $row['totalprice'];
              ?>
               <tr>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['sellunit'];?></td>
                <td>₱<?php echo number_format($row['totalprice'], 2);?></td>
               </tr>
                <?php
                 }
        } 
        else 
        {
            echo "<tr><td colspan='3'>0 results</td></tr>";
        }
    }
        ?>
    </tbody>
</table>
<div class="h5 mt-3 text-end">
    <strong>Total: ₱<?php echo number_format($t, 2); ?></strong>
</div>
</div>
</body>
</html>