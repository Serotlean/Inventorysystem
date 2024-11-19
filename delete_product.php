<?php
  require_once('includes/load.php');

  // Get product ID from GET request
  $product_id = $_GET['id'];

  // Query to fetch the product's media ID (if you want to delete the associated image file)
  $query = "SELECT media_id, name FROM products WHERE id = {$product_id}";
  $result = $db->query($query);
  $product = $result->fetch_assoc();
  
  if ($product) {
      // Optionally delete the image file if necessary
      $image_path = 'uploads/' . $product['media_id']; // Assuming the path to the image
      if (file_exists($image_path)) {
          unlink($image_path); // Delete the image file
      }

      // Delete from the fish table as well (if it's related to the same product)
      $fish_delete_query = "DELETE FROM fish WHERE name = '{$product['name']}'";
      $db->query($fish_delete_query);
  }

  // Delete the product from the database
  $query = "DELETE FROM products WHERE id = {$product_id}";
  if ($db->query($query)) {
      $session->msg('s', 'Product deleted successfully from both products and fish tables');
      redirect('product.php', false);
  } else {
      $session->msg('d', 'Failed to delete product');
      redirect('product.php', false);
  }
?>
