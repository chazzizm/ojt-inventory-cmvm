<?php
    include "header.php";
    include "connection.php";
    $t=0;
    $message = ""; 
    $res = null;

    $raw_start = isset($_POST['starttime']) ? $_POST['starttime'] : '';
    $raw_end = isset($_POST['endtime']) ? $_POST['endtime'] : '';

if (isset($_POST['submit'])) 
{
    if(empty($raw_start) || empty($raw_end)) {
        $message = "<div class='alert alert-warning m-3'><strong>Missing Boundaries!</strong> Please enter both a start date and an end date.</div>";
    } else {
        $starttime = date('Y-m-d H:i:00', strtotime($raw_start));
        $endtime = date('Y-m-d H:i:59', strtotime($raw_end));

        $sql = "SELECT * FROM purchase WHERE created_at >= '$starttime' AND created_at <= '$endtime' ORDER BY created_at DESC";
        $res = $conn -> query ($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Purchase Report</title>
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
<h5>Purchase Report</h5>
<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th scope="col">Date & Time</th>
      <th scope="col">Product Name</th>
      <th scope="col">Unit</th>
      <th scope="col">Total Unit Price</th>
    </tr>
  </thead>
  <tbody>
 <?php
 if(isset($_POST['submit']) && !empty($raw_start) && !empty($raw_end) && $res)
 {
          if (mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $row_total = $row['unit'] * $row['unitprice'];
                $t = $t + $row_total;
                $formatted_date = date("M d, Y - h:i A", strtotime($row['created_at']));
              ?>
               <tr>
                <td><span class="badge bg-secondary"><?php echo $formatted_date; ?></span></td>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['unit'];?></td>
                <td>₱<?php echo number_format($row_total, 2);?></td>
               </tr>
                <?php
                 }
        } 
        else 
        {
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
</body>
</html>