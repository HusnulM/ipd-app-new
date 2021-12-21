<?php
    require_once '../../config/config.php';

    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME."";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    $stm = $pdo->query("SELECT * From t_pr02 WHERE prnum = '$_GET[prnum]'");

    $prdata = $stm->fetchAll();

    // echo json_encode($prdata);
?>

<div class="table-responsive">
<table class="table table-bordered table-hover" Width='100%'>
<tr>
    <!-- <th> Order No. </th> -->
    <th> Item </th>
    <th> Material </th>
    <th> Description </th>
    <th style="text-align:right;"> Quantity </th>
    <th> Unit </th>
    <th> Price Unit </th>
    <th> Total Price </th>
    <th> Remark </th>
    <th> Approve/Reject Date</th>
    <th> Status</th>
</tr>
<?php foreach ($prdata as $data) : ?>
<tr>
    <td>
        <?= $data['pritem']; ?>
    </td>
    <td>
        <?= $data['material']; ?>
    </td>
    <td>
        <?= $data['matdesc']; ?>
    </td>
    <td style="text-align:right;">
        <?php if (strpos($data['quantity'], '.000') !== false) {
            echo number_format($data['quantity'], 0);
        }else{
            echo number_format($data['quantity'], 3);
        } ?>        
    </td>
    <td>
        <?= $data['unit']; ?>
    </td>
    <td style="text-align:right;">
        <?php echo number_format($data['price'], 2); ?>        
    </td>
    <td style="text-align:right;">
        <?php echo number_format($data['quantity']*$data['price'], 2); ?>        
    </td>
    <td>
        <?= $data['remark']; ?>
    </td>
    <td>
        <?= $data['approvedate']; ?>
    </td>
    
    <?php if($data['approvestat'] === '1') : ?>
        <td style="background-color:yellow;color:black;font-weight: bold;">
            Open
        </td>
    <?php elseif($data['approvestat'] === 'R') : ?>
        <td style="background-color:red;color:white;font-weight: bold;">
            Rejected by <?= $data['approveby']; ?>
        </td>             
    <?php else : ?>
        <td style="background-color:green;color:white;font-weight: bold;">
            Approved by <?= $data['approveby']; ?>
        </td> 
    <?php endif; ?>

    
</tr>
<?php endforeach; ?>
</table>
</div>