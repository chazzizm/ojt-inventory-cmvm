<?php
    include "header.php";
    include "connection.php";

    $message = ""; // Safe feedback container

    if (isset($_POST['submit'])) 
    {
        // Protection & Type Casting for Ubuntu MySQL Strict Mode
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $des = mysqli_real_escape_string($conn, $_POST['des']);
        $unit = (int)$_POST['unit'];
        $unitprice = (int)$_POST['unitprice'];

        $insertsql = "INSERT INTO product(name, des, unit, unitprice) VALUES ('$name', '$des', '$unit','$unitprice')";
        $insertsql1 = "INSERT INTO purchase(name, des, unit, unitprice) VALUES ('$name', '$des', '$unit','$unitprice')";
        
        $success = true;
        
        if (!$conn->query($insertsql1)) {
            $success = false;
        }
        
        if (!$conn->query($insertsql)) {
            $success = false;
        }

        if ($success) {
            $message = "<div class='alert alert-success m-3'><strong>Success!</strong> Brand new product successfully logged and added to inventory.</div>";
        } else {
            $message = "<div class='alert alert-danger m-3'><strong>Database Error:</strong> " . $conn->error . "</div>";
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Inbound</title>
</head>
<body>
    <?php if(!empty($message)) echo $message; ?>

    <div class="container table-wrapper" style="max-width: 600px; margin-top: 30px;">
        <h5 class="mb-4"><i class="fas fa-cart-plus me-2"></i>Inbound Purchase / Restock</h5>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g., Mechanical Keyboard" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <input type="text" name="des" class="form-control" placeholder="e.g., RGB Hot-swappable" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Initial Stock Units</label>
        <input type="number" name="unit" class="form-control" placeholder="0" min="1" required>
      </div>
      <div class="mb-4">
         <label class="form-label">Unit Price</label>
      <div class="input-group">
      <span class="input-group-text">₱</span>
      <input type="number" name="unitprice" class="form-control" placeholder="0" min="1" required>
      </div>
      </div>
      <div class="d-grid">
         <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i> Add Product into Database</button>
      </div>
    </form>
    </div>
</body>
</html>