<?php
include('includes/header.php');
require('includes/pdocon.php');
$raw_form_data=cleandata($_POST['productname']);
$clean_form_data=sanitizer($raw_form_data);
$clean_form_data='%'.$clean_form_data.'%';
$db = new Pdocon;
$db->query("SELECT * FROM inventory WHERE productName LIKE :name");
$db->bindvalue(':name',$clean_form_data,PDO::PARAM_STR); 
$db->execute();
$results = $db->fetchMultiple();
if($results){ 
    foreach ($results as $result){
   echo ' <table class="table table-bordered table-hover text-center">
    <thead >
      <tr>
        <th class="text-center">Name</th>
        <th class="text-center">Description </th>
        <th class="text-center">Quantity</th>
        <th class="text-center">Image</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>'.$result['productName'].'</td>
        <td>'.$result['productDescription'].'</td>
        <td>'.$result['quantity'].'</td>
        <td><img src="uploaded_image/'. $result['image'] .'"style="width:100px;height:100px"></td>
        <td><a href="https://'.$result['link'].'" class= btn btn-primary >Link</a></td>
        <td><a href="reports.php?report_id='.$result['id'].'" class=btn btn-primary>View Report</a></td>
        <td><a href="edit_product.php?product_id='.$result['id'].'" class=btn btn-danger>Update</a></td> 
      </tr>
    </tbody>
 </table>';
    }
}else{
echo "<p class='bg-success text-center' style='font-weight:bold;'>No results found.</p>";
}
?>


 