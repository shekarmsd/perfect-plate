<?php
session_start();

try {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $query = new MongoDB\Driver\Query([]);
    $rows = $mng->executeQuery("PerfectPlate.users", $query);
    $chefs = $mng->executeQuery("PerfectPlate.chef", $query);
    $orders = $mng->executeQuery("PerfectPlate.Customer_Orders", $query);
    $catg = $mng->executeQuery("PerfectPlate.category", $query);

    $ans = count($rows->toArray());
    if (!empty($ans)) {
        $count = $ans;
    } else {
        $err_msg = "0";
    }

    $ans1 = count($chefs->toArray());
    if (!empty($ans1)) {
        $chef = $ans1;
    } else {
        $err_msg1 = "0";
    }

    $ans2 = count($orders->toArray());
    if (!empty($ans2)) {
        $order = $ans2;
        $_SESSION['orders'] = $ans2;
    } else {
        $err_msg2 = "0";
    }

    $ans3 = count($catg->toArray());
    if (!empty($ans3)) {
        $catgry = $ans3;
    } else {
        $err_msg3 = "0";
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}

try {

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [
        'Status' => "Order Placed"
    ];
    $filter1 = [
        'Status' => "Processing"
    ];
    $filter2 = [
        'Status' => "Delivered"
    ];
    $order0 = new MongoDB\Driver\Query($filter);
    $order1 = new MongoDB\Driver\Query($filter1);
    $order2 = new MongoDB\Driver\Query($filter2);
    $rows = $mng->executeQuery("PerfectPlate.Customer_Orders", $order0);
    $rows1 = $mng->executeQuery("PerfectPlate.Customer_Orders", $order1);
    $rows2 = $mng->executeQuery("PerfectPlate.Customer_Orders", $order2);
    $placed = count($rows->toArray());
    $process = count($rows1->toArray());
    $delivered = count($rows2->toArray());
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Exception:", $e->getMessage();
}


include 'assets/mpdf/vendor/autoload.php';


//  //create new instance
$mpdf = new \Mpdf\Mpdf();
//  $orderid=$_SESSION['OrderId'] ;
//  $productids = $_SESSION['ProductIds'];
//  $fname=$_SESSION['FirstName'];
//  $lname=$_SESSION['LasttName']; 
//  $country=$_SESSION['Country'];
//  $state=$_SESSION['State'];
//  $city=$_SESSION['City'];
//  $street=$_SESSION['Street']; 
//  $pincode=$_SESSION['Pincode'];	
//  $phone=$_SESSION['Phone'];
//  //create pdf
$data = '';
$data .= '
 <style>

@page toc { sheet-size: A4; }

 .divide {
    font-family: "Source Sans Pro", "Arial", sans-serif;
}

table {
    width:100%;
    font-family: "Source Sans Pro", "Arial", sans-serif;
  }
  th, td {
    padding: 15px;
    text-align: center;
  }
  #t01 tr:nth-child(even) {
    background-color: #eee;
  }
  #t01 tr:nth-child(odd) {
   background-color: #fff;
  }
  #t01 th {
    background-color: black;
    color: white;
  }

@media only screen and (max-width: 600px) {
    .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: center;
    }
    
    .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: center;
    }
}

</style>';

$data .= ' 

    <div class="divide" style="text-align: center;">
        <h4 style="font-size: 3rem;">Perfect<span style="color: green;"> Plate</span></a></h4>
    </div><br/>
    <div class="divide">
        <h4>Contact: <a><span style="color: gray;">'.$_SESSION['admin'].'</span></a></h4>
        
    </div><br/>
    <div class="divide" style="text-align: center; color: gray;">
        <h3>Overall Details</h3>
    </div><br/>
    <div class="divide" style="padding-bottom: 30px; border-bottom: 1px solid gray;">
        <table style="text-align: center;">
            <thead>
                <tr>
                    <th>Total Customer</th>
                    <th>Total Orders</th>
                    <th>Total Chef</th>
                    <th>Total Recipes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><h1 style="color: red; font-size: 3rem;">' . $count . '</h1></td>
                    <td><h1 style="color: red; font-size: 3rem;">' . $order . '</h1></td>
                    <td><h1 style="color: red; font-size: 3rem;">' . $chef . '</h1></td>
                    <td><h1 style="color: red; font-size: 3rem;">' . $catgry . '</h1></td>
                </tr>
            </tbody>
        </table>
    </div><br/>
    <div class="divide" style="text-align: center; color: gray;">
        <h3>Customer Order Summary</h3>
    </div><br/>
    <div class="divide" style="border-bottom: 1px solid gray;">
    <table>
        <thead>
            <tr>
                <th>Orders Delivered</th>
                <th>Processing Orders</th>
                <th>Placed Orders</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><h1 style="color: green; font-size: 3rem;">' . $delivered . '</h1></td>
                <td><h1 style="color: green; font-size: 3rem;">' . $process . '</h1></td>
                <td><h1 style="color: green; font-size: 3rem;">' . $placed . '</h1></td>
            </tr>
        </tbody>
    </table>
</div><br/>
<div class="divide" style="text-align: center; color: gray;">
        <h5>&copy; Pertfect Plate 2020.</h5>
</div><br/>


    
 
 ';


$mpdf->WriteHTML($data);


// //output

$mpdf->Output('myfile.pdf','D');
header('Location:admin.php');
