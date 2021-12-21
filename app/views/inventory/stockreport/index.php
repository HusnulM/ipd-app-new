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
                                <a href="<?= BASEURL; ?>/exportdata/exportstocks" target="_blank" class="btn bg-teal">
                                   <i class="material-icons">cloud_download</i> EXPORT DATA
                                </a>

                                <a href="<?= BASEURL; ?>/" type="button" class="btn bg-teal">
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
                                                <th>Material</th>
                                                <th>Description</th>
                                                <th>Supplier</th>
                                                <th>Warehouse</th>
                                                <th style="text-align:right;">Quantity</th>
                                                <th>Unit</th>
                                                <th style="text-align:right;">Inventory Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            <?php foreach ($data['stock'] as $prdata) : ?>
                                                <?php $no++; ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $prdata['material']; ?></td>
                                                    <td><?= $prdata['matdesc']; ?></td>
                                                    <td><?= $prdata['supplier']; ?></td>
                                                    <td><?= $prdata['warehousename']; ?></td>
                                                    <td style="text-align:right;">
                                                        <?php 
                                                            if (strpos($prdata['quantity'], '.000') !== false) {
                                                                echo number_format($prdata['quantity'], 0);
                                                            }else{
                                                                echo number_format($prdata['quantity'], 3);
                                                            } 
                                                        ?>
                                                    </td>
                                                    <td><?= $prdata['matunit']; ?></td>
                                                    <td style="text-align:right;"><?= number_format($prdata['inventory_value'], 2); ?></td>
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