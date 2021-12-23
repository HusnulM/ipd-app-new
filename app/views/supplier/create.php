    <section class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                        <?= $data['menu']; ?>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <form action="<?= BASEURL; ?>/supplier/save" method="POST">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="suppliername">Supplier Name</label>
                                            <input type="text" name="suppliername" id="suppliername" class="form-control" placeholder="Supplier Name" required="true">
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" id="address" class="form-control" placeholder="Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="telp">Telephone</label>
                                            <input type="text" name="telp" id="telp" class="form-control" placeholder="Telephone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" style="padding:10dp;">
                                        <button type="submit" id="btn-save" class="btn btn-primary"  data-type="success">SAVE</button>

                                        <a href="<?= BASEURL; ?>/supplier" type="button" id="btn-back" class="btn btn-danger"  data-type="success">CANCEL</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>