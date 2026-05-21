<?php
 include 'header.php';
 include 'connection.php';
 $t=0;
 $message = ""; 
 $result = null;

 // Setup sticky variables
 $raw_start = isset($_POST['starttime']) ? $_POST['starttime'] : '';
 $raw_end = isset($_POST['endtime']) ? $_POST['endtime'] : '';

if (isset($_POST['submit'])) 
{
    if(empty($raw_start) || empty($raw_end)) {
        $message = "<div class='alert alert-warning m-3'><strong>Missing Boundaries!</strong> Please enter both a start date and an end date.</div>";
    } else {
        // FIXED: Force the Start Time to 00 seconds, and End Time to 59 seconds so nothing gets missed!
        $starttime = date('Y-m-d H:i:00', strtotime($raw_start));
        $endtime = date('Y-m-d H:i:59', strtotime($raw_end));

        // FIXED: Changed '<' to '<=' to include the final minute
        $sql = "SELECT * FROM sales WHERE created_at >= '$starttime' AND created_at <= '$endtime' ORDER BY created_at DESC";
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
  <input type="datetime-local" id="starttime" name="starttime" class="form-control d-inline-block w-auto me-2" value="<?php echo $raw_start; ?>">

  <label for="endtime" class="form-label">End (date and time):</label>
  <input type="datetime-local" id="endtime" name="endtime" class="form-control d-inline-block w-auto me-2" value="<?php echo $raw_end; ?>">
  
  <input type="submit" name="submit" class="btn btn-primary">
</form>
<button type="button" class="btn btn-secondary mb-3" onclick="window.print();return false;"><i class="fas fa-file-pdf"></i> Pdf Report</button>
<div class="container pendingbody">
  <h5>Sales Report</h5>
<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th scope="col">Date & Time</th>
      <th scope="col">Name</th>
      <th scope="col">Units Sold</th>
      <th scope="col">Total Earnings</th>
    </tr>
  </thead>
  <tbody>
  <?php
   if(isset($_POST['submit']) && !empty($raw_start) && !empty($raw_end) && $result)
   {
          $t=0;
          if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                    $t=$t+$row["totalprice"];
                    $formatted_date = date("M d, Y - h:i A", strtotime($row['created_at']));
              ?>
    <tr>
      <td><span class="badge bg-secondary"><?php echo $formatted_date; ?></span></td>
      <td><?php echo $row["name"] ?></td>
      <td><?php echo $row["sellunit"] ?></td>
      <td>₱<?php echo number_format($row["totalprice"], 2) ?></td>
    </tr>
    <?php 
    }
        } 
        else {
            echo "<tr><td colspan='4'>0 results matches the chosen date constraints.</td></tr>";
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