<?php
    require_once '../../config/config.php';

    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME."";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    $stm = $pdo->query("SELECT * From t_pr02 WHERE prnum = '$_GET[prnum]' and pocreated is null");

    $prdata = $stm->fetchAll();

    // echo json_encode($prdata);
?>
<table class="table table-bordered table-hover" Width='800'>
<tr>
    <!-- <th> Order No. </th> -->
    <th> Item </th>
    <th> Kode Barang </th>
    <th> Nama Barang </th>
    <th> Quantity </th>
    <th> Unit </th>
    <th> Remark </th>
</tr>
<?php foreach ($prdata as $data) : ?>
<tr>
    <td>
        <?= $data['pritem']; ?>
    </td>
    <td>
        <?= $data['kodebrg']; ?>
    </td>
    <td>
        <?= $data['namabrg']; ?>
    </td>
    <td>
        <?= $data['jumlah']; ?>
    </td>
    <td>
        <?= $data['satuan']; ?>
    </td>
    <td>
        <?= $data['remark']; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>