<?php
include('includes/header.php');
require('includes/pdocon.php');
$raw_form_data=cleandata($_POST['productname']);
$clean_form_data=sanitizer($raw_form_data);
$db = new Pdocon;
$db->query("SELECT * FROM inventory WHERE productName=:name");
$db->bindvalue(':name',$clean_form_data,PDO::PARAM_STR); 
$db->execute();
$row = $db->fetchSingle();
if($row){ 
   echo ' <table class="table table-bordered table-hover text-center">
    <thead >
      <tr>
        <th class="text-center">Item ID</th>
        <th class="text-center">Name</th>
        <th class="text-center">Description </th>
        <th class="text-center">Supplier</th>
        <th class="text-center">Email</th>
        <th class="text-center">Cost</th>
        <th class="text-center">Quantity</th>
        <th class="text-center">Image</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>'.$row['id'].'</td>
        <td>'.$row['productName'].'</td>
        <td>'.$row['productDescription'].'</td>
        <td>'.$row['productSupplier'].'</td>
        <td>'.$row['productEmail'].'</td>
        <td>'.$row['productCost'].'</td>
        <td>'.$row['quantity'].'</td>
        <td><img src="uploaded_image/'. $row['image'] .'"style="width:100px;height:100px"></td>
        <td><a href="reports.php?report_id='.$row['id'].'class="btn btn-primary">View</a></td>
        <td><a href="edit_product.php?product_id='.$row['id'].'" class="btn btn-danger">Edit</a></td> 
      </tr>
    </tbody>
 </table>';
}else{
echo "<p class='bg-success text-center' style='font-weight:bold;'>No results found.</p>";
}
?>