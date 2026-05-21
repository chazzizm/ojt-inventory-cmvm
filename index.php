<?php
    include "header.php";
    include "connection.php";

$message = ""; // Container for success/error alerts

if(isset($_POST['update_btn'])){
  $update_id = (int)$_POST['update_id'];
  $name = mysqli_real_escape_string($conn, $_POST['update_name']);
  $des = mysqli_real_escape_string($conn, $_POST['update_des']);
  $unit = (int)$_POST['update_unit'];
  $unitprice = (int)$_POST['update_unitprice'];
  
  $update_query = mysqli_query($conn, "UPDATE `product` SET unitprice = '$unitprice' , name='$name' , des='$des' ,unit='$unit'  WHERE id = '$update_id'");
  if($update_query){
      $message = "<div class='alert alert-success m-3'><strong>Success!</strong> Product details and stock levels have been updated.</div>";
  } else {
      $message = "<div class='alert alert-danger m-3'><strong>Error:</strong> Failed to update product details. " . mysqli_error($conn) . "</div>";
  }
}

if(isset($_GET['remove'])){
  $remove_id = (int)$_GET['remove'];
  $delete_query = mysqli_query($conn, "DELETE FROM `product` WHERE id = '$remove_id'");
  if($delete_query){
      $message = "<div class='alert alert-success m-3'><strong>Success!</strong> Product was permanently removed from the live stock database.</div>";
  } else {
      $message = "<div class='alert alert-danger m-3'><strong>Error:</strong> Failed to delete product. " . mysqli_error($conn) . "</div>";
  }
}

// Fetch the fresh list after modifications
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
    <table class="table table-striped align-middle">
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
              ?>
             <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
               <tr>
                <input type="hidden" name="update_id" value="<?php echo $row['id'];?>">
                <td><input type="text" class="form-control" name="update_name" value="<?php echo $row['name'];?>"></td>
                <td><input type="text" class="form-control" name="update_des" value="<?php echo $row['des'];?>"></td>
                <td><input type="number" class="form-control" name="update_unit" value="<?php echo $row['unit'];?>"></td>
                <td>
                  <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" class="form-control" name="update_unitprice" value="<?php echo $row['unitprice'];?>">
                  </div>
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary" name="update_btn" onclick="return checkChanges(this);">Update</button>
                    <a class="btn btn-danger" href="index.php?remove=<?php echo $row['id']; ?>" onclick="return confirm('CRITICAL WARNING: Are you sure you want to permanently delete this product from the live database?');">Delete</a>
                  </div>
                </td>
                </tr>
                </form>
                <?php }
        } else {
            echo "<tr><td colspan='5'>0 results available inside the inventory.</td></tr>";
        }
        ?>
  </tbody>
</table>
</div>

<script>
function checkChanges(button) {
    let changed = false;
    
    // Instead of looking at the broken form, we find the exact table row (tr) you clicked
    let row = button.closest('tr');
    
    // Target all text and number fields within that specific row
    let inputs = row.querySelectorAll('input[type="text"], input[type="number"]');
    
    // Check if the current typed value is different from the original database value
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value !== inputs[i].defaultValue) {
            changed = true;
            break; // Stop checking once we find at least one change
        }
    }

    // If nothing changed, popup the warning and cancel the form submission
    if (!changed) {
        alert("No edits were detected. The product details are already up to date.");
        return false; 
    }

    // If changes were found, proceed to the final confirmation prompt
    return confirm('Are you sure you want to commit these changes to the live database?');
}
</script>
</body>
</html>