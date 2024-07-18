<?php
@include 'config.php';
if(isset($_POST['add_to_wishlist'])){
   $product_Name=$_POST['product_name'];
   $product_Price=$_POST['product_price'];
   $product_Image=$_POST['product_image'];
   $product_Quantity=1;
   $select_wishlist= mysqli_query($conn,"SELECT * from wishlist WHERE name = '$product_Name'");
   if(mysqli_num_rows($select_wishlist)>5){
      $message []= "Product already added to wishlist";
   }
   else{
      $insert_products=mysqli_query($conn,"INSERT into wishlist(name,price, image, quantity) VALUES ('$product_Name','$product_Price','$product_Image','$product_Quantity')");
      $message[]="Product added to wishlist";
   }
}
if(isset($_POST['add_to_cart'])){
   $product_Name=$_POST['product_name'];
   $product_Price=$_POST['product_price'];
   $product_Image=$_POST['product_image'];
   $product_Quantity=1;
   
   $select_cart= mysqli_query($conn,"SELECT * from cart WHERE name = '$product_Name'");
   if(mysqli_num_rows($select_cart)>2){
      $message []= "Product already added to cart";
   }
   else{
      $insert_products=mysqli_query($conn,"INSERT into cart(name,price, image, quantity) VALUES ('$product_Name','$product_Price','$product_Image','$product_Quantity')");
      $message[]="Product added to cart";
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      * {
         font-family: 'Poppins', sans-serif;
         box-sizing: border-box;
      }
      body {
         margin: 0;
         padding: 0;
         background-color: #f5f5f5;
      }
      .header {
         text-align: center;
         font-size: 2em;
         font-weight: bold;
         color: #333;
         margin: 20px 0;
      }
      .box-container {
         display: flex;
         flex-wrap: wrap;
         gap: 40px;
         justify-content: center;
         padding: 20px;
      }
      .box {
         width: 300px;
         height: 480px; /* Adjusted height to fit content */
         border: 1px solid #ccc;
         border-radius: 10px;
         overflow: hidden;
         display: flex;
         flex-direction: column;
         align-items: center;
         padding: 20px; /* Increased padding for spacing */
         box-shadow: 0 4px 8px rgba(0,0,0,0.1);
         transition: transform 0.3s;
         background-color: #fff;
      }
      .box:hover {
         transform: translateY(-10px);
      }
      .box img {
         width: 100%;
         height: 250px;
         object-fit: cover;
         border-radius: 10px;
         margin-bottom: 20px; /* Added margin to move the image lower */
      }
      .box h3 {
         margin: 10px 0;
         font-size: 18px;
         text-align: center;
      }
      .box .price {
         font-size: 18px;
         color: #333;
         margin: 5px 0;
      }
      .btn-container {
         display: flex;
         flex-direction: column;
         width: 100%;
         margin-top: 10px;
         margin-bottom: 0; /* Removed bottom margin */
      }
      .btn {
         padding: 10px;
         border: none;
         background-color: #007bff;
         color: white;
         cursor: pointer;
         border-radius: 5px;
         text-align: center;
         transition: background-color 0.3s;
         margin-bottom: 10px;
      }
      .btn:last-child {
         margin-bottom: 0; /* Remove margin for the last button */
      }
      .btn:hover {
         background-color: #0056b3;
      }
      .wishlist-btn {
         background-color: #f8b400;
      }
      .wishlist-btn:hover {
         background-color: #c79200;
      }
      .action-links {
         text-align: right;
         margin: 20px;
      }
      .action-links a {
         display: inline-block;
         padding: 10px 20px;
         margin: 10px;
         text-decoration: none;
         border-radius: 5px;
         font-size: 16px;
         color: white;
      }
      .action-links .add-product {
         background-color: green;
      }
      .action-links .wishlist {
         background-color: #f8b400;
      }
      .action-links .cart {
         background-color: #007bff;
      }
   </style>
</head>
<body>
<div class="action-links">
   <a href="http://localhost/Ecommerce" class="add-product">Add product</a>
   <a href="http://localhost/Ecommerce/wishlist.php" class="wishlist">Wishlist</a>
   <a href="http://localhost/Ecommerce/cart.php" class="cart">Cart</a>
</div>
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message"><span>'.$message.
      '</span> <i class="fas fa-times" 
      onclick="this.parentElement.style.display = none;"></i> </div>';
   };
};
?>
<div class="container">
<section class="products">
<h1 class="heading" style="text-align: center; margin-top: 20px; margin-bottom: 20px;">LATEST PRODUCTS</h1>
   <div class="box-container">
      <?php
         $select_products= mysqli_query($conn,"SELECT * from products");
         if(mysqli_num_rows($select_products)>0){
            while($fetch_product=mysqli_fetch_assoc($select_products)){
      ?>
      <form action="" method="post">
         <div class="box">
            <img src="<?php echo UPLOAD_DIR,$fetch_product['image'];?>" alt="">
            <h3><?php echo $fetch_product['name']; ?></h3>
            <div class="price">Rs.<?php echo $fetch_product['price']; ?>/-</div>
            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
            <div class="btn-container">
               <input type="submit" class="btn" value="Add to cart" name="add_to_cart">
               <input type="submit" class="btn wishlist-btn" value="Add to wishlist" name="add_to_wishlist">
            </div>
         </div>
      </form>
      <?php
         };
      };
      ?>
   </div>
</section>
</div>
</body>
</html>
