<section class="content">
    <div class="container-fluid">
        <form id="form-po-data" enctype="multipart/form-data">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2 id="title">
                                <?= $data['menu']; ?> <?= $data['pohead']['ponum']; ?>
                            </h2>

                            <ul class="header-dropdown m-r--5">   
                                <a href="<?= BASEURL; ?>/po" class="btn bg-teal waves-effect">
                                    <i class="material-icons">backspace</i> <span>BACK</span>
                                </a>

                                <button type="button" class="btn bg-green" id="btn-view-attachment">
                                    <i class="material-icons" id="_icon">attachment</i>
                                    <span id="act-txt">VIEW Attachment</span>
                                </button>

                                <button type="button" id="btn-change" class="btn bg-blue ">
                                    <i class="material-icons" id="_icon">edit</i> 
                                    <span id="act-txt">CHANGE</span>
                                </button>    
                            </ul>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="vendor">Vendor / Supplier</label>
                                            <input type="text" name="namavendor" id="namavendor" class="form-control readOnly" value="<?= $data['vendor']['supplier_name']; ?>" required>
                                            <input type="hidden" name="vendor" id="vendor" value="<?= $data['pohead']['vendor']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 hideComponent">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <br>
                                            <button class="btn bg-blue form-control hideComponent" type="button" id="btn-search-vendor">
                                            <i class="material-icons">format_list_bulleted</i> <span>Choose Vendor</span>
                                            </button>
                                        </div>
                                    </div>    
                                </div>
                                
                                
                                
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="podate">PO Date</label>
                                            <input type="date" name="podate" id="podate" class="datepicker form-control readOnly" placeholder="" required value="<?= $data['pohead']['podat']; ?>">
                                        </div>
                                    </div>    
                                </div>

                                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="note">Note</label>
                                            <input type="text" name="note" id="note" class="form-control readOnly" placeholder="Note" value="<?= $data['pohead']['note']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="note" style="text-align:right;">Total PO Amount</label>
                                            <input type="text" name="amount" id="amount" class="form-control readOnly" readonly value="<?= number_format($data['poamount']['poamount'],0,',','.'); ?>" style="text-align:right;">
                                        </div>
                                    </div>
                                </div> -->
                            </div>                                
                        </div>
                    </div>

                    <div class="card" id="div-po-item">
                        <div class="header">
                            <h2>
                                Purchase Order Item
                            </h2>
                                    
                            <ul class="header-dropdown m-r--5">          
                                <!-- <button type="button" id="btn-add-poitem-from-pr" class="btn bg-blue hideComponent">
                                    <i class="material-icons">playlist_add</i> <span>ADD ITEM FROM PR</span>
                                </button>

                                <button type="button" id="btn-add-poitem" class="btn bg-blue hideComponent">
                                    <i class="material-icons">playlist_add</i> <span>ADD ITEM</span>
                                </button> -->
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
                                                    <th>Item</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Unit Price</th>
                                                    <th>Request Slip</th>
                                                    <th class="hideComponent">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl-po-body" class="mainbodynpo">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="pull-right">          
                                        <!-- <button type="button" id="btn-add-poitem-from-pr" class="btn bg-blue hideComponent">
                                            <i class="material-icons">playlist_add</i> <span>ADD ITEM FROM PR</span>
                                        </button> -->

                                        <button type="submit" id="btn-save" class="btn bg-green waves-effect hideComponent">
                                            <i class="material-icons">save</i> <span>SAVE</span>
                                        </button>
                                    </ul>
                                </div>
                            </div>                                                                
                        </div>
                    </div>
                </div>
            </div>
        </form>
    
        <div class="modal fade" id="approvedReqeustSlipModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="approvedReqeustSlipModalLabel">Request Slip Data</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive" id="request-slip-data" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Reqeust Number</th>
                                        <th>Reqeust Date</th>
                                        <th>Note</th>
                                        <th>Department</th>
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
            

        <div class="modal fade" id="vendorModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="vendorModalLabel">Select Vendor</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive" id="list-vendor" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Vendor</th>
                                        <th>Vendor Name</th>
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
    </div>

    <div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="attachmentModalText">Attachment List</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-responsive" id="tbl-err-msg" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Attachment</th>
                            </thead>
                            <tbody>
                                <?php $icount = 0; ?>
                                <?php foreach ($data['attachments'] as $row) :?>
                                    <?php $icount += 1; ?>
                                    <tr>
                                        <td><?= $icount; ?></td>
                                        <td>
                                            <a href="<?= BASEURL; ?>/efile/po/<?= $row['efile']; ?>" target="_blank"><?= $row['efile']; ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
</section>
    

    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        var vendor            = "<?= $data['pohead']['vendor']; ?>";
        var namavendor        = '';
        var _ppnchecked       = '';
        let detail_order_beli = [];

        var sel_ponum = "<?= $data['pohead']['ponum']; ?>";

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                event.preventDefault();
                return false;
                }
            });
        });

        function materialExists(material) {
            return detail_order_beli.some(function(el) {
                return el.material === material;
            }); 
        }

        function pritemExists(prnum, pritem) {
            return detail_order_beli.some(function(el) {
                console.log(el)
                if(el.prnum === prnum && el.pritem === pritem){
                    return true;
                }else{
                    return false;
                }
            }); 
        }

        function removeitem(index){
            detail_order_beli.splice(index, 1);
        }

        $(document).ready(function(){

            $('.hideComponent').hide();
            $('#btn-view-attachment').on('click', function(){
                $('#attachmentModal').modal('show');
            });

            $('#btn-change').on('click', function(){
                if(this.innerText === "edit CHANGE"){
                    $('#act-txt').html('DISPLAY')
                    $('#_icon').html('pageview');
                    $('.readOnly').attr("readonly", false);
                    $('.hideComponent').show();
                    $('#title').html("Edit Purchase Order <?= $data['pohead']['ponum']; ?>");
                    $('.readOnly').attr('readonly', false);
                }else{
                    $('#act-txt').html('CHANGE')
                    $('#_icon').html('edit');
                    $('.readOnly').attr("readonly", true);
                    $('.hideComponent').hide();
                    $('#title').html("Detail Purchase Order <?= $data['pohead']['ponum']; ?>");
                }                
            })

            var count = 0;
            $('#btn-search-vendor').on('click', function(){
                $('#vendorModal').modal('show');
                loadvendor();
            });

            
            $('#btn-add-poitem-from-pr').on('click', function(){
                $('#approvedReqeustSlipModal').modal('show');
                    // var selectedwhs = $('#warehouse').val();
                    // loadopenpr(selectedwhs);
                loadApprovedSlip();
            });

            $('#form-po-data').on('submit', function(event){
                event.preventDefault();
                var formData = new FormData(this);
                console.log($(this).serialize())
                    $.ajax({
                        url:base_url+'/po/updatepo/data?ponum='+sel_ponum,
                        method:'post',
                        data:formData,
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend:function(){
                            $('#btn-save').attr('disabled','disabled');
                        },
                        success:function(data)
                        {
                        	console.log(data);
                        },
                        error:function(err){
                            console.log(err)
                        }
                    }).done(function(data){
                        showSuccessMessage('PO '+ sel_ponum + ' Updated!');
                    })
            })

            loadpoitem();
            function loadpoitem(){
                // alert(sel_ponum)
                $.ajax({
                    url: base_url+'/po/getpoitem/data?ponum='+sel_ponum,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                        // console.log(result)
                        for(var i=0; i<result.length; i++){
                            detail_order_beli.push(result[i]);
                            var stringprnum = '';
                            if(result[i].requestnum == null){
                                
                            }else{
                                stringprnum = result[i].requestnum + ' | ' + result[i].request_item;
                            }

                            var selqty = '';
                            selqty      = result[i].quantity.replace('.000','');

                            count = count+1;
                            html = '';
                            html = `
                                <tr counter="`+ count +`" id="tr`+ count +`">
                                    <td class="nurut"> 
                                        `+ count +`
                                        <input type="hidden" name="no[]" value="`+ count +`" />
                                    </td>
                                    <td> 
                                        <input type="text" name="itm_material[]" counter="`+count+`" id="material`+count+`" class="form-control materialCode" style="width:120px;" required="true" value="`+ result[i].material +`" readonly />
                                    </td>
                                    <td> 
                                        <input type="text" name="itm_matdesc[]" counter="`+count+`" id="matdesc`+count+`" class="form-control" style="width:250px;" value="`+ result[i].matdesc +`" readonly/>
                                    </td>
                                    <td> 
                                        <input type="text" name="itm_qty[]" counter="`+count+`" id="poqty`+count+`"  class="form-control inputNumber" style="width:100px; text-align:right;" required="true" value="`+ formatNumber(selqty) +`" readonly/>
                                    </td>
                                    <td> 
                                        <input type="text" name="itm_unit[]" counter="`+count+`" id="unit`+count+`" class="form-control" style="width:80px;" required="true" value="`+ result[i].unit +`" readonly/>
                                    </td>
                                    <td> 
                                        <input type="text" name="itm_price[]" class="form-control disableInput inputNumber readOnly" style="width:100px;text-align: right;" counter="`+count+`" id="poprice`+count+`" required="true" value="`+ formatNumber(result[i].price) +`"/>
                                    </td>
                                    <td> 
                                        <input type="text" name="prnum[]" counter="`+count+`" id="prnum`+count+`" class="form-control" style="width:130px;" readonly value="`+ stringprnum +`"/>

                                        <input type="hidden" name="itm_prnum[]" value="`+ result[i].requestnum +`" />
                                        <input type="hidden" name="itm_pritem[]" value="`+ result[i].request_item +`" />
                                    </td>
                                    <td class="hideComponent">
                                        <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`" id="btnRemove`+count+`" data-ponum="`+ result[i].ponum +`" data-poitem="`+ result[i].poitem +`">Remove</button>
                                    </td>
                                </tr>
                            `;
                            $('#tbl-po-body').append(html);
                            renumberRows();

                            // $('.removePO').on('click', function(e){
                            //     e.preventDefault();
                            //     $(this).closest("tr").remove();
                            //     renumberRows();
                            // });
                            
                            $('#btnRemove'+count).on('click', function(e){
                                e.preventDefault();
                                var _data = $(this).data();
                                var row_index = $(this).closest("tr").index();
                                removeitem(row_index);           
                                deletePOItem(_data.ponum,_data.poitem);             
                                $(this).closest("tr").remove();
                                renumberRows();
                                console.log(detail_order_beli)
                            });

                            $('.readOnly').attr('readonly', true);

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
                            })
                            
                            $('.hideComponent').hide();
                        }
                    }
                }).done(function(){
                    console.log(detail_order_beli)
                });
            }

            $('.readOnly').attr('readonly', true);

            function deletePOItem(ponum,poitem){
                $.ajax({
                    url: base_url+'/po/deletepoitem/'+ponum+'/'+poitem,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                })
            }

            // loadvendor();
            function loadvendor(){
                $('#list-vendor').dataTable({
                    "ajax": base_url+'/supplier/supplierlist',
                    "columns": [
                        { "data": "supplier_id" },
                        { "data": "supplier_name" },
                        {"defaultContent": "<button class='btn btn-primary btn-xs'>Select</button>"}
                    ],
                    "bDestroy": true,
                    "paging":   true,
                    "searching":   true
                });

                $('#list-vendor tbody').on( 'click', 'button', function () {
                    var table = $('#list-vendor').DataTable();
                    selected_data = [];
                    selected_data = table.row($(this).closest('tr')).data();
                    
                    vendor     = selected_data.supplier_id;
                    namavendor = selected_data.supplier_name;
                    $('#vendor').val(selected_data.supplier_id);
                    $('#namavendor').val(selected_data.supplier_name);
                    $('#vendorModal').modal('hide');
                } );               
            }

            function loadApprovedSlip(){
                $('#request-slip-data').dataTable({
                    "ajax": base_url+'/requestslip/getapprovedslip',
                    "columns": [
                        { "data": "requestnum" },
                        { "data": "request_date" },
                        { "data": "request_note" },
                        { "data": "department" },
                        {"defaultContent": "<button class='btn btn-primary btn-xs'>Select</button>"}
                    ],
                    "bDestroy": true,
                    "paging":   true,
                    "searching":   true
                });

                $('#request-slip-data tbody').on( 'click', 'button', function () {
                    var table = $('#request-slip-data').DataTable();
                    selected_data = [];
                    selected_data = table.row($(this).closest('tr')).data();
                    
                    // alert(selected_data.requestnum)
                    $.ajax({
                        url: base_url+'/requestslip/getapprovedslipitem/'+selected_data.requestnum,
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
                        $('#tbl-po-body').html('');
                        var count = 0;
                        var html = '';
                        for(var i = 0; i < data.length; i++){
                            detail_order_beli.push(data[i]);
                            count = count+1;
                            var selqty = '';
                            selqty      = data[i].quantity.replace('.000','');
                            // selqty      = selqty.replace('.',',');
                            // selqty      = selqty.replace('.',',');
                            // selqty      = selqty.replace('.',',');
                            html = `
                                <tr counter="`+ count +`" id="tr`+ count +`">
                                    <td class="nurut"> 
                                        `+ count +`
                                        <input type="hidden" name="no[]" value="`+ count +`" />
                                    </td>
                                    <td> 
                                        `+ data[i].material +`
                                        <input type="hidden" name="itm_material[]" counter="`+count+`" id="material`+count+`" class="form-control" style="width:150px;" value="`+ data[i].material +`" required="true" readonly/>
                                    </td>
                                    <td> 
                                        `+ data[i].matdesc +`
                                        <input type="hidden" name="itm_matdesc[]" counter="`+count+`" id="matdesc`+count+`" class="form-control" style="width:250px;" value="`+ data[i].matdesc +`" required="true" readonly/>
                                    </td>
                                    <td style="text-align:right;"> 
                                        `+ formatNumber(selqty) +`
                                        <input type="hidden" name="itm_qty[]" counter="`+count+`" id="poqty`+count+`"  class="form-control inputNumber" style="width:100px;text-align:right;" value="`+ selqty +`" required="true" autocomplete="off" readonly/>
                                    </td>
                                    <td> 
                                        `+ data[i].unit +`
                                        <input type="hidden" name="itm_unit[]" counter="`+count+`" id="unit`+count+`"  class="form-control" style="width:100px;" value="`+ data[i].unit +`" required="true" readonly/>                              
                                    </td>
                                    <td> 
                                        <input type="text" name="itm_price[]" class="form-control disableInput inputNumber" style="text-align: right;" counter="`+count+`" id="poprice`+count+`" required="true" autocomplete="off"/>
                                    </td>
                                    <td> 
                                    `+ data[i].requestnum +` | `+ data[i].request_item +`

                                        <input type="hidden" name="itm_prnum[]" id="selprnum`+count+`"  value="`+ data[i].requestnum +`" />
                                        <input type="hidden" name="itm_pritem[]" id="selpritem`+count+`"  value="`+ data[i].request_item +`" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`" id="btnRemove`+count+`">Remove</button>
                                    </td>
                                </tr>
                            `;
                            $('#tbl-po-body').append(html);
                            renumberRows();

                            $('#btnRemove'+count).on('click', function(e){
                                e.preventDefault();
                                var row_index = $(this).closest("tr").index();
                                removeitem(row_index);                        
                                $(this).closest("tr").remove();
                                renumberRows();
                                // console.log(detail_order_beli)
                            }); 

                            $('.inputNumber').on('change', function(){
                                this.value = formatNumber(this.value);
                            });
                        }
                    });
                    $('#approvedReqeustSlipModal').modal('hide');
                } ); 
            }

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function getMaterialbyKode(materialcode, callback){
                $.ajax({
                    url: base_url+'/barang/caribarangbykode/'+materialcode,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                        callback(result);
                    }
                });  
            }

            function renumberRows() {
                $(".mainbodynpo > tr").each(function(i, v) {
                    $(this).find(".nurut").text(i + 1);
                });
            }

            function showSuccessMessage(message) {
                swal({title: "Success!", text: message, type: "success"},
                    function(){ 
                        window.location.href = base_url+'/po';
                    }
                );
            }

            function showErrorMessage(message){
                swal("", message, "warning");
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
        })
    </script>