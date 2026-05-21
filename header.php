<?php
 SESSION_START();

 if(isset($_SESSION['auth']))
 {
    if($_SESSION['auth']!=1)
    {
        header("location:login.php");
    }
 }
 else
 {
    header("location:login.php");
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enterprise Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="sidebar">
  <div class="brand"><i class="fas fa-layer-group"></i> IMS PRO</div>
  <a href="index.php"><i class="fas fa-boxes"></i> Live Stock</a>
  <a href="purchase.php"><i class="fas fa-shopping-cart"></i> Inbound / Purchase</a>
  <a href="sales.php"><i class="fas fa-chart-line"></i> Outbound / Sales</a>
  <a href="purchase_report.php"><i class="fas fa-file-invoice"></i> Purchase Logs</a>
  <a href="sales_report.php"><i class="fas fa-file-invoice-dollar"></i> Sales Logs</a>
  
  <div style="position: absolute; bottom: 30px; width: 100%;">
      <a href="logout.php" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Secure Logout</a>
  </div>
</div>

<div class="main-content">