    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
            <form id="form-pr-data" enctype="multipart/form-data">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2 id="title">
                            Display Purchase Request <?= $data['prhead']['prnum']; ?>
                            </h2> 

                            <ul class="header-dropdown m-r--5">        
                            <button type="button" id="btn-change" class="btn btn-success waves-effect">Change</button>                        
							<a href="<?= BASEURL; ?>/pr" class="btn btn-danger waves-effect">Cancel</a>
							</ul>
                        </div>
                        <div class="body">
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="prtype">Request Type</label>
                                                <select class="form-control show-tick" name="prtype" id="prtype" required>
                                                    <option value="<?= $data['cprtype']['prtype']; ?>"><?= $data['cprtype']['prtype']; ?> - <?= $data['cprtype']['description']; ?></option>
                                                    <?php foreach($data['prtype'] as $row): ?>
                                                        <?php if($data['cprtype']['prtype'] !== $row['prtype']): ?>
                                                        <option value="<?= $row['prtype']; ?>"><?= $row['prtype']; ?> - <?= $row['description']; ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <div id="div_whs">

                                        </div>
                                        <!-- <div class="form-group">
                                            <div class="form-line">
                                                <label for="warehouse">Warehouse</label>
                                                <select class="form-control show-tick" name="warehouse" id="warehouse" required>
                                                
                                                <option value="<?= $data['cwhs']['warehouseid']; ?>"><?= $data['cwhs']['warehouseid']; ?> - <?= $data['cwhs']['warehousename']; ?></option>
                                                    <?php foreach($data['whs'] as $row): ?>
                                                        <option value="<?= $row['warehouseid']; ?>"><?= $row['warehouseid']; ?> - <?= $row['warehousename']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>     -->
                                    </div> 

                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="note">Note</label>
                                                <input type="text" name="note" id="note" class="form-control readOnly" placeholder="Note" value="<?= $data['prhead']['note']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="regdate">Request Date</label>
                                                <input type="date" name="reqdate" id="reqdate" class="datepicker form-control readOnly" value="<?= $data['prhead']['prdate']; ?>">
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="department">Department</label>
                                                <select class="form-control show-tick" name="department" id="department" required>
                                                    <option value="<?= $data['cdept']['id']; ?>"><?= $data['cdept']['department']; ?></option>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="currency">Currency</label>
                                                <select class="form-control show-tick" name="currency" id="currency" required>
                                                    <option value="<?= $data['ccurr']['currency'] ?>"><?= $data['ccurr']['currency'] ?> - <?= $data['ccurr']['description'] ?></option>
                                                    <?php foreach($data['currency'] as $row): ?>
                                                        <option value="<?= $row['currency']; ?>"><?= $row['currency']; ?> - <?= $row['description']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>    

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="requestor">Requestor</label>
                                                <input type="text" class="form-control readOnly" name="requestor" id="requestor" value="<?= $data['prhead']['requestby']; ?>">
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="header">
                            <h2>
                                Purchase Request Item
                            </h2>
                                    
                            <ul class="header-dropdown m-r--5">                                
                                
                            </ul>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="table-responsive">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Part Number</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Price</th>
                                                    <th>Remark</th>
                                                    <th class="hideComponent">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl-pr-body" class="mainbodynpo">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row hideComponent">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="pull-right">  
                                        <button type="button" id="btn-dlg-add-item" class="btn bg-blue hideComponent">
                                            <i class="material-icons">playlist_add</i> <span>ADD ITEM</span>
                                        </button>

                                        <button type="submit" class="btn bg-blue hideComponent">
                                            <i class="material-icons">save</i> <span>SAVE</span>
                                        </button>  
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="barangModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="barangModal">Add Part</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive" id="list-barang" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Part Code</th>
                                        <th>Description</th>
                                        <th>Inventory Stock</th>
                                        <th>Unit</th>
                                        <th>Price</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="errorModalText">Info</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive" id="tbl-err-msg" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl-err-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
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
            let detail_order_beli = [];
            var kodebrg           = '';
            var namabrg           = '';
            var action            = '';
            var imgupload         = [];
            var count = 0;

            var _scwhs = "<?= $data['cwhs']['warehouseid']; ?>";
            var _swhsnm = "<?= $data['cwhs']['warehousename']; ?>";           

            var sel_prnum = "<?= $data['prhead']['prnum']; ?>";

            function materialExists(material) {
                return detail_order_beli.some(function(el) {
                    return el.material === material;
                }); 
            }

            $('#prtype').on('change', function(){
                var _prtype = this.value;
                loadwarehouse(_prtype);
            });

            loaddefaultwarehouse($('#prtype').val());
            function loaddefaultwarehouse(_prtype){
                $.ajax({
                    url: base_url+'/warehouse/getwarehousebyprtype/'+_prtype,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    console.log(data)
                    $("#div_whs").html('');
                    var listItems = '';
                    listItems += "<label>Warehouse</label>";
                    listItems += "<select name='warehouse' id='warehouse' class='form-control' >";
                    listItems += "<option class='form-control' value='"+ _scwhs +"'>"+ _scwhs +" - "+ _swhsnm +"</option>";
                    for (var x = 0; x < data.length; x++) {      
                        if(_scwhs !== data[x].warehouseid){
                            listItems += "<option class='form-control' value='"+ data[x].warehouseid +"'>"+ data[x].warehouseid +" - "+ data[x].warehousename +"</option>";
                        }                  
                    };
                    listItems += "</select>";
                    $("#div_whs").html(listItems);
                });
            }

            function loadwarehouse(_prtype){
                $.ajax({
                    url: base_url+'/warehouse/getwarehousebyprtype/'+_prtype,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    console.log(data)
                    $("#div_whs").html('');
                    var listItems = '';
                    listItems += "<label>Warehouse</label>";
                    listItems += "<select name='warehouse' id='warehouse' class='form-control' >";
                    for (var x = 0; x < data.length; x++) {      
                        listItems += "<option class='form-control' value='"+ data[x].warehouseid +"'>"+ data[x].warehouseid +" - "+ data[x].warehousename +"</option>";                  
                    };
                    listItems += "</select>";
                    $("#div_whs").html(listItems);
                });
            }

            function loaddatabarang(){
                var _whscode = $('#warehouse').val();
                
                $('#list-barang').dataTable({
                    "ajax": base_url+'/pr/getmateriallist/'+_whscode,
                    "columns": [
                        { "data": "material" },
                        { "data": "matdesc" },
                        { "data": "quantity", "className" : "text-right", 
                            render: function(data, type, row){                            
                                return formatNumber(data);
                            }
                        },
                        { "data": "matunit" },
                        { "data": "stdprice", "className" : "text-right",
                            render: function(data, type, row){                            
                                return formatNumber(data);
                            }
                        },
                        {"defaultContent": "<button class='btn btn-primary btn-xs'>ADD</button>"}
                    ],
                    "bDestroy": true,
                    "paging":   true,
                    "searching":   true
                });

                $('#list-barang tbody').on( 'click', 'button', function () {
                    var table = $('#list-barang').DataTable();
                    selected_data = [];
                    selected_data = table.row($(this).closest('tr')).data();  
                    if(materialExists(selected_data.material)){

                    }else{
                        kodebrg = selected_data.material;
                        $('#namabrg').val(selected_data.matdesc);
                        $('#satuan').val(selected_data.matunit);
                        detail_order_beli.push(selected_data);
                        console.log(detail_order_beli)

                        count = count+1;
                        html = '';
                        html = `
                            <tr counter="`+ count +`" id="tr`+ count +`">
                                <td class="nurut"> 
                                    `+ count +`
                                    <input type="hidden" name="itm_no[]" value="`+ count +`" />
                                </td>
                                <td> 
                                    <a href="#" data-imagepath="<?= BASEURL; ?>/images/material-images/`+selected_data.image+`" data-partcode="`+selected_data.material+`" class="img-preview">`+ selected_data.material +` - `+ selected_data.matdesc +`</a>
                                
                                    <input type="hidden" name="itm_material[]" counter="`+count+`" id="material`+count+`" class="form-control materialCode" style="width:150px;" required="true" value="`+ selected_data.material +`" readonly/>

                                    <input type="hidden" name="itm_matdesc[]" counter="`+count+`" id="matdesc`+count+`" class="form-control" style="width:300px;" value="`+ selected_data.matdesc +`" readonly/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_qty[]" counter="`+count+`" id="poqty`+count+`"  class="form-control inputNumber" style="width:100px; text-align:right;" required="true" autocomplete="off"/>
                                </td>
                                <td> 
                                `+ selected_data.matunit +`
                                    <input type="hidden" name="itm_unit[]" counter="`+count+`" id="unit`+count+`" class="form-control" style="width:80px;" required="true" value="`+ selected_data.matunit +`" readonly/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_price[]" counter="`+count+`" id="price`+count+`" class="form-control" style="width:80px;text-align:right;" required="true" value="`+ selected_data.stdprice +`"/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_remark[]" class="form-control" style="width:200px;" counter="`+count+`" id="remark`+count+`" autocomplete="off"/>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`" id="btnRemove`+count+`" data-count="`+ count +`">Remove</button>
                                </td>
                            </tr>
                        `;
                        $('#tbl-pr-body').append(html);
                        renumberRows();

                        $('.img-preview').on('click', function(){
                            var _data = $(this).data();
                            var imgContent = document.getElementById("materil-image");
                            imgContent.style.width = '350px';
                            $('#imagePreviewModalLabel').html('Part '+ _data.partcode + ' Image');
                            $('#materil-image').attr('src', '');
                            $('#materil-image').attr('src', _data.imagepath);
                            $('#imagePreviewModal').modal('show');
                        });

                        $('#btnRemove'+count).on('click', function(e){
                            e.preventDefault();
                            var row_index = $(this).closest("tr").index();
                            removeitem(row_index);                        
                            $(this).closest("tr").remove();
                            renumberRows();
                            console.log(detail_order_beli)
                        })

                        $('.removePO').on('click', function(e){
                            // e.preventDefault();
                            // $(this).closest("tr").remove();
                            // renumberRows();
                        });

                        $('.inputNumber').on('change', function(){
                            this.value = formatNumber(this.value);
                        });
                    }                    
                } );
            }

            function getnamabarang(_kodebrg){
                $.ajax({
                    url: base_url+'/barang/caribarangbykode/'+_kodebrg,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                        console.log(result)
                        namabrg = result.namabrg
                    }
                });                
            }

            $.ajax({
                url: base_url+'/pr/getpritem/'+sel_prnum,
                type: 'GET',
                dataType: 'json',
                cache:false,
                success: function(result){
                    // console.log(result)
                    
                    for(var i=0; i<result.length; i++){
                        detail_order_beli.push(result[i]);
                        count = count+1;
                        html = '';
                        html = `
                            <tr counter="`+ count +`" id="tr`+ count +`">
                                <td class="nurut"> 
                                    `+ count +`
                                    <input type="hidden" name="itm_no[]" value="`+ count +`" />
                                </td>
                                <td> 
                                    <a href="#" data-imagepath="<?= BASEURL; ?>/images/material-images/`+result[i].image+`" data-partcode="`+result[i].material+`" class="img-preview">`+ result[i].material +` - `+ result[i].matdesc +`</a>
                                
                                    <input type="hidden" name="itm_material[]" counter="`+count+`" id="material`+count+`" class="form-control materialCode" style="width:150px;" required="true" value="`+ result[i].material +`" readonly/>
                                    <input type="hidden" name="itm_matdesc[]" counter="`+count+`" id="matdesc`+count+`" class="form-control" style="width:300px;" value="`+ result[i].matdesc +`" readonly/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_qty[]" counter="`+count+`" id="poqty`+count+`"  class="form-control inputNumber readOnly" style="width:100px; text-align:right;" required="true" value="`+ formatNumber(result[i].quantity).replaceAll('.000','') +`"/>
                                </td>
                                <td> 
                                `+ result[i].unit +`
                                    <input type="hidden" name="itm_unit[]" counter="`+count+`" id="unit`+count+`" class="form-control readOnly" style="width:80px;" required="true" value="`+ result[i].unit +`" readonly/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_price[]" counter="`+count+`" id="price`+count+`" class="form-control readOnly" style="width:80px;text-align:right;" required="true" value="`+ formatNumber(result[i].price) +`" readonly/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_remark[]" class="form-control readOnly" style="width:200px;" counter="`+count+`" id="poprice`+count+`" value="`+ result[i].remark +`"/>
                                </td>
                                <td class="hideComponent">
                                    <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`" id="btnRemove`+count+`">Remove</button>
                                </td>
                            </tr>
                        `;
                        $('#tbl-pr-body').append(html);
                        renumberRows();

                        $('.img-preview').on('click', function(){
                            var _data = $(this).data();
                            var imgContent = document.getElementById("materil-image");
                            imgContent.style.width = '350px';
                            $('#imagePreviewModalLabel').html('Part '+ _data.partcode + ' Image');
                            $('#materil-image').attr('src', '');
                            $('#materil-image').attr('src', _data.imagepath);
                            $('#imagePreviewModal').modal('show');
                        });

                        // $('.removePO').on('click', function(e){
                        //     e.preventDefault();
                        //     $(this).closest("tr").remove();
                        //     renumberRows();
                        // })
                        $('#btnRemove'+count).on('click', function(e){
                            e.preventDefault();
                            var row_index = $(this).closest("tr").index();
                            removeitem(row_index);                        
                            $(this).closest("tr").remove();
                            renumberRows();
                            console.log(detail_order_beli)
                        })

                        $('.materialCode').on('change', function(){
                            var xcounter = $(this).attr('counter');
                            var kodebrg  = $('#material'+xcounter).val();

                            getMaterialbyKode(kodebrg, function(d){
                                console.log(d)
                                $('#matdesc'+xcounter).val(d.matdesc);
                                $('#unit'+xcounter).val(d.matunit);
                            });
                        })

                        $('.inputNumber').on('change', function(){
                            this.value = formatNumber(this.value);
                        });

                        $('.hideComponent').hide();
                        $('.readOnly').attr("readonly", true);
                    }
                },error: function(err){
                }
            }).done(function(){
                console.log(detail_order_beli);
            });

            function removeitem(index){
                detail_order_beli.splice(index, 1);
            }

            function renumberRows() {
                $(".mainbodynpo > tr").each(function(i, v) {
                    $(this).find(".nurut").text(i + 1);
                });
            }

            $('#form-pr-data').on('submit', function(event){
                event.preventDefault();
                
                var formData = new FormData(this);
                console.log($(this).serialize())
                    $.ajax({
                        url:base_url+'/pr/updatepr/'+sel_prnum,
                        method:'post',
                        data:formData,
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend:function(){
                            // $('#btn-save').attr('disabled','disabled');
                        },
                        success:function(data)
                        {
                        	console.log(data);
                        },
                        error:function(err){
                            showErrorMessage(JSON.stringify(err))
                        }
                    }).done(function(result){
                        // showSuccessMessage('PR ' + sel_prnum + ' Updated!')
                        console.log(result)
                        if(result.msgtype === "1"){
                            showSuccessMessage('PR '+ result.prnum + ' Updated!')
                        }else if(result.msgtype === "3"){
                            showErrorMessage("Deficit Quantity");
                            $errordata = result.data;
                            var irows = 0;
                            $('#tbl-err-body').html('');
                            for(var y = 0; y < result.data.length; y++){
                                irows=irows+1;
                                html = '';
                                html = `
                                    <tr counter="`+ irows +`">
                                        <td style="text-align:center"> 
                                            `+ irows +`
                                        </td>
                                        <td style="color:red;">
                                            `+ result.data[y].message +`
                                        </td>
                                    </tr>`;
                                $('#tbl-err-body').append(html);
                            }
                            $('#errorModal').modal('show');
                        }else{
                            showErrorMessage(JSON.stringify(result))
                        }
                    })
            })

            $('.readOnly').attr("readonly", true);
            $('.hideComponent').hide();

            $('#btn-change').on('click', function(){
                if(this.innerText === "Change"){
                    document.getElementById("btn-change").innerText = 'Display';
                    $('.readOnly').attr("readonly", false);
                    // $('._disable').attr("disabled", false);
                    $('._disable').removeAttr("disabled");
                    $('.hideComponent').show();
                    $('#title').html("Edit Purchase Request <?= $data['prhead']['prnum']; ?>");
                }else{
                    document.getElementById("btn-change").innerText = 'Change';
                    $('.readOnly').attr("readonly", true);
                    $('._disable').attr("disabled", true);
                    $('.hideComponent').hide();
                    $('#title').html("Display Purchase Request <?= $data['prhead']['prnum']; ?>");
                }                
            })

            $('#btn-dlg-add-item').on('click', function(){
                loaddatabarang();
                $('#barangModal').modal('show')
            })

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function formatRupiah(angka, prefix){
                var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                split   		  = number_string.split(','),
                sisa     		  = split[0].length % 3,
                rupiah     		  = split[0].substr(0, sisa),
                ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
            
                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
            
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }

            function showBasicMessage() {
                swal({title:"Loading...", text:"Mohon Menunggu", showConfirmButton: false});
            }

            function showSuccessMessage(message) {
                // swal("Success", message, "success");
                swal({title: "Success!", text: message, type: "success"},
                    function(){ 
                        window.location.href = base_url+'/pr';
                    }
                );
            }

            function showErrorMessage(message){
                swal("Error", message, "error");
            }
        })

        function isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
        }        
    </script>