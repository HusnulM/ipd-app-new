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
							    <a href="<?= BASEURL; ?>/approval/create" class="btn btn-success waves-effect pull-right">Create Approval Assignment</a>
							</ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">No</th>
                                            <th>Object</th>
                                            <th>Type</th>
                                            <th>Sequence</th>
                                            <th>Creator</th>
                                            <th>Approver</th>
                                            <th style="width:120px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach($data['usrapp'] as $out) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $out['object']; ?></td>
                                                <td><?= $out['doctype']; ?></td>
                                                <td><?= $out['level']; ?></td>
                                                <td><?= $out['creator']; ?></td>
                                                <td><?= $out['approval']; ?></td>
                                                <td>
                                                    <a href="<?= BASEURL; ?>/approval/delete/<?= $out['object']; ?>/<?= $out['doctype']; ?>/<?= $out['creator']; ?>/<?= $out['approval']; ?>" class="btn btn-danger btn-sm">Delete</a>
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