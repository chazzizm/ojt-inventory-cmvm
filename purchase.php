<?php
    include "header.php";
    include "connection.php";

    // Temporarily force Linux to show us any hidden errors
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    if (isset($_POST['submit'])) 
    {
        // Add protection and force strict integers for Ubuntu MySQL
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $des = mysqli_real_escape_string($conn, $_POST['des']);
        $unit = (int)$_POST['unit']; 
        $unitprice = (int)$_POST['unitprice'];

        $insertsql = "INSERT INTO product(name, des, unit, unitprice) VALUES ('$name', '$des', '$unit','$unitprice')";
        $insertsql1 = "INSERT INTO purchase(name, des, unit, unitprice) VALUES ('$name', '$des', '$unit','$unitprice')";
        
        $success = true;
        $error_msg = "";

        if (!$conn->query($insertsql1)) {
            $success = false;
            $error_msg .= "Purchase Table Error: " . $conn->error . "<br>";
        }
        
        if (!$conn->query($insertsql)) {
            $success = false;
            $error_msg .= "Product Table Error: " . $conn->error . "<br>";
        }

        if ($success) {
            echo "<div class='alert alert-success m-3'><strong>Success!</strong> Item successfully added to live database.</div>";
        } else {
            echo "<div class='alert alert-danger m-3'><strong>Database Blocked It:</strong><br>$error_msg</div>";
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h5>Purchase</h5>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <div class="mb-3">
    <label for="exampleInputName" class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" id="exampleInputName">
    
  </div>
  <div class="mb-3">
    <label for="exampleInputDes" class="form-label">Description</label>
    <input type="text" name="des" class="form-control" id="exampleInputDes">
  </div>
  <div class="mb-3">
    <label for="exampleInputUnit" class="form-label">Unit</label>
    <input type="number" name="unit" class="form-control" id="exampleInputUnit">
  </div>
  <div class="mb-3">
    <label for="exampleInputUnitprice" class="form-label">Unit Price</label>
    <input type="number" name="unitprice" class="form-control" id="exampleInputUnitprice">
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>
    </div>
</body>
</html>