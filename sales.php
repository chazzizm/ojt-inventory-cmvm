<?php
    include "header.php";
    include "connection.php";

$message = ""; 

if (isset($_POST['submit'])) 
{
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $unit = (int)$_POST['unit'];
    $unitprice = (int)$_POST['unitprice'];
    $unitsale = (int)$_POST['unitsale'];
    
    if($unitsale <= 0) {
         $message = "<div class='alert alert-warning m-3'><strong>Notice:</strong> Please enter a valid quantity.</div>";
    }
    elseif($unit >= $unitsale)
    {
        $totalprice = $unitprice * $unitsale;
        $u_unit = $unit - $unitsale;

        // FIXED: Removed the forced 'created_at' to let the DB handle it natively like in purchase.php
        $insertsql = "INSERT INTO sales(name, sellunit, totalprice, productid) VALUES ('$name', '$unitsale', '$totalprice', '$id')";
        $update_quantity_query = "UPDATE `product` SET unit = '$u_unit' WHERE id = '$id'";

        if ($conn->query($insertsql) === TRUE && $conn->query($update_quantity_query) === TRUE) 
        {
            $message = "<div class='alert alert-success m-3'><strong>Success!</strong> Sold $unitsale units of $name. The transaction has been permanently logged.</div>";
        } 
        else 
        {
            $message = "<div class='alert alert-danger m-3'><strong>Database Error:</strong> " . $conn->error . "</div>";
        }
    }
    else
    {
        $message = "<div class='alert alert-danger m-3'><strong>Failed!</strong> Not enough stock available.</div>";
    }
}

$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Outbound Sales</title>
</head>
<body>
    <?php if(!empty($message)) echo $message; ?>

    <div class="container table-wrapper">
    <h5>Outbound / Sales</h5>
    <table class="table align-middle">
  <thead>
    <tr>
      <th scope="col" style="width: 25%;">Product Name</th>
      <th scope="col" style="width: 25%;">Description</th>
      <th scope="col" style="width: 15%;">Stock Level</th>
      <th scope="col" style="width: 15%;">Unit Price</th>
      <th scope="col" style="width: 20%;">Execute Sale</th>
    </tr>
  </thead>
  <tbody>
      <?php
          if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
              $is_empty = ($row['unit'] <= 0);
              $row_class = $is_empty ? "table-danger" : ""; 
              ?>
               <tr class="<?php echo $row_class; ?>">
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['des'];?></td>
                <td>
                    <span class="fs-5 <?php echo $is_empty ? 'text-danger fw-bold' : ''; ?>">
                        <?php echo $row['unit'];?>
                    </span>
                    <?php if($is_empty): ?><br><small class="text-danger fw-bold">OUT OF STOCK</small><?php endif; ?>
                </td>
                <td>₱<?php echo number_format($row['unitprice'], 2);?></td>
                <td>
                  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="d-flex gap-2 m-0" onsubmit="return confirm('Confirm sale of ' + this.unitsale.value + ' units of <?php echo addslashes($row['name']); ?>?');">
                        <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                        <input type="hidden" name="name" value="<?php echo $row['name'];?>">
                        <input type="hidden" name="unit" value="<?php echo $row['unit'];?>">
                        <input type="hidden" name="unitprice" value="<?php echo $row['unitprice'];?>">
                        <input type="number" name="unitsale" class="form-control form-control-sm" style="width: 80px;" placeholder="Qty" min="1" max="<?php echo $row['unit'];?>" <?php echo $is_empty ? 'disabled' : 'required'; ?>>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit" <?php echo $is_empty ? 'disabled' : ''; ?>>Sell Now</button>
                  </form>
                </td>
                </tr>
                <?php }
        } else {
            echo "<tr><td colspan='5'>0 results available inside the inventory.</td></tr>";
        }
        ?>
  </tbody>
</table>
</div>
</body>
</html>