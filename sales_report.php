<?php
 include 'header.php';
 include 'connection.php';
 $t=0;
 $message = ""; // Error tracking element configuration
 $result = null;

if (isset($_POST['submit'])) 
{
    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];

    // Safeguard database execution structure against empty elements
    if(empty($starttime) || empty($endtime)) {
        $message = "<div class='alert alert-warning m-3'><strong>Missing Boundaries!</strong> Please enter both a start date and an end date before requesting a transaction query filter.</div>";
    } else {
        $sql = "SELECT * FROM sales where created_at>='$starttime' && created_at<'$endtime'";
        $result = $conn -> query ($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales Report</title>
</head>
<body>
<?php if(!empty($message)) echo $message; ?>

<div class="container table-wrapper">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-4">
  <label for="starttime" class="form-label">Start (date and time):</label>
  <input type="datetime-local" id="starttime" name="starttime" class="form-control d-inline-block w-auto me-2">

  <label for="endtime" class="form-label">End (date and time):</label>
  <input type="datetime-local" id="endtime" name="endtime" class="form-control d-inline-block w-auto me-2">
  <input type="submit" name="submit" class="btn btn-primary">
</form>
<button type="button" class="btn btn-secondary mb-3" onclick="window.print();return false;"><i class="fas fa-file-pdf"></i> Pdf Report</button>
<div class="container pendingbody">
  <h5>Sales Report</h5>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Unit</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
  <tbody>
  <?php
   if(isset($_POST['submit']) && !empty($starttime) && !empty($endtime) && $result)
   {
          $t=0;
          if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                    $t=$t+$row["totalprice"];
              ?>
    <tr>
      <td><?php echo $row["name"] ?></td>
      <td><?php echo $row["sellunit"] ?></td>
      <td>₱<?php echo number_format($row["totalprice"], 2) ?></td>
    </tr>
    <?php 
    }
        } 
        else {
            echo "<tr><td colspan='3'>0 results matches the chosen date constraints.</td></tr>";
        }
    }
        ?>
  </tbody>
</table>
<div class="h5 mt-3 text-end">
    <strong>Total: ₱<?php echo number_format($t, 2); ?></strong>
</div>
</div>
</div>
</body>
</html>