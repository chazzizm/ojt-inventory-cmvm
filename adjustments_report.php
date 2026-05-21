<?php
 include 'header.php';
 include 'connection.php';
 $t=0;
 $message = ""; 
 $result = null;

 // Sticky date filter variables
 $raw_start = isset($_POST['starttime']) ? $_POST['starttime'] : '';
 $raw_end = isset($_POST['endtime']) ? $_POST['endtime'] : '';

if (isset($_POST['submit'])) 
{
    if(empty($raw_start) || empty($raw_end)) {
        $message = "<div class='alert alert-warning m-3'><strong>Missing Boundaries!</strong> Please enter both a start date and an end date before requesting a transaction filter.</div>";
    } else {
        // Secure time windows using our 00 to 59 seconds filter patch
        $starttime = date('Y-m-d H:i:00', strtotime($raw_start));
        $endtime = date('Y-m-d H:i:59', strtotime($raw_end));

        $sql = "SELECT * FROM adjustments WHERE created_at >= '$starttime' AND created_at <= '$endtime' ORDER BY created_at DESC";
        $result = $conn -> query ($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory Adjustments Report</title>
</head>
<body>
<?php if(!empty($message)) echo $message; ?>

<div class="container table-wrapper">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-4">
  <label for="starttime" class="form-label">Start (date and time):</label>
  <input type="datetime-local" id="starttime" name="starttime" class="form-control d-inline-block w-auto me-2" value="<?php echo $raw_start; ?>">

  <label for="endtime" class="form-label">End (date and time):</label>
  <input type="datetime-local" id="endtime" name="endtime" class="form-control d-inline-block w-auto me-2" value="<?php echo $raw_end; ?>">
  
  <input type="submit" name="submit" class="btn btn-primary" value="Filter Adjustments">
</form>
<button type="button" class="btn btn-secondary mb-3" onclick="window.print();return false;"><i class="fas fa-file-pdf"></i> Print PDF Report</button>
<div class="container pendingbody">
  <h5>Inventory Adjustments & Wastage Report</h5>
<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th scope="col">Date & Time</th>
      <th scope="col">Product Name</th>
      <th scope="col">Units Reduced</th>
      <th scope="col">Value Impact</th>
    </tr>
  </thead>
  <tbody>
  <?php
   if(isset($_POST['submit']) && !empty($raw_start) && !empty($raw_end) && $result)
   {
          if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                    $row_total = $row["units_reduced"] * $row["unitprice"];
                    $t = $t + $row_total;
                    $formatted_date = date("M d, Y - h:i A", strtotime($row['created_at']));
              ?>
    <tr>
      <td><span class="badge bg-danger"><?php echo $formatted_date; ?></span></td>
      <td><?php echo $row["name"] ?></td>
      <td>-<?php echo $row["units_reduced"] ?></td>
      <td>₱<?php echo number_format($row_total, 2) ?></td>
    </tr>
    <?php 
    }
        } 
        else {
            echo "<tr><td colspan='4'>0 manual inventory adjustments found within this date constraint.</td></tr>";
        }
    }
        ?>
  </tbody>
</table>
<div class="h5 mt-3 text-end text-danger">
    <strong>Total Value Adjusted: ₱<?php echo number_format($t, 2); ?></strong>
</div>
</div>
</div>
</body>
</html>