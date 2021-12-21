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
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="prlist"></table>
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Request Number</th>
                                        <th>Request Date</th>
                                        <th>Request By</th>
                                        <th>Department</th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    <?php foreach ($data['reqdata'] as $pr) : ?>
                                        <?php $no++; ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= $pr['requestnum']; ?></td>
                                            <td><?= $pr['request_date']; ?></td>
                                            <td><?= $pr['request_by']; ?></td>
                                            <td><?= $pr['department']; ?></td>
                                            <td><?= $pr['request_note']; ?></td>
                                            <td>
                                                <a href="<?= BASEURL; ?>/requestslip/requestdetail/<?= $pr['requestnum']; ?>" type="button" class="btn btn-success">Detail</a>
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
    