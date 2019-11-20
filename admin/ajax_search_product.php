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
   echo ' <div class="alert alert-success alert-dismissible fade in">
   <a href="#" class="close" data-dimiss="alert" aria-label="close"> &times;</a>
   <div class="table-responsive">
   <table class="table table-bordered table-hover text-center">
    <thead >
      <tr>
        <th class="text-center" style="color:black;">Name</th>
        <th class="text-center" style="color:black;">Description </th>
        <th class="text-center" style="color:black;">Quantity</th>
        <th class="text-center" style="color:black;">Image</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="color:#262626;">'.$result['productName'].'</td>
        <td style="color:#262626;">'.$result['productDescription'].'</td>
        <td style="color:#262626;">'.$result['quantity'].'</td>
        <td><img src="uploaded_image/'. $result['image'] .'"style="width:100px;height:100px" class="img-thumbnail img-responsive"></td>
        <td><a href="https://'.$result['link'].'" class= "btn btn-primary" >Link</a></td>
        <td><a href="reports.php?report_id='.$result['id'].'" class="btn btn-primary">View Report</a></td>
        <td><a href="edit_product.php?product_id='.$result['id'].'" class="btn btn-danger">Update</a></td> 
      </tr>
    </tbody>
 </table>
 </div>
 </div>';
    }
}else{
echo "<div class='alert alert-danger alert-dismissible fade in'>
      <a href='#' class='close' data-dimiss='alert' aria-label='close'> &times;</a>
      <strong> No results found. </strong> 
      </div>'";
}
?>


 