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
							<a href="<?= BASEURL; ?>/material/create" class="btn btn-success waves-effect pull-right">Create Material</a>
							</ul>
                        </div>
                        
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Part Code</th>
                                            <th>Brand</th>
                                            <th>Description / Model</th>
                                            <th>Supplier</th>
                                            <th>Base Unit</th>
                                            <th style="text-align:right;">Standard Price</th>
                                            <th style="width:100px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach($data['material'] as $barang) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td>
                                                    <a href="#" data-imagepath="<?= BASEURL; ?>/images/material-images/<?= $barang['image']; ?>" data-partcode="<?= $barang['material']; ?>" class="img-preview"><?= $barang['material']; ?></a>
                                                </td>
                                                <td><?= $barang['brand']; ?></td>
                                                <td><?= $barang['matdesc']; ?></td>
                                                <td><?= $barang['supplier']; ?></td>
                                                <td><?= $barang['matunit']; ?></td>
                                                <td style="text-align:right;"><?= number_format($barang['stdprice'],2); ?></td>
                                                <td>
                                                    <a href="<?= BASEURL; ?>/material/edit/data?material=<?= $barang['material']; ?>" type="button" class="btn btn-success">Edit</a>
                                                    <a href="<?= BASEURL; ?>/material/delete/data?material=<?= $barang['material']; ?>" type="button" class="btn btn-danger">Delete</a>
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

       
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="imagePreviewModalLabel">Part Image</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box" style="text-align:center;">
                            <img src="#" alt="image" id="materil-image" style="width:350px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="zoomin()">
                            <i class="material-icons">zoom_in</i>
                        </button>
                        
                        <button type="button" class="btn btn-primary" onclick="zoomout()"> 
                            <i class="material-icons">zoom_out</i>
                        </button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>            
        </div>
    </section>

    <script>
        function zoomin() {
            var GFG = document.getElementById("materil-image");
            var currWidth = GFG.clientWidth;
            if(currWidth <= 750){
                GFG.style.width = (currWidth + 100) + "px";
            }
        }
          
        function zoomout() {
            var GFG = document.getElementById("materil-image");
            var currWidth = GFG.clientWidth;
            GFG.style.width = (currWidth - 100) + "px";
        }

        $(function(){
            $('.img-preview').on('click', function(){
                var _data = $(this).data();
                var imgContent = document.getElementById("materil-image");
                imgContent.style.width = '350px';
                $('#imagePreviewModalLabel').html('Part '+ _data.partcode + ' Image');
                $('#materil-image').attr('src', '');
                $('#materil-image').attr('src', _data.imagepath);
                $('#imagePreviewModal').modal('show');
            });
        })
    </script>

    