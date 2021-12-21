<section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
            <form id="form-pr-data" enctype="multipart/form-data">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="note">Note</label>
                                            <input type="text" name="reqnote" id="note" class="form-control" placeholder="Note">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="attachment">Attachment</label>
                                            <input type="file" name="attachment" id="attachment" class="form-control">
                                        </div>
                                    </div>    
                                </div>
                                
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="regdate">Request Date</label>
                                            <input type="date" name="reqdate" id="reqdate" class="datepicker form-control" value="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>    
                                </div>

                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="requestor">Requestor</label>
                                            <input type="text" class="form-control" name="requestor" id="requestor" value="<?= $_SESSION['usr']['name']; ?>">
                                        </div>
                                    </div>    
                                </div>
                                
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="department">Department</label>
                                            <select class="form-control show-tick" name="department" id="department" required>
                                                <?php if(sizeof($data['departmentuser']) > 0) : ?>
                                                    <?php foreach($data['departmentuser'] as $row) : ?>
                                                        <option value="<?= $row['department']; ?>"><?= $row['deptname']; ?></option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>    
                                                    <?php foreach($data['department'] as $row) : ?>
                                                        <option value="<?= $row['id']; ?>"><?= $row['department']; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>    
                                </div>

                                <!-- <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="currency">Currency</label>
                                            <select class="form-control show-tick" name="currency" id="currency" required>
                                                <?php foreach($data['currency'] as $row): ?>
                                                    <option value="<?= $row['currency']; ?>"><?= $row['currency']; ?> - <?= $row['description']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>    
                                </div>                                     -->

                                
                            </div>                            
                        </div>
                    </div>

                    <div class="card">
                        <div class="header">
                            <h2>
                                Request Slip Items
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Part Number</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <!-- <th>Price</th> -->
                                                <!-- <th>Remark</th> -->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl-pr-body" class="mainbodynpo">

                                        </tbody>
                                    </table>
                                    <ul class="pull-right">    
                                        <button type="button" id="btn-dlg-add-item" class="btn bg-blue">
                                            <i class="material-icons">playlist_add</i> <span>ADD ITEM</span>
                                        </button>
                                        <a href="<?= BASEURL; ?>/requestslip" class="btn bg-red">
                                            <i class="material-icons">highlight_off</i> <span>CANCEL</span>
                                        </a>
                                        <button type="submit" class="btn bg-blue">
                                            <i class="material-icons">save</i> <span>SAVE REQUEST SLIP</span>
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
                                        <th>Unit</th>
                                        <!-- <th>Unit Price</th> -->
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

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                event.preventDefault();
                return false;
                }
            });
        });
        
        $(function(){
            let detail_order_beli = [];
            var kodebrg           = '';
            var namabrg           = '';
            var action            = '';
            var imgupload         = [];
            var count = 0;

            // $('#prtype').on('change', function(){
            //     // getwarehousebyprtype
            //     var _prtype = this.value;
            //     loadwarehouse(_prtype);
            // });

            // loadwarehouse($('#prtype').val());
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

            function materialExists(material) {
                return detail_order_beli.some(function(el) {
                    return el.material === material;
                }); 
            }

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function loaddatabarang(){
                // alert(_whscode)
                $('#list-barang').dataTable({
                    "ajax": base_url+'/requestslip/getmateriallist',
                    "columns": [
                        { "data": "material" },
                        { "data": "matdesc" },
                        { "data": "matunit" },
                        // { "data": "stdprice", "className" : "text-right",
                        //     render: function(data, type, row){                            
                        //         return formatNumber(data);
                        //     }
                        // },
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
                                <td style="width:300px;"> 
                                    <a href="#" data-imagepath="<?= BASEURL; ?>/images/material-images/`+selected_data.image+`" data-partcode="`+selected_data.material+`" class="img-preview">`+ selected_data.material +` - `+ selected_data.matdesc +`</a>

                                    <input type="hidden" name="itm_material[]" counter="`+count+`" id="material`+count+`" class="form-control materialCode" style="width:150px;" required="true" value="`+ selected_data.material +`" readonly/>

                                    <input type="hidden" name="itm_matdesc[]" counter="`+count+`" id="matdesc`+count+`" class="form-control" style="width:300px;" value="`+ selected_data.matdesc +`" readonly/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_qty[]" counter="`+count+`" id="poqty`+count+`"  class="form-control inputNumber" style=" text-align:right;" required="true" autocomplete="off"/>
                                </td>
                                <td> 
                                `+ selected_data.matunit +`
                                    <input type="hidden" name="itm_unit[]" counter="`+count+`" id="unit`+count+`" class="form-control" style="width:80px;" required="true" value="`+ selected_data.matunit +`" readonly/>
                                </td>
                                
                                
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`" id="btnRemove`+count+`" data-count="`+ count +`">Remove</button>
                                </td>
                            </tr>
                        `;
                        $('#tbl-pr-body').append(html);
                        renumberRows();

                        // <td> 
                        //             <input type="text" name="itm_price[]" counter="`+count+`" id="price`+count+`" class="form-control" style="text-align:right;" required="true" value="`+ selected_data.stdprice +`"/>
                        //         </td>

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

            function renumberRows() {
                $(".mainbodynpo > tr").each(function(i, v) {
                    $(this).find(".nurut").text(i + 1);
                });
            }

            $('#btn-dlg-add-item').on('click', function(){
                loaddatabarang();
                $('#barangModal').modal('show')
            })

            $('#btn-pilih-barang').on('click', function(){
                
                $('#barangModal').modal('show');
            });

            $('#add-new-item').on('click', function(){
                
                $('#largeModalLabel').html('Add New Item')
                $('#largeModal').modal('show');
                $('#btn-add-item').html('Add Item');
                action = 'add';
            });
            

            $('#form-pr-data').on('submit', function(event){
                event.preventDefault();
                
                var formData = new FormData(this);
                console.log($(this).serialize())
                    $.ajax({
                        url:base_url+'/requestslip/save',
                        method:'post',
                        data:formData,
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend:function(){
                            showBasicMessage();
                        },
                        success:function(data)
                        {
                        	console.log(data);
                        },
                        error:function(err){
                            showErrorMessage(JSON.stringify(err))
                        }
                    }).done(function(result){
                        console.log(result)
                        if(result.msgtype === "1"){
                            showSuccessMessage(result.message +' : '+ result.reqnum)
                        }else{
                            showErrorMessage(JSON.stringify(result))
                        }
                    })
            });

            function removeitem(index){
                detail_order_beli.splice(index, 1);
                // setpritem();
            }

            function formatRupiah(angka, prefix){
                var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                split   		  = number_string.split('.'),
                sisa     		  = split[0].length % 3,
                rupiah     		  = split[0].substr(0, sisa),
                ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
            
                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? ',' : '';
                    rupiah += separator + ribuan.join('.');
                }
            
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }

            function showBasicMessage() {
                swal({title:"Loading...", text:"Please wait...", showConfirmButton: false});
            }

            function showSuccessMessage(message) {
                // swal("Success", message, "success");
                swal({title: "Success!", text: message, type: "success"},
                    function(){ 
                        window.location.href = base_url+'/requestslip';
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