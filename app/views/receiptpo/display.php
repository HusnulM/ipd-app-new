<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" id="div-po-item">
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?>
                        </h2>
                        <ul class="header-dropdown m-r--5">                                
                            <!-- <a href="<?= BASEURL; ?>/exportdata/exportbudgetissued/<?= $data['dept']; ?>/<?= $data['month']; ?>/<?= $data['year']; ?>" target="_blank" class="btn bg-teal">
                               <i class="material-icons">cloud_download</i> EXPORT DATA
                            </a> -->

                            <a href="<?= BASEURL; ?>/reportgrpo" type="button" class="btn bg-teal">
                                <i class="material-icons">backspace</i> <span>BACK</span>
                            </a>
                        </ul>
                    </div>
                    <div class="body"> 
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-standard-table" style="width:150%;font-size:13px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Receipt Number</th>
                                        <th>Receipt Date</th>
                                        <th>Receipt Note</th>
                                        <th>Supplier</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>PO Number</th>
                                        <th>PO Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    <?php foreach ($data['grdata'] as $row) : ?>
                                        <?php $no++; ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= $row['movement_number']; ?></td>
                                            <td><?= $row['movement_date']; ?></td>
                                            <td><?= $row['movement_note']; ?></td>
                                            <td><?= $row['supplier_name']; ?></td>
                                            <td><?= $row['material']; ?></td>
                                            <td><?= $row['matdesc']; ?></td>
                                            <td style="text-align:right;"><?= number_format($row['quantity'],3); ?></td>
                                            <td><?= $row['unit']; ?></td>
                                            <td style="text-align:right;"><?= number_format($row['unit_price'],2); ?></td>
                                            <td style="text-align:right;"><?= number_format($row['totalprice'],2); ?></td>
                                            <td><?= $row['ponum']; ?></td>
                                            <td><?= $row['poitem']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    