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
                                <a href="<?= BASEURL; ?>/exportdata/exportbudgetissued/<?= $data['dept']; ?>/<?= $data['month']; ?>/<?= $data['year']; ?>" target="_blank" class="btn bg-teal">
                                   <i class="material-icons">cloud_download</i> EXPORT DATA
                                </a>

                                <a href="<?= BASEURL; ?>/reports/budgetissuing" type="button" class="btn bg-teal">
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
                                                <th>Issuing Date</th>
                                                <th>Department</th>
                                                <th>Reference</th>
                                                <th>Allocation</th>
                                                <th style="text-align:right;">Issuing Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            <?php foreach ($data['rdata'] as $row) : ?>
                                                <?php $no++; ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $row['createdon']; ?></td>
                                                    <td><?= $row['deptname']; ?></td>
                                                    <td><?= $row['refnum']; ?></td>
                                                    <td><?= $row['description']; ?></td>
                                                    <td style="text-align:right;"><?= number_format($row['amount'],2); ?></td>
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