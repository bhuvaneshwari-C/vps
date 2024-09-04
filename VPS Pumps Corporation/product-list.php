<?php
session_start();

if (!isset($_SESSION['username'])) {
    // If the session is not set, redirect to the login page
    header("Location: login/login.php");
    exit();
}
include './database_conn.php';

// Fetch product data from the database
$sql = "SELECT * FROM product_details ORDER BY id DESC";
$result = $conn->query($sql);
$serialNumber = 1;

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Collapsible Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <a href="dashboard.php" class="nav-link mb-3"><i class="bi bi-house icon"></i><span class="text">Dashboard</span></a>
        <a href="add-product.php" class="nav-link  mb-3"><i class="icon bi bi-plus-square"></i><span class="text">Add Product</span></a>
        <a href="product-list" class="nav-link mb-3"><i class="icon bi bi-list"></i><span class="text">Product List</span></a>
        </nav>
  </div>
</div>
</div>
    </div>
    <!-- Table -->
            <div class="table-responsive main-content p-5">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) : ?>
                            <?php while($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $serialNumber++; ?></td>
                                    <td>
                                        <?php if ($row['product_img']) : ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_img']); ?>" alt="Product Image" style="max-width: 100px;">
                                        <?php else : ?>
                                            No Image
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['product_name']; ?></td>
                                    <td><?php echo $row['product_description']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td class="action-buttons">
                                        <div style='display:flex; align-items:center;'>
                                        <a href="edit-product.php?id=<?php echo $row['id']; ?>"><i class='fa-solid fa-pencil m-r-5'></i></a>
                                        <form action="delete_product.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product?');" class="btn btn-danger btn-sm">
                                                <i class='fa-regular fa-trash-can m-r-5'></i>
                                            </button>
                                        </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">No products found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
    </div>
    <script>
        //Toggle Sidebar
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
