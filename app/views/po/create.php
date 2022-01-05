<section class="content">
        <div class="container-fluid">
            <!-- <form action="<?= BASEURL; ?>/po/savepo" method="POST" > -->
            <form id="form-po-data" enctype="multipart/form-data">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    <?= $data['menu']; ?>
                                </h2>

                                <ul class="header-dropdown m-r--5">          
                                    
                                </ul>
                            </div>
                            <div class="body">
                                
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="ponum">PO Number</label>
                                                <input type="text" name="ponum" id="ponum" class="form-control" maxlength="6" size="6" required placeholder="PO Number (6 digit)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="vendor">Vendor / Supplier</label>
                                                <input type="text" name="namavendor" id="namavendor" class="form-control"  readonly="true" required>
                                                <input type="hidden" name="vendor" id="vendor">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <br>
                                                <button class="btn bg-blue form-control" type="button" id="btn-search-vendor">
                                                <i class="material-icons">format_list_bulleted</i> <span>Choose Vendor</span>
                                                </button>
                                            </div>
                                        </div>    
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="podate">PO Date</label>
                                                <input type="date" name="podate" id="podate" class="datepicker form-control" placeholder="" required value="<?= date('Y-m-d'); ?>">
                                            </div>
                                        </div>    
                                    </div>                                    

                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="note">Note</label>
                                                <input type="text" name="note" id="note" class="form-control" placeholder="Note" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="attachment">Attachment</label>
                                                <input type="file" name="attachment[]" id="attachment" class="form-control" multiple>
                                            </div>
                                        </div>    
                                    </div>
                                </div>  
                            </div>
                        </div>

                        <div class="card" id="div-po-item">
                            <div class="header">
                                <h2>
                                    Purchase Order Item
                                </h2>
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
                                                        <th>Action</th>
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
                                            <button type="button" id="btn-add-poitem-from-pr" class="btn bg-blue">
                                                <i class="material-icons">playlist_add</i> <span>ADD ITEM FROM REQUEST SLIP</span>
                                            </button>

                                            <!-- <button type="button" id="btn-add-poitem" class="btn bg-blue">
                                                <i class="material-icons">playlist_add</i> <span>ADD ITEM</span>
                                            </button> -->

                                            <a href="<?= BASEURL ?>/po" type="button" id="btn-cancel" class="btn bg-red">
                                                <i class="material-icons">highlight_off</i> <span>CANCEL</span>
                                            </a>
                                            <button type="submit" id="btn-save" class="btn bg-green waves-effect">
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

        <div class="modal fade" id="barangModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="barangModal">Pilih Material</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive" id="list-barang" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Part Name</th>
                                        <th>Part Number</th>
                                        <th>Unit</th>
                                        <!-- <th>Order Unit</th> -->
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <!-- <td></td> -->
                                    </tr>
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
        var vendor            = '';
        var namavendor        = '';
        let detail_order_beli = [];
        var _ppnchecked = '';

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                event.preventDefault();
                return false;
                }
            });
        });

        $(document).ready(function(){
            var count = 0;
            $('#btn-search-vendor').on('click', function(){
                $('#vendorModal').modal('show');
                loadvendor();
            });    
            
            $('#btn-add-poitem-from-pr').on('click', function(){
                if(vendor === ""){
                    showErrorMessage('Input Vendor');
                }else{
                    $('#approvedReqeustSlipModal').modal('show');
                    // var selectedwhs = $('#warehouse').val();
                    // loadopenpr(selectedwhs);
                    loadApprovedSlip();
                }
            });

            function materialExists(material) {
                return detail_order_beli.some(function(el) {
                    return el.material === material;
                }); 
            }

            function reqeustExists(requestnum) {
                return detail_order_beli.some(function(el) {
                    console.log(el)
                    if(el.requestnum === requestnum){
                        return true;
                    }else{
                        return false;
                    }
                }); 
            }

            function removeitem(index){
                detail_order_beli.splice(index, 1);
            }

            $('#form-po-data').on('submit', function(event){
                event.preventDefault();
                if(detail_order_beli.length > 0){
                    if($('#vendor').val() === ''){
                        showErrorMessage("Select Vendor!");
                    }else{
                        var formData = new FormData(this);
                        console.log($(this).serialize())
                        $.ajax({
                            url:base_url+'/po/savepo',
                            method:'post',
                            data:formData,
                            dataType:'JSON',
                            contentType: false,
                            cache: false,
                            processData: false,
                            beforeSend:function(){
                                $('#btn-save').attr('disabled','disabled');
                                showBasicMessage();
                            },
                            success:function(data)
                            {

                            },
                            error:function(err){
                                showErrorMessage(JSON.stringify(err));
                                console.log(err);
                            }
                        }).done(function(result){
                            if(result.msgtype === "1"){
                                showSuccessMessage("PO "+ result.docnum + " Created");
                            }else{
                                showErrorMessage(result.docnum);
                            }
                        }) ;
                    }
                }else{
                    showErrorMessage("No Purchase Order Item");
                }
            })

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

            function removeitem(index){
                detail_order_beli.splice(index, 1);
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

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
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

            function showBasicMessage() {
                swal({title:"Loading...", text:"Please wait...", showConfirmButton: false});
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