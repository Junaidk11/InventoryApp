<?php 
include('includes/header.php');
$id = $_GET['cus_id'];
require('includes/pdocon.php');
$db = new Pdocon; 
$db->query('SELECT * FROM inventory WHERE id=:id'); 
$db->bindvalue(':id',$id, PDO::PARAM_INT);
$row = $db->fetchSingle();
if($row){ 
        echo '  <div  class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr >
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr class="text-center">
                            <td>' . $row['productSupplier'] . '</td>
                            <td>' . $row['productEmail'] . '</td>
                            <td>' . $row['productCost'] . ' CAD</td>
                            
                          </tr>

                        </tbody>
                    </table>
                </div>';
}
?>