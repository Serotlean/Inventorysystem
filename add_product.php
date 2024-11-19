<?php
$page_title = 'Add Product';
require_once('includes/load.php');
require_once('includes/config2.php');
// Check what level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    $session->msg('d', 'Unauthorized access.');
    redirect('login.php', false);
}
$seller_id = $_SESSION['user_id']; // Seller ID from session
?>

<?php
if (isset($_POST['add_product'])) {
    // Form validation and data sanitization
    $name = remove_junk($db->escape($_POST['product-title']));
    $quantity = remove_junk($db->escape($_POST['product-quantity']));
    $buy_price = remove_junk($db->escape($_POST['buying-price']));
    $sale_price = remove_junk($db->escape($_POST['saleing-price']));
    $categorie_id = remove_junk($db->escape($_POST['product-categorie']));
    $media_id = empty($_POST['product-photo']) ? 'NULL' : remove_junk($db->escape($_POST['product-photo'])); // Handle media_id
    $date = make_date();  // Current date

    // Insert product into the main products table with seller_id
    $query = "INSERT INTO products (name, quantity, buy_price, sale_price, categorie_id, media_id, date, seller_id) 
              VALUES ('{$name}', '{$quantity}', '{$buy_price}', '{$sale_price}', '{$categorie_id}', {$media_id}, '{$date}', '{$seller_id}')";
              
    if ($db->query($query)) {
        // Insert into the fish table in the e-commerce system
        $query_fish = "INSERT INTO fish (name, seller_id, description, quantity, price, created_at, updated_at) 
                       VALUES ('{$name}', '{$seller_id}', 'Description not set', '{$quantity}', '{$sale_price}', NOW(), NOW())";
        $fish_exe = mysqli_query($con2, $query_fish);

        if ($fish_exe) {
          // Get the last inserted fish ID
          $fish_id = $db->insert_id();
      
          if (!empty($media_id) && is_numeric($media_id)) {
            $query_image = "INSERT INTO fish_images (fish_id, media_id) VALUES ('{$fish_id}', {$media_id})";
            if ($db->query($query_image)) {
                $session->msg('s', "Product added successfully with image!");
                redirect('product.php', false);
            } else {
                $error = $db->error;
                $session->msg('d', "Error on this Query: {$query_image} - Error: {$error}");
                redirect('add_product.php', false);
            }
        } else {
            $session->msg('d', 'Invalid media ID. Image not added.');
            redirect('add_product.php', false);
        }
       } else {
          $session->msg('d', 'Failed to insert into the fish table!');
          redirect('add_product.php', false); // Stay on the add product page
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
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Product</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="product-title" placeholder="Product Title" required>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control" name="product-categorie">
                    <option value="">Select Product Category</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>"><?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <select class="form-control" name="product-photo">
                    <option value="">Select Product Photo</option>
                    <?php foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="buying-price" placeholder="Buying Price" required>
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price" required>
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" name="add_product" class="btn btn-danger">Add Product</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
