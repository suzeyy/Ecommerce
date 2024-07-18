<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-base64.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            margin-bottom: 10px;
            font-weight: 500;
            text-align: left;
            width: 100%;
        }
        input[type="text"],
        input[type="hidden"],
        input[type="submit"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: green;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: darkgreen;
        }
        .esewa-image {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    @include 'config.php';
    ?>
    <?php
    @include 'setting.php';
    $select_cart = mysqli_query($conn, "SELECT * from cart");
    $grand_total = 0;
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
            $grand_total += $sub_total;
        }
    }
    $tax_amt = $grand_total * (13 / 100);
    ?>
    <h1>Esewa Payment</h1>
    <div class="esewa-image">
        <img src="uploaded_img/esewa.png" height="100" alt="Esewa">
    </div>
    <form action="<?php echo $epay_url ?>" method="POST">
        <label for="amount">Amount:</label>
        <input type="hidden" id="amount" name="amount" value="<?php echo $grand_total ?>" required>
        <input type="hidden" id="tax_amount" name="tax_amount" value="<?php echo $tax_amt ?>" required>
        <input type="text" id="total_amount" name="total_amount" value="<?php echo $grand_total + $tax_amt + 0 + 0 ?>" required readonly>
        <input type="hidden" id="transaction_uuid" name="transaction_uuid" required>
        <input type="hidden" id="product_code" name="product_code" value="EPAYTEST" required>
        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0" required>
        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
        <input type="hidden" id="success_url" name="success_url" value="https://esewa.com.np" required>
        <input type="hidden" id="failure_url" name="failure_url" value="https://google.com" required>
        <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>
        <input type="hidden" id="signature" name="signature" required>
        <input value="Pay With Esewa" type="submit">
    </form>
</div>
<script>
    function generateSignature() {
        var currentTime = new Date();
        var formattedTime = currentTime.toISOString().slice(2, 10).replace(/-/g, '') + '-' + currentTime.getHours() + currentTime.getMinutes() + currentTime.getSeconds();
        document.getElementById("transaction_uuid").value = formattedTime;
        var total_amount = document.getElementById("total_amount").value;
        var transaction_uuid = document.getElementById("transaction_uuid").value;
        var product_code = document.getElementById("product_code").value;
        var secret = "8gBm/:&EnhH.1/q"; 
        var hash = CryptoJS.HmacSHA256(`total_amount=${total_amount},transaction_uuid=${transaction_uuid},product_code=${product_code}`, secret);
        var hashInBase64 = CryptoJS.enc.Base64.stringify(hash);
        document.getElementById("signature").value = hashInBase64;
    }
    generateSignature();
</script>
</body>
</html>
