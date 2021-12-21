    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="msg-alert" class="msg-alert">
                        <?php
                            Flasher::msgInfo();
                        ?>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
							
                            <ul class="header-dropdown m-r--5">                                
							    <a href="<?= BASEURL; ?>/budgeting/create" class="btn btn-success waves-effect pull-right">ADD Budget</a>
							</ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Department</th>
                                            <th>Budget Period (Year)</th>
                                            <th style="text-align:right;">Budget Allocated</th>
                                            <th style="text-align:right;">Issuing Budget</th>
                                            <th style="text-align:right;">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach($data['budget'] as $row) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $row['deptname']; ?></td>
                                                <td><?= $row['budget_period']; ?></td>
                                                <td style="text-align:right;">
                                                    <?= number_format($row['amount']+$row['issuing_amount'], 2, '.', ','); ?>
                                                </td>
                                                <td style="text-align:right;">
                                                    <?= number_format($row['issuing_amount'], 2, '.', ','); ?>
                                                </td>

                                                <td style="text-align:right;">
                                                    <?= number_format(($row['amount']+$row['issuing_amount'])-$row['issuing_amount'], 2, '.', ','); ?>
                                                </td>
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