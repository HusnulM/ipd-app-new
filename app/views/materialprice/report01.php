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
                        <form>
                            <div class="row clearfix">
                                <!-- <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="pyear">Month</label>
                                            <select name="pmonth" id="pmonth" class="form-control">
                                                <option value="01">January</option>
                                                <option value="02">February</option>
                                                <option value="03">March</option>
                                                <option value="04">April</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">August</option>
                                                <option value="09">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>
                                    </div>    
                                </div> -->

                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="pyear">Year</label>
                                            <input type="text" name="pyear" id="pyear" class="datepicker form-control" value="<?php echo date('Y'); ?>">
                                        </div>
                                    </div>    
                                </div>                                    
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="material">Material</label>
                                            <input type="text" name="pmaterial" id="pmaterial" class="form-control">
                                        </div>
                                    </div>    
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="material">-</label>
                                            <button type="button" class="btn btn-primary form-control" id="btn-sel-material">Select Material</button>
                                        </div>
                                    </div>    
                                </div>                                    
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <button type="button" id="btn-process" class="btn btn-primary"  data-type="success">Show Data</button>
                                    </div>    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="barangModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="barangModal">Select Material</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-responsive" id="list-barang" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">TUTUP</button>
                </div>
            </div>
        </div>
    </div>
        
</section>
    
    
    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        $(function(){
            loaddatabarang();

            function loaddatabarang(){
                $('#list-barang').dataTable({
                    "ajax": base_url+'/material/getAllmaterial',
                    "columns": [
                        { "data": "material" },
                        { "data": "matdesc" },
                        {"defaultContent": "<button class='btn btn-primary btn-xs'>SELECT</button>"}
                    ],
                    "bDestroy": true,
                    "paging":   true,
                    "searching":   true
                });

                $('#list-barang tbody').on( 'click', 'button', function () {
                    var table = $('#list-barang').DataTable();
                    selected_data = [];
                    selected_data = table.row($(this).closest('tr')).data();
                    
                    $('#pmaterial').val(selected_data.material);
                    $('#barangModal').modal('hide');
                } );
            }

            $('#btn-sel-material').on('click', function(){
                
                $('#barangModal').modal('show');
            });

            $('#btn-process').on('click', function(){
                var _material = '';
                if($('#pmaterial').val() === ''){
                    _material = 'ALL'
                }else{
                    _material = $('#pmaterial').val();
                }
                window.location.href = base_url+'/materialprice/display/data?year='+$('#pyear').val()+'&material='+_material;
            });
        })
    </script>