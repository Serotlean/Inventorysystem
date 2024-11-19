<?php
$page_title = 'All Product';
require_once('includes/load.php');
require_once('includes/config2.php');
// Check what level user has permission to view this page
page_require_level(2);

// Check if the logged-in user is a seller
$user = current_user();
if ($user['user_level'] == 2) { // Assuming 2 is the level for sellers
    // Fetch only the products added by this seller
    $products = join_product_table_by_seller($user['id']);
} else {
    // Admin or other users with higher permission levels can see all products
    $products = join_product_table();
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="add_product.php" class="btn btn-primary">Add New</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Photo</th>
                            <th> Product Title </th>
                            <th class="text-center" style="width: 10%;"> Categories </th>
                            <th class="text-center" style="width: 10%;"> In-Stock </th>
                            <th class="text-center" style="width: 10%;"> Buying Price </th>
                            <th class="text-center" style="width: 10%;"> Selling Price </th>
                            <th class="text-center" style="width: 10%;"> Product Added </th>
                            <th class="text-center" style="width: 100px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="text-center"><?php echo count_id(); ?></td>
                                    <td>
                                        <?php if ($product['media_id'] === '0'): ?>
                                            <img class="img-avatar img-circle" src="uploads/products/" alt="">
                                        <?php else: ?>
                                            <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                        <?php endif; ?>
                                    </td>
                                    <td> <?php echo remove_junk($product['name']); ?></td>
                                    <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                                    <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                                    <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                                    <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                                    <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                            <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No products found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
