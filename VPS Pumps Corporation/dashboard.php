<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login if not logged in
    header("Location: login/login.php");
    exit();
}
include './database_conn.php';

$agriculture_count = 0;
$solar_count = 0;
$domestic_count = 0;

$sql_agriculture = "SELECT COUNT(*) as agriculture_count FROM product_details WHERE product_category = 'Agricultural Product'";
$sql_solar = "SELECT COUNT(*) as solar_count FROM product_details WHERE product_category = 'Solar Product'";
$sql_domestic = "SELECT COUNT(*) as domestic_count FROM product_details WHERE product_category = 'Domestic Product'";

$result_agriculture = $conn->query($sql_agriculture);
$result_solar = $conn->query($sql_solar);
$result_domestic = $conn->query($sql_domestic);

if ($result_agriculture) {
    $row = $result_agriculture->fetch_assoc();
    $agriculture_count = $row['agriculture_count'];
}

if ($result_solar) {
    $row = $result_solar->fetch_assoc();
    $solar_count = $row['solar_count'];
}

if ($result_domestic) {
    $row = $result_domestic->fetch_assoc();
    $domestic_count = $row['domestic_count'];
}

$sql = "SELECT COUNT(*) as product_count FROM product_details";
$result = $conn->query($sql);
$product_count = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $product_count = $row['product_count'];
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <link rel="stylesheet" href="./css/adminPage-style.css" />
</head>
<body>
<div class="container-fluid">
    <div class="row">
 <!-- Sidebar -->
   <div id="sidebar" class="sidebar d-flex flex-column">
    <div class="sidebar-header mb-5">
        <img src="./images/favicon.png" alt="logo" class="logo">  
    </div>
    <hr class="sidebar-divider">
    <div class="menu">
        <h5>Menu</h5>
    </div>
        <nav class="nav flex-column mt-1">
        <a href="dashboard.php" class="nav-link mb-3 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="bi bi-house icon"></i><span class="text">Dashboard</span></a>
            <a href="add-product.php" class="nav-link  mb-3 <?php echo basename($_SERVER['PHP_SELF']) == 'add-product.php' ? 'active' : ''; ?>"><i class="icon bi bi-folder-plus"></i><span class="text">Add Product</span></a>
            <a href="product-list.php" class="nav-link mb-3 <?php echo basename($_SERVER['PHP_SELF']) == 'product-list.php' ? 'active' : ''; ?>"><i class="icon bi bi-list"></i><span class="text">Product List</span></a>
        </nav>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom nav-content">
    <div class="container-fluid">
            <div class="sidebar-toggler" type="button" onclick="toggleSidebar()">
                <i id="sidebarToggleIcon" class="bi bi-list text-white"></i>
            </div>
            <div class="logout-btn ms-auto">
                <a href="../logout.php">
                <button class="btn btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</button></a>
            </div>
        </div>
    </nav>
<!-- off canvas navbar -->
<div class="canvas-navbar">
<button class="off-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
  <i class="bi bi-list text-white"></i>
</button>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <img src="./assets/Images/favicon.png" alt="logo" class="offcanvas-title logo" id="offcanvasExampleLabel">
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <nav class="nav flex-column mt-1">
        <a href="#" class="nav-link mb-3"><i class="bi bi-house icon"></i><span class="text">Dashboard</span></a>
        <a href="add-product.php" class="nav-link  mb-3"><i class="icon bi bi-plus-square"></i><span class="text">Add Product</span></a>
        <a href="product-list.php" class="nav-link mb-3"><i class="icon bi bi-list"></i><span class="text">Product List</span></a>
        </nav>
  </div>
</div>
</div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <h3 class="p-4">Dashboard</h3>
        <div class="row">
              <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 mb-3">
                 <div class="card p-4 shadow text-center align-items-center">
                     <div class="card-content text-left">
                       <div class="text-count">Agriculture Products</div>
                         <h5><?php echo $agriculture_count; ?></h5>
                 </div>
            <div class="card-icon">
                <i class="bi bi-tree"></i>
            </div>
        </div>
    </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 mb-3">
                <div class="card p-4 shadow text-center align-items-center">
                    <div class="card-content text-left">
                        <div class="text-count">Solar Products</div>
                        <h5><?php echo $solar_count; ?></h5>
                    </div>
                    <div class="card-icon">
                    <i class="bi bi-brightness-high card-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 mb-3">
                <div class="card p-4 shadow text-center align-items-center">
                    <div class="card-content text-left">
                        <div class="text-count">Domestic Products</div>
                        <h5><?php echo $domestic_count; ?></h5>  
                    </div>
                    <div class="card-icon">
                    <i class="bi bi-house-door card-icon"></i></div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 mb-3">
                <div class="card p-4 shadow text-center align-items-center">
                    <div class="card-content text-left">
                        <div class="text-count">Total Products</div>
                        <h5><?php echo $product_count; ?></h5>  
                    </div>
                    <div class="card-icon">
                    <i class="bi bi-boxes card-icon"></i></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
    function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');
    const mainContent = document.querySelector('.main-content');
    const sidebarToggler = document.querySelector('.sidebar-toggler');

    sidebar.classList.toggle('collapsed');

    if (sidebar.classList.contains('collapsed')) {
        mainContent.style.marginLeft = '80px';
        sidebarToggler.style.marginLeft = '80px';
    } else {
        mainContent.style.marginLeft = '240px';
        sidebarToggler.style.marginLeft = '250px';
    }

    // Check for mobile view
    const mobileView = window.matchMedia('(max-width: 760px)');
    if (mobileView.matches) {
        mainContent.style.marginLeft = '0';
    }
}
window.addEventListener('resize', () => {
    const mobileView = window.matchMedia('(max-width: 760px)');
    if (mobileView.matches) {
        document.querySelector('.main-content').style.marginLeft = '0';
    } else {
        // Update the margin of the main content based on the sidebar's state
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('collapsed')) {
            document.querySelector('.main-content').style.marginLeft = '80px';
        } else {
            document.querySelector('.main-content').style.marginLeft = '240px';
        }
    }
});
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
