    <section class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form action="<?= BASEURL; ?>/warehouse/update" method="POST">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?>
                        </h2>

                        <ul class="header-dropdown m-r--5">
                            <button type="submit" id="btn-save" class="btn btn-primary"  data-type="success">SAVE</button>

                            <a href="<?= BASEURL; ?>/warehouse" type="button" id="btn-back" class="btn btn-danger"  data-type="success">CANCEL</a>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="warehouseid">Warehouse Code</label>
                                            <input type="text" name="warehouseid" id="warehouseid" class="form-control" placeholder="Warehouse Code" required="true" value="<?= $data['whs']['warehouseid']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="warehousename">Warehouse Name</label>
                                            <input type="text" name="warehousename" id="warehousename" class="form-control" placeholder="Warehouse Name" value="<?= $data['whs']['warehousename']; ?>">
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </section>