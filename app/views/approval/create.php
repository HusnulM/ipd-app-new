    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
                        </div>
                        <div class="body">
                            <form action="<?= BASEURL; ?>/approval/save" method="POST">
                                <div class="row clearfix">                                    
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="telp">Object</label>
                                            <select name="object" class="form-control" id="object">
                                                <option value="">Select Object</option>
                                                <option value="PR">PR - Purchase Requisition</option>
                                                <option value="PO">PO - Purchase Order</option>
                                                <option value="RS">RS - Request Slip</option>
                                                <!-- <option value="PO">PO - Purchase Order</option> -->
                                                <!-- <option value="IV">IV - Payment</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-line">
                                            <label for="doctype">Document Type</label>
                                            <input type="text" class="form-control" name="doctype">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-line">
                                            <label for="telp">Sequence</label>
                                            <input type="text" class="form-control" name="level">
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">                                    
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label for="telp">User Creator</label>
                                            <select name="creator" class="form-control" id="creator">
                                                <option value="">Select User Creator</option>
                                                <?php foreach($data['userc'] as $out) : ?>
                                                    <option value="<?= $out['username']; ?>"><?= $out['username']; ?> - <?= $out['nama']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label for="approval">User Approval</label>
                                            <select name="approval" class="form-control" id="approval">
                                            <option value="">Select User Approval</option>
                                                <?php foreach($data['usera'] as $out) : ?>
                                                    <option value="<?= $out['username']; ?>"><?= $out['username']; ?> - <?= $out['nama']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">  
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
										<div class="form-group">
											<button type="submit" id="btn-save" class="btn btn-primary waves-effect pull-left">SAVE</button>
                                            <a href="<?= BASEURL; ?>/approval" class="btn btn-default" style="margin-left:5px;">
                                                BACK
                                            </a>
										</div>
									</div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>