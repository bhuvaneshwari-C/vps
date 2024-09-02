<?php 
include './database_conn.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql_products = "SELECT * FROM product_details WHERE product_category = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql_products);
$stmt->bind_param("s", $category);
$stmt->execute();
$result_products = $stmt->get_result();

$product_data = [];

if ($result_products->num_rows > 0) {
    while($row = $result_products->fetch_assoc()) {
        $product = [
            'id' => $row['id'],
            'name' => $row['product_name'],
            'description' => $row['product_description'],
            'image' => base64_encode($row['product_img']),
            'pdf_name' => '',
            'pdf_content' => ''
        ];

        // Check if there's a PDF and split the string into name and content
        if (!empty($row['product_pdf'])) {
            list($product['pdf_name'], $product['pdf_content']) = explode('::', $row['product_pdf']);
        }

        $product_data[] = $product;
    }
}

echo json_encode($product_data);
?>
