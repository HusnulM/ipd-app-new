<section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
            <form id="form-post-data" enctype="multipart/form-data">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="note">Note</label>
                                            <input type="text" name="note" id="note" class="form-control" placeholder="Note">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="warehouse">Warehouse</label>
                                            <select class="form-control show-tick" name="warehouse" id="warehouse" required>
                                                <?php foreach($data['whs'] as $row): ?>
                                                    <option value="<?= $row['warehouseid']; ?>"><?= $row['warehouseid']; ?> - <?= $row['warehousename']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="regdate">Adjustment Date</label>
                                            <input type="date" name="adjdate" id="adjdate" class="datepicker form-control" value="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>    
                                </div>                               

                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="adjby">Adjust By</label>
                                            <input type="text" class="form-control" name="adjby" id="adjby" value="<?= $_SESSION['usr']['name']; ?>">
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="header">
                            <h2>
                                Part Items
                            </h2>
                                    
                            <ul class="header-dropdown m-r--5">                                
                                
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Part</th>
                                                <th>Description</th>
                                                <!-- <th>Remaining Qty</th> -->
                                                <th>Quantity</th>
                                                <th>Unit</th>
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
                                        <button type="submit" class="btn bg-blue">
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
                                        <th>Unit</th>
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
            let detail_order_beli = [];
            var kodebrg           = '';
            var namabrg           = '';
            var action            = '';
            var imgupload         = [];
            var count = 0;

            $('#namabrg').on('input', function(){
                // autocomplete_produk($('#namabrg').val())
            });

            function materialExists(material) {
                return detail_order_beli.some(function(el) {
                    return el.material === material;
                }); 
            }

            loaddatabarang();
            function loaddatabarang(){
                $('#list-barang').dataTable({
                    "ajax": base_url+'/inventory/getAllmaterial',
                    "columns": [
                        { "data": "material" },
                        { "data": "matdesc" },
                        { "data": "matunit" },
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

                        var _quantity = '';
                        if(selected_data.quantity == null || selected_data.quantity === "null"){
                            _quantity = 0;
                        }else{
                            _quantity = selected_data.quantity;
                            _quantity = _quantity.replace('.000','');
                        }
                        // <td> 
                        //             <input type="text" name="rem_qty[]" counter="`+count+`" id="rem_qty`+count+`"  class="form-control inputNumber" style="width:100px; text-align:right;" required="true" autocomplete="off" value="`+ _quantity +`" readonly/>
                        //         </td>
                        count = count+1;
                        html = '';
                        html = `
                            <tr counter="`+ count +`" id="tr`+ count +`">
                                <td class="nurut"> 
                                    `+ count +`
                                    <input type="hidden" name="itm_no[]" value="`+ count +`" />
                                </td>
                                <td> 
                                    <input type="text" name="itm_material[]" counter="`+count+`" id="material`+count+`" class="form-control materialCode" style="width:150px;" required="true" value="`+ selected_data.material +`" readonly/>
                                </td>
                                <td>
                                    <input type="text" name="itm_matdesc[]" counter="`+count+`" id="matdesc`+count+`" class="form-control" style="width:300px;" value="`+ selected_data.matdesc +`" readonly/>
                                </td>
                                
                                <td> 
                                    <input type="text" name="itm_qty[]" counter="`+count+`" id="poqty`+count+`"  class="form-control inputNumber" style="width:100px; text-align:right;" required="true" autocomplete="off"/>
                                </td>
                                <td> 
                                    <input type="text" name="itm_unit[]" counter="`+count+`" id="unit`+count+`" class="form-control" style="width:80px;" required="true" value="`+ selected_data.matunit +`" readonly/>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`" id="btnRemove`+count+`" data-count="`+ count +`">Remove</button>
                                </td>
                            </tr>
                        `;
                        $('#tbl-pr-body').append(html);
                        renumberRows();

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
                            // this.value = formatRupiah(this.value, '');
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
                $('#barangModal').modal('show')
            });

            $('#btn-pilih-barang').on('click', function(){
                $('#barangModal').modal('show')
            });

            $('#add-new-item').on('click', function(){
                $('#largeModalLabel').html('Add New Item')
                $('#largeModal').modal('show');
                $('#btn-add-item').html('Add Item');
                action = 'add';
            });            

            $('#form-post-data').on('submit', function(event){
                event.preventDefault();
                var formData = new FormData(this);
                console.log($(this).serialize())
                    $.ajax({
                        url:base_url+'/inventory/saveadjustment',
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
                    }).done(function(data){
                        if(data.msg === "success"){
                            showSuccessMessage(data.docnum)
                        }else{
                            showErrorMessage(JSON.stringify(err))
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
                        window.location.href = base_url+'/inventory/adjustment';
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