    <section class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form action="<?= BASEURL; ?>/prtype/update" method="POST">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?>
                        </h2>

                        <ul class="header-dropdown m-r--5">
                            <button type="submit" id="btn-save" class="btn btn-primary"  data-type="success">SAVE</button>

                            <a href="<?= BASEURL; ?>/prtype" type="button" id="btn-back" class="btn btn-danger"  data-type="success">CANCEL</a>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="prtype">Purchase Request Type</label>
                                            <input type="text" name="prtype" id="prtype" class="form-class" required="true" value="<?= $data['whs']['prtype']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="description">Description</label>
                                            <input type="text" name="description" id="description" class="form-control" placeholder="Warehouse Name" value="<?= $data['whs']['description']; ?>">
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