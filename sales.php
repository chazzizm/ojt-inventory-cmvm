<?php
    include "header.php";
    include "connection.php";

$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);
$message = ""; // This will hold our UI alerts

if (isset($_POST['submit'])) 
{
    // Force strict integers for Ubuntu MySQL
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $unit = (int)$_POST['unit'];
    $unitprice = (int)$_POST['unitprice'];
    $unitsale = (int)$_POST['unitsale'];
    
    if($unitsale <= 0) {
         $message = "<div class='alert alert-warning m-3'>Please enter a valid amount to sell.</div>";
    }
    elseif($unit >= $unitsale)
    {
        $totalprice = $unitprice * $unitsale;
        $u_unit = $unit - $unitsale;

        $insertsql = "INSERT INTO sales(name, sellunit, totalprice, productid) VALUES ('$name', '$unitsale', '$totalprice','$id')";
        $update_quantity_query = "UPDATE `product` SET unit = '$u_unit'  WHERE id = '$id'";

        if ($conn->query($insertsql) === TRUE && $conn->query($update_quantity_query) === TRUE) 
        {
            $message = "<div class='alert alert-success m-3'><strong>Success!</strong> Sold $unitsale units of $name.</div>";
            // Refresh the table data so it immediately shows the new stock count
            $result = mysqli_query($conn, $sql); 
        } 
        else 
        {
            $message = "<div class='alert alert-danger m-3'><strong>Database Error:</strong> " . $conn->error . "</div>";
        }
    }
    else
    {
        $message = "<div class='alert alert-danger m-3'><strong>Failed!</strong> Not enough stock. (Available: $unit)</div>";
    }
}
?>
<?php echo $message; ?>
<html>
<head>
    <title></title>
</head>
<body>
    <div class="container">
    <h5>Sales</h5>
    <table class="table table-striped">
  <thead>
    <tr>
      <!--<th scope="col">#</th>-->
      <th scope="col">Product Name</th>
      <th scope="col">Description</th>
      <th scope="col">Unit</th>
      <th scope="col">Unit Price</th>
      <th scope="col">Sell Unit</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
   
      <?php
          if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
              ?>
             <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
               <tr>
               <input type="hidden" name="id"  value="<?php echo $row['id'];?>">
                <input type="hidden" name="name"  value="<?php echo $row['name'];?>">
                <input type="hidden" name="des"  value="<?php echo $row['des'];?>">
               <input type="hidden" name="unit"  value="<?php echo $row['unit'];?>">
                <input type="hidden" name="unitprice"  value="<?php echo $row['unitprice'];?>">
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['des'];?></td>
                <td><?php echo $row['unit'];?></td>
                <td><?php echo $row['unitprice'];?></td>
                <td><div class="mb-3">
                    <input type="number" name="unitsale" class="form-control" id="exampleInputUnit">
               </div></td>
                <td><button type="submit" class="btn btn-primary" name="submit">Sell Now</button></td>
                </tr>
                </form>
                <?php }
        } else {
            echo "0 results";
        }
        ?>
      

    
  </tbody>
</table>
</div>
</body>
</html>