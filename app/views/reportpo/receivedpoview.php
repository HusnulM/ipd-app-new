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
                            <a href="<?= BASEURL; ?>/materialprice/exportdata/data?year=<?= $data['year']; ?>&material=<?= $data['material']; ?>" target="_blank" class="btn bg-teal">
                               <i class="material-icons">cloud_download</i> EXPORT DATA
                            </a>

                            <a href="<?= BASEURL; ?>/reportpo/receivedpo" type="button" class="btn bg-teal">
                                <i class="material-icons">backspace</i> <span>BACK</span>
                            </a>
                        </ul>
                    </div>
                    <div class="body">                                
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable" style="width:100%;font-size:13px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>PO Number</th>
                                            <th>PO Item</th>
                                            <th>Supplier</th>
                                            <th>PO Date</th>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Received Qty</th>
                                            <th>Unit</th>
                                            <th>Received Date</th>
                                            <th>Unit Price</th>
                                            <th>Total Amount</th>
                                            <th>Receipt Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach ($data['rdata'] as $row) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $row['ponum']; ?></td>
                                                <td><?= $row['poitem']; ?></td>
                                                <td><?= $row['supplier_name']; ?></td>
                                                <td><?= $row['podat']; ?></td>
                                                <td><?= $row['material']; ?></td>
                                                <td><?= $row['matdesc']; ?></td>
                                                <td style="text-align:right;"><?= number_format($row['quantity'],3); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['grqty'],2); ?></td>
                                                <td><?= $row['unit']; ?></td>
                                                <td><?= $row['movement_date']; ?></td>
                                                <td style="text-align:right;"><?= number_format($row['price'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['price']*$row['grqty'],2); ?></td>
                                                <td><?= $row['movement_number']; ?></td>
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
    </div>
</section>
    