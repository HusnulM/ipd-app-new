<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <form id="form-post-data" action="grpo/post" method="POST" enctype="multipart/form-data">
            <!-- <form action="<?= BASEURL; ?>/grpo/post" method="POST" enctype="multipart/form-data"> -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="refnum">PO Number</label>
                                            <input type="text" class="form-control" name="refnum" id="refnum"/>
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="refnum">-</label>
                                            <button type="button" id="btn-sel-ref" class="btn btn-primary form-control">Select PO</button>
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="regdate">Receipt Date</label>
                                            <input type="date" name="mvdate" id="mvdate" class="datepicker form-control" value="<?= date('Y-m-d'); ?>" required>
                                            <input type="hidden" name="immvt" value="101">
                                        </div>
                                    </div>    
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="note">Receipt Note</label>
                                            <input type="text" name="note" id="note" class="form-control" placeholder="Note">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 txt-message">
                                    <label id="txt-message" style="color:red;"></label>    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card hideComponent">
                        <div class="header">
                            <h2>
                                Receipt Items
                            </h2>
                                    
                            <ul class="header-dropdown m-r--5">                                
                                <button type="button" id="btn-dlg-add-item" class="btn bg-blue moveOther">
                                    <i class="material-icons">playlist_add</i> <span>ADD ITEM</span>
                                </button>

                                <button type="submit" class="btn bg-blue" id="btn-post">
                                    <i class="material-icons">save</i> <span>POST</span>
                                </button>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered table-striped table-hover" id="tbl-move-item">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Warehouse</th>
                                                <th>PO Number</th>
                                                <th>PO Item</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl-item-body" class="mainbodynpo">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
        

        <!-- Modal PO Ref -->
        <div class="modal fade" id="referenceGR01Modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="referenceGR01ModalText">Select Purchase Order</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive" id="list-po-togr" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Supplier</th>
                                        <th>PO Date</th>
                                        <th>Note</th>
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
                                    </tr>
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

        
        <!-- Modal Error Message -->
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
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">TUTUP</button>
                    </div>
                </div>
            </div>
        </div>
</section>
    

    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        
        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });

        $(function(){

            var whslist = <?= json_encode($data['whslist']); ?>;

            let detail_order_beli = [];
            var kodebrg           = '';
            var namabrg           = '';
            var action            = '';
            var imgupload         = [];
            var count = 0;
            var eind  = '';
            
            $('.hideComponent, .hideHeader, .reservasi, .moveOther,.txt-message').hide();

            $('#refnum').on('keyup', function(e){
                if (e.key === 'Enter' || e.keyCode === 13) {
                    $('.txt-message, .hideComponent').hide();
                    $('#tbl-item-body').html('');
                    if($('#movement').val() === "GR01"){
                        $('#threfnum').html('PO Number');
                        $('#threfitm').html('PO Item');
                        $('#immvt').val('101');
                        $('.reservasi').hide();
                        readpoitem(this.value);
                    }else if($('#movement').val() === "TF01"){
                        $('#threfnum').html('Reservation Number');
                        $('#threfitm').html('Resservation Item');
                        $('#immvt').val('201');
                        $('.reservasi').show();
                        readreservationitem(this.value);
                    }else if($('#movement').val() === "TF02"){
                        $('#threfnum').html('Refrence Number');
                        $('#threfitm').html('Refrence Item');
                        $('#immvt').val('211');
                        $('.reservasi').show();
                        $('.hideComponent, .moveOther').show();
                    }else if($('#movement').val() === "GI01"){
                        $('#threfnum').html('Refrence Number');
                        $('#threfitm').html('Refrence Item');
                        $('#immvt').val('261');
                        $('.reservasi').hide();
                        $('.hideComponent').show();
                        $('.moveOther').show();
                    }
                }
            });

            $('#refnum').on('change', function(e){
                $('.txt-message, .hideComponent').hide();
                $('#threfnum').html('PO Number');
                    $('#threfitm').html('PO Item');
                    // $('#immvt').val('101');
                    $('.reservasi').hide();
                    readpoitem(this.value);
            });

            $('#btn-sel-ref').on('click', function(){
                loaddatapotogr();
                $('#referenceGR01Modal').modal('show');
            })

            function readpoitem(ponum){
                $.ajax({
                    url: base_url+'/grpo/getopenpoitem/data?ponum='+ponum,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                        // console.log(result)
                        if(result.length > 0){
                            append_data(result);
                            $('.hideComponent').show();
                        }else{
                            $('#txt-message').html('No PO Items!');
                            $('.txt-message').show();
                        }
                    }
                }); 
                // $.ajax({
                //     url: base_url+'/movement/checkporelstat/data?ponum='+ponum,
                //     type: 'GET',
                //     dataType: 'json',
                //     cache:false,
                //     success: function(result){
                //         console.log(result)
                //         if(result.approvestat == "1" || result.approvestat == "3"){
                //             $('#txt-message').html('PO Not Approved yet or Rejected!');
                //             $('.txt-message').show();
                //         }else if(result.approvestat == "2"){
                //         }
                //     }
                // }); 
                
            }

            function append_data(_data){
                $('#tbl-item-body').html('');
                var aMat = [];
                var aWhs = [];
                var aQty = [];
                for(var i=0;i<_data.length;i++){
                    var _refnum  = '';
                    var _refitem = '';

                    var quantity = 0;
                    quantity = _data[i].openqty;

                    selqty     = quantity.toString();
                    // selqty      = data[i].quantity.replace('.000','');
                    selqty     = selqty.replaceAll('.000','');

                    _refnum  = _data[i].ponum;
                    _refitem = _data[i].poitem;
                    count = count+1;
                    html = '';
                    html = `
                        <tr counter="`+ count +`" id="tr`+ count +`">
                            <td class="nurut"> 
                                `+ count +`
                                <input type="hidden" name="itm_no[]" value="`+ count +`" />
                            </td>
                            <td> 
                            `+ _data[i].material +`
                                <input type="hidden" name="itm_material[]" counter="`+count+`" id="material`+count+`" class="form-control materialCode" style="width:150px;" required="true" value="`+ _data[i].material +`" readonly/>
                            </td>
                            <td> 
                            `+ _data[i].matdesc +`
                                <input type="hidden" name="itm_matdesc[]" counter="`+count+`" id="matdesc`+count+`" class="form-control" style="width:300px;" value="`+ _data[i].matdesc +`" readonly/>
                            </td>
                            
                            <td> 
                                <input type="text" name="itm_qty[]" counter="`+count+`" id="inputqty`+count+`"  class="form-control inputNumber inputQty" style="width:100px; text-align:right;" required="true" value="`+ formatNumber(selqty) +`" data-openqty="`+quantity+`" />
                            </td>
                            <td> 
                            `+ _data[i].unit +`
                                <input type="hidden" name="itm_unit[]" counter="`+count+`" id="unit`+count+`" class="form-control" style="width:100px;" required="true" value="`+ _data[i].unit +`" readonly/>
                            </td>
                            <td> 
                                <input type="hidden" id="_itm_whs`+count+`" />
                                <select name="itm_whs[]" counter="`+count+`" id="whs`+count+`" class="form-control whsCode checkWhsAuth" required style="width:200px;">
                                    <option value="">Select Warehouse</option>
                                </select>
                            </td>
                            
                            <td> 
                            `+ _refnum +`
                                <input type="hidden" name="itm_refnum[]" counter="`+count+`" id="ponum`+count+`" class="form-control" style="width:120px;" required="true" value="`+ _refnum +`" readonly/>
                            </td>
                            <td> 
                            `+ _refitem +`
                                <input type="hidden" name="itm_refitem[]" class="form-control" style="width:80px;" counter="`+count+`" id="poitem`+count+`" required value="`+ _refitem +`" readonly/>
                            </td>
                            
                            <td>
                                <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`">Remove</button>
                            </td>
                        </tr>
                    `;
                    $('#tbl-item-body').append(html);
                    renumberRows();

                    $("#whs"+count).html('');
                    var listItems = '';
                    for (var x = 0; x < whslist.length; x++) {
                        listItems += "<option class='form-control' value='"+ whslist[x].warehouseid +"'>"+ whslist[x].warehouseid +" - "+ whslist[x].warehousename +"</option>";
                    };
                    $("#whs"+count).html(listItems);
                    $('#_itm_whs'+count).val($('#whs'+count).val())
                        

                    $('.removePO').on('click', function(e){
                        e.preventDefault();
                        $(this).closest("tr").remove();
                        renumberRows();
                    })

                    $('.inputNumber').on('change', function(){
                        this.value = formatNumber(this.value);
                    });

                    $('#inputqty'+count).on('change', function(){
                        var _inputqty = this.value;
                        
                        var inputqty = _inputqty.replaceAll(',','');
                        // alert(inputqty)
                        var _data    = $(this).data();
                        var currentcounter = $(this).attr('counter');
                        // console.log('change qty')
                        
                        if((inputqty*1) > (_data.openqty*1)){
                            var _openqty = '';
                            _openqty     = _data.openqty.toString();
                            _openqty     = _openqty.replaceAll('.000','');  
                            showErrorMessage('Input quantity ('+ inputqty +') greater than open quantity ('+ _openqty +')');
                            this.value = formatNumber(_openqty);
                        }
                    });                    
                }
            }

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function checkauthwhs(_whscode, callback){
                $.ajax({
                    url: base_url+'/movement/checkauthwhs/'+_whscode,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                        console.log(result)
                        callback(result);
                    }
                });                
            }

            

            // listreservasitotf
            // loaddatapotogr();
            function loaddatapotogr(){
                $('#list-po-togr').dataTable({
                    "ajax": base_url+'/grpo/listpotogr',
                    "columns": [
                        { "data": "ponum" },
                        { "data": "supplier_name" },
                        { "data": "podat" },
                        { "data": "note" },
                        {"defaultContent": "<button class='btn btn-primary btn-xs'>SELECT</button>"}
                    ],
                    "bDestroy": true,
                    "paging":   true,
                    "searching":   true
                });

                $('#list-po-togr tbody').on( 'click', 'button', function () {
                    var table = $('#list-po-togr').DataTable();
                    selected_data = [];
                    selected_data = table.row($(this).closest('tr')).data();
                    $('#refnum').val(selected_data.ponum);   
                    $('#referenceGR01Modal').modal('hide');
                    readpoitem(selected_data.ponum);      
                });
            }


            function checkstock(_material, _whscode, _inpqty, callback){
                $.ajax({
                    url: base_url+'/movement/checkstock/'+_material+'/'+_whscode+'/'+_inpqty,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                        // console.log(result)
                        callback(result);
                    }
                });                
            }

            function renumberRows() {
                $(".mainbodynpo > tr").each(function(i, v) {
                    $(this).find(".nurut").text(i + 1);
                });
            }
            
            function autocomplete_produk(namaproduk){
                // alert(namaproduk)
                kodebrg = '';
                namabrg = '';
                $("#namabrg").autocomplete({
                    source: base_url+"/barang/caribarang/"+namaproduk,
                    select: function(event, ui){				
                        var uilabel = ui.item.label.split(' ');
                        kodebrg = '';
                        namabrg = '';
                        kodebrg = uilabel[0];
                        // namabrg = uilabel[1];
                        console.log(uilabel);
                        $('#satuan').val(uilabel[uilabel.length - 1]);
                        getnamabarang(kodebrg)
                    }
                }); 
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

            $('#btn-dlg-add-item').on('click', function(){
                $('#barangModal').modal('show')
            })

            $('#btn-pilih-barang').on('click', function(){
                $('#barangModal').modal('show')
            });

            $('#add-new-item').on('click', function(){
                $('#largeModalLabel').html('Add New Item')
                $('#largeModal').modal('show');
                $('#btn-add-item').html('Add Item');
                action = 'add';
            })

            $('#form-post-data').on('submit', async function(event){
                event.preventDefault();
                $("#btn-post").attr("disabled", true);
                    var formData = new FormData(this);
                    $.ajax({
                        url:base_url+'/grpo/post',
                        method:'post',
                        data:formData,
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend:function(){
                            $("#btn-post").attr("disabled", true);
                        },
                        success:function(data)
                        {
                        	// console.log(data);
                        },
                        error:function(err){
                            showErrorMessage(JSON.stringify(err))
                            console.log(err.responseText)     
                        }
                    }).done(function(result){
                        console.log(result)

                        if(result.msgtype === "1"){
                            showSuccessMessage('Inventory Movement Success '+ result.docnum);
                            $("#btn-post").attr("disabled", false);
                        }else if(result.msgtype === "2"){
                            // showSuccessMessage('Inventory Movement Success '+ data.docnum)
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
                                $("#btn-post").attr("disabled", false);
                            }
                            $('#errorModal').modal('show');
                        }else{
                            showErrorMessage(JSON.stringify(result))   
                            console.log(result)                      
                            $("#btn-post").attr("disabled", false);   
                        }
                    })
            });

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
                        window.location.href = base_url+'/grpo';
                    }
                );
            }

            function showErrorMessage(message){
                swal("", message, "error");
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