<?php
require('stripe-php-master/init.php');
$publishableKey="pk_test_51QHU0kJyvbGr5pGdQ3OnUzdYLbiYlYceY5njdyIuYCNUBHHi3B5AmL3VjKmxNOc9ZqwYH3ZhGXqp7NxQwGe4nmW600y0RJYaw4";
$secretKey="sk_test_51QHU0kJyvbGr5pGdmI09CR5puEHcuduEEZmfhWJucv61EORuKBmUyS0YAlHovCaFc76HTlYVfGK1uL1gKHCmS0fx00vU25KD6F";

\Stripe\Stripe::setApiKey($secretKey);
?>
