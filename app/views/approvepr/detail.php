<section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2 id="title">
                            Approve Purchase Request <?= $data['prhead']['prnum']; ?>
                            </h2> 

                            <ul class="header-dropdown m-r--5">   
							<a href="<?= BASEURL; ?>/approvepr" class="btn bg-teal waves-effect">
                                <i class="material-icons">backspace</i> <span>BACK</span>
                            </a>
							</ul>
                        </div>
                        <div class="body">
                            <form>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="prtype">Request Type</label>
                                                <input type="text" class="form-control readOnly" value="<?= $data['cprtype']['prtype']; ?> - <?= $data['cprtype']['description']; ?>" readonly>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="warehouse">Warehouse</label>
                                                <select class="form-control show-tick" name="warehouse" id="warehouse" required>
                                                <option value="<?= $data['cwhs']['warehouseid']; ?>"><?= $data['cwhs']['warehouseid']; ?> - <?= $data['cwhs']['warehousename']; ?></option>
                                                    
                                                </select>
                                            </div>
                                        </div>    
                                    </div> 

                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="note">Note</label>
                                                <input type="text" name="note" id="note" class="form-control readOnly" placeholder="Note" value="<?= $data['prhead']['note']; ?>" readonly disabled>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="regdate">Request Date</label>
                                                <input type="date" name="reqdate" id="reqdate" class="datepicker form-control readOnly" value="<?= $data['prhead']['prdate']; ?>" disabled>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="department">Department</label>
                                                <select class="form-control show-tick" name="department" id="department" required readonly>
                                                    <option value="<?= $data['cdept']['id']; ?>"><?= $data['cdept']['department']; ?></option>
                                                    <?php foreach($data['department'] as $row) : ?>
                                                        <option value="<?= $row['id']; ?>"><?= $row['department']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="currency">Currency</label>
                                                <select class="form-control show-tick" name="currency" id="currency" required readonly>
                                                    <option value="<?= $data['ccurr']['currency'] ?>"><?= $data['ccurr']['currency'] ?> - <?= $data['ccurr']['description'] ?></option>
                                                    <?php foreach($data['currency'] as $row): ?>
                                                        <option value="<?= $row['currency']; ?>"><?= $row['currency']; ?> - <?= $row['description']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>   

                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="requestor">Requestor</label>
                                                <input type="text" class="form-control readOnly" name="requestor" id="requestor" value="<?= $data['prhead']['requestby']; ?>" readonly disabled>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="header">
                            <h2>
                                Purchase Request Item
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-responsive table-bordered table-striped" id="tbl-pr-item">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="checkAll" class="filled-in" />
                                                    <label for="checkAll"></label>
                                                </th>
                                                <th>PR Item</th>
                                                <th>Part Code</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Price</th>
                                                <th>Remark</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            <?php foreach ($data['prdata'] as $pr) : ?>
                                                <?php $no++; ?>
                                                <tr>
                                                    <td>
                                                        <?php if($pr['approvestat'] === $data['approvelevel']['level']) : ?>
                                                            <input class="filled-in checkbox" type="checkbox" id="<?= $pr['pritem']; ?>" name="ID[]">
                                                            <label for="<?= $pr['pritem']; ?>"></label>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $pr['pritem']; ?></td>
                                                    <td>
                                                        <!-- <?= $pr['material']; ?> -->
                                                        <a href="#" data-imagepath="<?= BASEURL; ?>/images/material-images/<?= $pr['image']; ?>" data-partcode="<?= $pr['material']; ?>" class="img-preview"><?= $pr['material']; ?></a>
                                                    </td>
                                                    <td><?= $pr['matdesc']; ?></td>
                                                    <td style="text-align:right;">
                                                        <?php if (strpos($pr['quantity'], '.000') !== false) {
                                                            echo number_format($pr['quantity'], 0);
                                                        }else{
                                                            echo number_format($pr['quantity'], 3);
                                                        } ?>  
                                                    </td>
                                                    <td><?= $pr['unit']; ?></td>
                                                    <td style="text-align:right;">
                                                        <?php echo number_format($pr['price'], 2); ?>  
                                                    </td>
                                                    <td><?= $pr['remark']; ?></td>
                                                    <td>Open</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <ul class="pull-right">                                           
                                        <button type="button" class="btn bg-red" id="btn-reject">
                                            <i class="material-icons">highlight_off</i> <span>REJECT</span>
                                        </button>
                                        <button type="button" class="btn bg-green" id="btn-approve">
                                            <i class="material-icons">done_all</i> <span>APPROVE</span>
                                        </button>
                                    </ul>
                                </div>
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

    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
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

            var sel_prnum = "<?= $data['prhead']['prnum']; ?>";
            $('#checkAll').click(function(){
                if(this.checked){
                    $('.checkbox').each(function(){
                        this.checked = true;
                    });   
                }else{
                    $('.checkbox').each(function(){
                        this.checked = false;
                    });
                } 
            });

            $('#btn-approve').on('click', function(){
                var tableControl= document.getElementById('tbl-pr-item');
                var _splchecked = [];
                $('input[name="ID[]"]:checkbox:checked', tableControl).each(function() {
                    _splchecked.push($(this).parent().next().text())
                }).get();
                if(_splchecked.length > 0){
                    console.log(_splchecked)
                    var prtemchecked = {
                        "pritem" : _splchecked
                    }
                    $.ajax({
                        url:base_url+'/approvepr/approvepritem/'+sel_prnum,
                        method:'post',
                        data:prtemchecked,
                        dataType:'JSON',
                        beforeSend:function(){
                            $('#btn-approve').attr('disabled','disabled');
                            showBasicMessage();
                        },
                        success:function(data)
                        {
                        	
                        },
                        error:function(err){
                            showErrorMessage(JSON.stringify(err))
                        }
                    }).done(function(data){
                        console.log(data);
                        if(data.msgtype === "1"){
                            showSuccessMessage('Selected PR Item Approved');
                        }else{
                            showErrorMessage(data.message);
                        }
                        $('#btn-approve').attr('disabled',false);
                    })   
                }else{
                    alert('No record selected ');
                }
            });

            $('#btn-reject').on('click', function(){
                var tableControl= document.getElementById('tbl-pr-item');
                var _splchecked = [];
                $('input[name="ID[]"]:checkbox:checked', tableControl).each(function() {
                    _splchecked.push($(this).parent().next().text())
                }).get();
                if(_splchecked.length > 0){
                    console.log(_splchecked)
                    var prtemchecked = {
                        "pritem" : _splchecked
                    }
                    $.ajax({
                        url:base_url+'/approvepr/rejectpritem/'+sel_prnum,
                        method:'post',
                        data:prtemchecked,
                        dataType:'JSON',
                        beforeSend:function(){
                            $('#btn-approve').attr('disabled','disabled');
                        },
                        success:function(data)
                        {
                        	
                        },
                        error:function(err){
                            showErrorMessage(JSON.stringify(err))
                        }
                    }).done(function(data){
                        console.log(data);
                        $('#btn-approve').attr('disabled',false);
                        showSuccessMessage('Selected PR Item Rejected');                        
                    })   
                }else{
                    alert('No record selected ');
                }
            });

            function showBasicMessage() {
                swal({title:"Loading...", text:"Please wait...", showConfirmButton: false});
            }

            function showSuccessMessage(message) {
                swal({title: "Success!", text: message, type: "success"},
                    function(){ 
                        window.location.href = base_url+'/approvepr';
                    }
                );
            }

            function showErrorMessage(message){
                swal("Error", message, "error");
            }
        });    
    </script>