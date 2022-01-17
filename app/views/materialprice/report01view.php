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

                            <a href="<?= BASEURL; ?>/materialprice" type="button" class="btn bg-teal">
                                <i class="material-icons">backspace</i> <span>BACK</span>
                            </a>
                        </ul>
                    </div>
                    <div class="body">                                
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable" style="width:100%;font-size:13px;">
                                    <thead>
                                        <tr>
                                            <th colspan="4" style='text-align:center;'>Year <?= $data['year']; ?></th>
                                            <th colspan="12" style='text-align:center;'>Cost</th>
                                        </tr>
                                        <tr>
                                            <th>No</th>
                                            <th>Supplier</th>
                                            <th>Material</th>
                                            <th>Description</th>
                                            <th>Jan</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Apr</th>
                                            <th>May</th>
                                            <th>Jun</th>
                                            <th>Jul</th>
                                            <th>Aug</th>
                                            <th>Sep</th>
                                            <th>Oct</th>
                                            <th>Nov</th>
                                            <th>Dec</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach ($data['rdata'] as $row) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <!-- <td><?= $row['month']; ?></td> -->
                                                <!-- <td><?= $row['year']; ?></td> -->
                                                <td><?= $row['supplier_name']; ?></td>
                                                <td><?= $row['material']; ?></td>
                                                <td><?= $row['matdesc']; ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Jan'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Feb'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Mar'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Apr'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['May'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Jun'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Jul'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Aug'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Sep'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Oct'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Nov'],2); ?></td>
                                                <td style="text-align:right;"><?= number_format($row['Dec'],2); ?></td>
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
    