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
							<a href="<?= BASEURL; ?>/warehouse/create" class="btn btn-success waves-effect pull-right">Create Warehouse</a>
							</ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Warehouse</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach($data['whs'] as $out) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $out['warehouseid']; ?></td>
                                                <td><?= $out['warehousename']; ?></td>
                                                <td style="width:280px; text-align:center;">
                                                    <a href="<?= BASEURL; ?>/warehouse/edit/<?= $out['warehouseid']; ?>" type="button" class="btn btn-success">Edit</a>
                                                    <a href="<?= BASEURL; ?>/warehouse/delete/<?= $out['warehouseid']; ?>" type="button" class="btn btn-danger">Delete</a>
                                                    <button type="button" class="btn btn-primary btn-btn-assign-prtype" data-whscode="<?= $out['warehouseid']; ?>" data-whsname="<?= $out['warehousename']; ?>">
                                                        Assign Request Type
                                                    </button>
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

        <div class="modal fade" id="prtypeModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="prtypeModalTitle"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Warehouse ID</label>
                                    <input type="text" name="_whscode" id="_whscode" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="">Warehouse Name</label>
                                    <input type="text" name="_whsname" id="_whsname" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="">PR Type</label>
                                    <select name="prtype" id="prtype" class="form-control">
                                        <?php foreach($data['prtype'] as $row): ?>
                                            <option value="<?= $row['prtype']; ?>"><?= $row['description']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="btn-save-assignment">
                                        Add Assignment
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-responsive" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th colspan="3">Assignment</th>
                                            <!-- <th></th>
                                            <th></th> -->
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-prtype-assignment">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        $(function(){
            $('.btn-btn-assign-prtype').on('click', function(){
                var _data = $(this).data();
                loadwarehouse(_data.whscode)
                $('#_whscode').val(_data.whscode);
                $('#_whsname').val(_data.whsname);
                $('#prtypeModalTitle').html('')
                $('#prtypeModalTitle').append('Assing PR Type to '+ _data.whsname)
                $('#prtypeModal').modal('show');
            });


            function loadwarehouse(_whscode){
                $.ajax({
                    url: base_url+'/warehouse/getwarehouseprtypeassignment/'+_whscode,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    
                    $('#tbl-prtype-assignment').html('');
                    var count = 0;
                    for(var i = 0; i < data.length; i++){
                        count = count + 1;
                        $('#tbl-prtype-assignment').append(`
                            <tr>
                                <td>`+ data[i].prtype +`</td>
                                <td>`+ data[i].description +`</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" id="btn-del`+count+`" data-whscode="`+data[i].warehouseid+`" data-prtype="`+data[i].prtype+`">Delete Assignment</button>
                                </td>
                            </tr>
                        `);

                        $('#btn-del'+count).on('click', function(){
                            var _data = $(this).data();
                            deleteassignment(_data.whscode, _data.prtype);
                        })
                    }
                });
            }

            $('#btn-save-assignment').on('click', function(){
                saveassignment();
            });

            function saveassignment(){
                var _whscode = $('#_whscode').val();
                var _prtype  = $('#prtype').val();
                $.ajax({
                    url: base_url+'/warehouse/savewhsassignment/'+_whscode+'/'+_prtype,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    loadwarehouse(_whscode);
                });
            }

            function deleteassignment(_whscode, _prtype){
                $.ajax({
                    url: base_url+'/warehouse/deletewhsassignment/'+_whscode+'/'+_prtype,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    loadwarehouse(_whscode);
                });
            }
        });
    </script>