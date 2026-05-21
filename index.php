<?php
    include "header.php";
    include "connection.php";

$message = ""; 

if(isset($_POST['update_btn'])){
  $update_id = (int)$_POST['update_id'];
  $name = mysqli_real_escape_string($conn, $_POST['update_name']);
  $des = mysqli_real_escape_string($conn, $_POST['update_des']);
  $new_unit = (int)$_POST['update_unit'];
  $unitprice = (int)$_POST['update_unitprice'];
  
  $check_sql = mysqli_query($conn, "SELECT unit FROM `product` WHERE id = '$update_id'");
  $current_data = mysqli_fetch_assoc($check_sql);
  $old_unit = (int)$current_data['unit'];

  $update_query = mysqli_query($conn, "UPDATE `product` SET unitprice = '$unitprice', name='$name', des='$des', unit='$new_unit' WHERE id = '$update_id'");
  
  if($update_query){
      if ($new_unit > $old_unit) {
          $added_stock = $new_unit - $old_unit;
          
          // FIXED: Removed the forced 'created_at' 
          $log_insert = mysqli_query($conn, "INSERT INTO purchase(name, des, unit, unitprice) VALUES ('$name', '$des', '$added_stock', '$unitprice')");
          
          if($log_insert) {
              $message = "<div class='alert alert-success m-3'><strong>Success!</strong> Details updated and <strong>$added_stock new units</strong> were logged to Purchase Report.</div>";
          }
      } elseif ($new_unit < $old_unit) {
          $deducted = $old_unit - $new_unit;
          $message = "<div class='alert alert-warning m-3'><strong>Notice:</strong> Stock was reduced by $deducted units (Adjustment).</div>";
      } else {
          $message = "<div class='alert alert-success m-3'><strong>Success!</strong> Product details updated.</div>";
      }
  } else {
      $message = "<div class='alert alert-danger m-3'><strong>Error:</strong> " . mysqli_error($conn) . "</div>";
  }
}

if(isset($_GET['remove'])){
  $remove_id = (int)$_GET['remove'];
  mysqli_query($conn, "DELETE FROM `product` WHERE id = '$remove_id'");
  header('location:index.php');
}

$sql = "SELECT * FROM product";
$result = $conn -> query ($sql);
?>

<html>
<head>
    <title>Stock Status</title>
</head>
<body>
    <?php if(!empty($message)) echo $message; ?>

    <div class="container table-wrapper">
    <h5>Stock Status</h5>
    <table class="table align-middle">
  <thead>
    <tr>
      <th scope="col" style="width: 25%;">Product Name</th>
      <th scope="col" style="width: 25%;">Description</th>
      <th scope="col" style="width: 15%;">Unit</th>
      <th scope="col" style="width: 20%;">Unit Price</th>
      <th scope="col" style="width: 15%;">Action</th>
    </tr>
  </thead>
  <tbody>
      <?php
          if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
              $is_empty = ($row['unit'] <= 0);
              $row_class = $is_empty ? "table-danger" : ""; 
              ?>
             <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
               <tr class="<?php echo $row_class; ?>">
                <input type="hidden" name="update_id" value="<?php echo $row['id'];?>">
                <td><input type="text" class="form-control" name="update_name" value="<?php echo $row['name'];?>"></td>
                <td><input type="text" class="form-control" name="update_des" value="<?php echo $row['des'];?>"></td>
                <td>
                  <input type="number" class="form-control <?php echo $is_empty ? 'is-invalid' : ''; ?>" name="update_unit" value="<?php echo $row['unit'];?>">
                  <?php if($is_empty): ?><div class="invalid-feedback fw-bold">OUT OF STOCK</div><?php endif; ?>
                </td>
                <td>
                  <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" class="form-control" name="update_unitprice" value="<?php echo $row['unitprice'];?>">
                  </div>
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <button type="submit" class="btn <?php echo $is_empty ? 'btn-warning' : 'btn-primary'; ?>" name="update_btn" onclick="return checkChanges(this);">
                        <?php echo $is_empty ? 'Restock' : 'Update'; ?>
                    </button>
                    <a class="btn btn-danger" href="index.php?remove=<?php echo $row['id']; ?>" onclick="return confirm('CRITICAL WARNING: Delete product?');">Delete</a>
                  </div>
                </td>
                </tr>
                </form>
                <?php }
        } else {
            echo "<tr><td colspan='5'>0 results available.</td></tr>";
        }
        ?>
  </tbody>
</table>
</div>

<script>
function checkChanges(button) {
    let changed = false;
    let row = button.closest('tr');
    let inputs = row.querySelectorAll('input[type="text"], input[type="number"]');
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value !== inputs[i].defaultValue) { changed = true; break; }
    }
    if (!changed) {
        alert("No edits were detected. The product details are already up to date.");
        return false; 
    }
    return confirm('Are you sure you want to commit these changes to the live database?');
}
</script>
</body>
</html>