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
                                Approve Purchase Request
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="prlist"></table>
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Purchase Request</th>
                                            <th>Request Type</th>
                                            <th>PR Date</th>
                                            <th>Note</th>
                                            <th>Request By</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach ($data['prdata'] as $pr) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $pr['prnum']; ?></td>
                                                <td><?= $pr['prtypename']; ?></td>
                                                <td><?= $pr['prdate']; ?></td>
                                                <td><?= $pr['note']; ?></td>
                                                <td><?= $pr['requestby']; ?></td>
                                                <td>Open</td>
                                                <td>
                                                    <a href="<?= BASEURL; ?>/approvepr/detail/<?= $pr['prnum']; ?>" type="button" class="btn btn-success">Detail</a>
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