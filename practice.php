

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website <?php ?></title>
</head>
<body>


   <?php

       $price = $_GET['price'];

       if (! empty($_GET['price'])) {

           $totalPrice = @(int) $_GET['price'] * 50;

           echo $totalPrice;
       }

       //    $user_details = [

       //        [
       //            'driver-licence' => 'Government issuesd Driver licences',
       //            'passport'       => 'Government issuesd Passport',
       //        ],
       //        'name' => "Adeola",

       //    ];

       //    foreach ($user_details as $users) {
       //        if (is_array($users)) {
       //            echo $users['passport'];
       //        } else {
       //            echo $users;
       //        }
       //    }
   ?>





</body>
</html>
