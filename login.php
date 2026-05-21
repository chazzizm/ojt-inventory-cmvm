<?php 
SESSION_START();

if(isset($_SESSION['auth']))
{
    if($_SESSION['auth']==1)
    {
        header("location:index.php");
    }
}

if (isset($_POST['submit'])) 
{
    $id = $_POST['id'];
    $pass = $_POST['password'];

    if($id=='admin' && $pass=='admin')
    {
        $_SESSION['auth']=1;
        header("location:index.php");
    }
    else
    {
        echo "<script>alert('Invalid Credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Secure Admin Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body style="background-color: #1e293b; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Poppins', sans-serif;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); overflow: hidden;">
                <div class="card-header text-center" style="background-color: #fff; padding: 30px 20px 10px; border-bottom: none;">
                    <i class="fas fa-shield-alt" style="font-size: 40px; color: #3b82f6; margin-bottom: 15px;"></i>
                    <h3 style="font-weight: 600; color: #1e293b;">Admin Portal</h3>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="mb-4">
                            <label style="font-weight: 500; color: #64748b; margin-bottom: 8px;">Username</label>
                            <input type="text" class="form-control" style="padding: 12px; border-radius: 8px;" placeholder="Enter admin ID" name="id" required>
                        </div>
                        <div class="mb-4">
                            <label style="font-weight: 500; color: #64748b; margin-bottom: 8px;">Password</label>
                            <input type="password" class="form-control" style="padding: 12px; border-radius: 8px;" placeholder="Enter password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" value="Login" class="btn btn-primary" style="padding: 12px; font-weight: 500; font-size: 16px; background-color: #3b82f6; border: none;" name="submit">Secure Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>
</html>