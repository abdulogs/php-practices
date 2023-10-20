
<?php 
$SetDiscount = 69;  // This is the value you want to set for discount.
$DiscountOn = 69;   // This is the value you have to check if proceed with cart total
$CartTotal = 100;   // This is the cart total value
$Total = $cartTotal;


// If cart total increased with the value of set discount value then cart total value will be set as discount value.
 
if($CartTotal >=  $DiscountOn){
    $Total = $SetDiscount;
} else{
    $Total;
}

echo $total;


?>