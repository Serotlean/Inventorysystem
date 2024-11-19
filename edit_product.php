<?php
$page_title = 'Edit Product';
require_once('includes/load.php');

// Check user level permission to view this page
page_require_level(2);

// Fetch product details using ID from GET request
$product = find_by_id('products', (int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');

if (!$product) {
  $session->msg("d", "Missing product id.");
  redirect('product.php');
}

if (isset($_POST['update_product'])) {
    // Validate the required fields
    $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
    validate_fields($req_fields);

    if (empty($errors)) {
        // Clean the input data
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = (int)$_POST['product-categorie'];
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['buying-price']));
        $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
        
        // Handle product photo
        if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
            $media_id = '0'; // No image selected
        } else {
            $media_id = remove_junk($db->escape($_POST['product-photo']));
        }

        // Update the product in the database
        $query = "UPDATE products SET";
        $query .= " name = '{$p_name}', quantity = '{$p_qty}', buy_price = '{$p_buy}', sale_price = '{$p_sale}', categorie_id = '{$p_cat}', media_id = '{$media_id}'";
        $query .= " WHERE id = '{$product['id']}'";
        
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            // Synchronize with the `fishop` database
            $fishop_query = "UPDATE fishop.products SET";
            $fishop_query .= " name = '{$p_name}', quantity = '{$p_qty}', buy_price = '{$p_buy}', sale_price = '{$p_sale}', category_id = '{$p_cat}'";
            $fishop_query .= " WHERE id = '{$product['id']}'"; // Ensure ID matches in both databases
            $fish_exe = mysqli_query($con2, $query_fish);

            if ($fish_exe) {
              // Get the last inserted fish ID
              $fish_id = $db->insert_id();

        }
        
        // Check if update is successful
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Product updated successfully");
            redirect('product.php', false);
        } else {
            $session->msg('d', "Failed to update product");
            redirect('edit_product.php?id=' . $product['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_product.php?id=' . $product['id'], false);
    }
}
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Edit Product</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="col-md-7">
                <form method="post" action="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="clearfix">
                    <!-- Product Name -->
                    <div class="form-group">
                        <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']); ?>" required>
                    </div>

                    <!-- Category and Image Selection -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="product-categorie">
                                    <option value="">Select Category</option>
                                    <?php foreach ($all_categories as $cat): ?>
                                        <option value="<?php echo (int)$cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?>>
                                            <?php echo remove_junk($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <select class="form-control" name="product-photo">
                                    <option value="">No image</option>
                                    <?php foreach ($all_photo as $photo): ?>
                                        <option value="<?php echo (int)$photo['id']; ?>" <?php if ($product['media_id'] === $photo['id']): echo "selected"; endif; ?>>
                                            <?php echo $photo['file_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Product Quantity and Pricing -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="qty">Quantity</label>
                                <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="qty">Buying Price</label>
                                <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="qty">Selling Price</label>
                                <input type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="update_product" class="btn btn-danger">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
