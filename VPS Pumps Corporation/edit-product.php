<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ./login/login.php");
    exit();
}

include './database_conn.php';

// Retrieve product ID from query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialize variables for the product
$product_name = '';
$product_description = '';
$product_category = '';
$keywords = '';
$price = '';
$product_img = '';
$product_pdf = '';

// Fetch existing product data
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT product_name, product_description, product_category, keywords, price, product_img, product_pdf FROM product_details WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($product_name, $product_description, $product_category, $keywords, $price, $product_img, $product_pdf);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Invalid product ID.";
    exit();
}
// Handle the case where product_pdf might not contain '::'
if (strpos($product_pdf, '::') !== false) {
    list($pdf_name, $pdf_content_encoded) = explode('::', $product_pdf);
    $pdf_content = base64_decode($pdf_content_encoded);
} else {
    $pdf_name = '';
    $pdf_content = '';
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_category = $_POST['product_category'];
    $keywords = $_POST['keywords'];
    $price = $_POST['price'];

   // Handle product image upload
   if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] == 0) {
    $product_img = file_get_contents($_FILES['product_img']['tmp_name']);
}

// Handle PDF upload
if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
    $pdf_content = file_get_contents($_FILES['pdf']['tmp_name']);
    $pdf_name = $_FILES['pdf']['name'];
    $product_pdf = $pdf_name . '::' . base64_encode($pdf_content);
}

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE product_details SET product_name = ?, product_description = ?, product_category = ?, keywords = ?, price = ?, product_img = ?, product_pdf = ? WHERE id = ?");

// Bind parameters: 5 strings, 2 blobs, and 1 integer
$stmt->bind_param("sssssssi", $product_name, $product_description, $product_category, $keywords, $price, $product_img, $product_pdf, $product_id);

if ($stmt->execute()) {
    echo "<script>
        alert('Product Updated successfully');
        window.location.href='product-list.php'; // Redirect to product list or another page
        </script>";
    exit();
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>
<body> 
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar d-flex flex-column">
            <div class="sidebar-header mb-5">
                <img src="./assets/Images/favicon.png" alt="logo" class="logo">  
            </div>
            <hr class="sidebar-divider">
            <div class="menu">
                <h5>Menu</h5>
            </div>
            <nav class="nav flex-column mt-1">
                <a href="dashboard.php" class="nav-link mb-3"><i class="bi bi-house icon"></i><span class="text">Dashboard</span></a>
                <a href="add-product.php" class="nav-link mb-3"><i class="icon bi bi-plus-square"></i><span class="text">Add Product</span></a>
                <a href="product-list.php" class="nav-link mb-3"><i class="icon bi bi-list"></i><span class="text">Product List</span></a>
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
                        <button class="btn btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</button>
                    </a>
                </div>
            </div>
        </nav>
        <!-- Offcanvas Navbar -->
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
                        <a href="add-product.php" class="nav-link mb-3"><i class="icon bi bi-plus-square"></i><span class="text">Add Product</span></a>
                        <a href="product-list.php" class="nav-link mb-3"><i class="icon bi bi-list"></i><span class="text">Product List</span></a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="container mt-3">
            <div class="form-container">
                <div class="modal-header header-border justify-content-between p-0 mb-3">
                    <h5 class="modal-title">Edit Product</h5>
                    <a href="product-list.php">
                        <button type="button" class="btn-close position-static" data-bs-dismiss="modal" aria-label="Close"></button>
                    </a>
                </div>
                <hr>
                <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <div id="imagePreview" class="mt-3">
                        <img id="imageElement" src="<?php echo $product_img ? 'data:image/jpeg;base64,' . base64_encode($product_img) : ''; ?>" alt="Image Preview" class="img-fluid" style="<?php echo empty($product_img) ? 'display:none;' : 'display:block;'; ?>  max-height: 200px;" />
                    </div>
                    <div class="mb-4 mt-5">
                        <label for="productImage" class="form-label">Product Image</label>
                        <div class="input-group">
                            <input type="file" id="productImage" name="product_img" class="form-control" onchange="previewImage(event)" accept=".jpg, .jpeg, .png, .svg">
                            <button type="button" class="add-reset-btn" onclick="resetImagePreview()">Reset</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
                    </div>
                    <div class="mb-4">
                        <label for="product_description" class="form-label">Product Description</label>
                        <textarea class="form-control" id="product_description" name="product_description" rows="3"><?php echo htmlspecialchars($product_description); ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="product_category" class="form-label">Product Category</label>
                        <select class="form-select" id="product_category" name="product_category">
                            <option>Select</option>
                            <option <?php echo ($product_category == 'Solar Product') ? 'selected' : ''; ?>>Solar Product</option>
                            <option <?php echo ($product_category == 'Agricultural Product') ? 'selected' : ''; ?>>Agricultural Product</option>
                            <option <?php echo ($product_category == 'Domestic Product') ? 'selected' : ''; ?>>Domestic Product</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="keywords" class="form-label">Keywords</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" value="<?php echo htmlspecialchars($keywords); ?>">
                    </div>
                    <div class="mb-4">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>">
                    </div>
                    <div class="mb-5">
    <label for="pdf" class="form-label">Upload PDF</label>
    <div class="input-block mb-3">
        <?php
        if (!empty($pdf_name)) {
            echo '<p><a href="data:application/pdf;base64,' . base64_encode($pdf_content) . '" target="_blank" style="color: green; text-decoration: none;">' . htmlspecialchars($pdf_name) . '</a></p>';
        } else {
            echo '<p>No PDF uploaded.</p>';
        }
        ?>
        <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf">
    </div>
</div>

                    <button type="submit" class="btn add-submit-btn text-white">Submit</button>
                </form>
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
// preview
function previewImage(event) {
    const imageElement = document.getElementById('imageElement');
    imageElement.src = URL.createObjectURL(event.target.files[0]);
    imageElement.style.display = 'block';
}

function resetImagePreview() {
    const imageElement = document.getElementById('imageElement');
    const inputFile = document.getElementById('productImage');
    inputFile.value = '';
    imageElement.src = '';
    imageElement.style.display = 'none';
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
