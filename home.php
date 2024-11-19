<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false); }
  
  // Fetch counts for dashboard stats
  $user_count = count_by_id('users');
  $category_count = count_by_id('categories');
  $product_count = count_by_id('products');
  $sales_count = count_by_id('sales');
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>



<script>
// Display notification alert when a new notification is added
window.onload = function() {
  <?php if($result->num_rows > 0): ?>
    document.getElementById('notification-alert').style.display = 'block';
  <?php endif; ?>
}
</script>


<!-- Welcome Message -->
<div class="row">
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
         <h1>Welcome, <?php echo ucfirst($user['name']); ?> <hr> Inventory Management System</h1>
         <p>Browse around to find out the pages that you can access!</p>
      </div>
    </div>
 </div>
</div>

<?php include_once('layouts/footer.php'); ?>
