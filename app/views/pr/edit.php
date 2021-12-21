    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Edit Purchase Request <b><?= $data['prhead']['prnum']; ?></b>                            
                            </h2>
                        </div>
                        <div class="body">
                            <form>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="note">Note</label>
                                                <input type="hidden" id="prnum" value="<?= $data['prhead']['prnum']; ?>">
                                                <input type="text" name="note" id="note" class="form-control" placeholder="Note" value="<?= $data['prhead']['note']; ?>" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="project">Project</label>
                                                <select class="form-control show-tick" name="project" id="project">
                                                    <option value="<?= $data['prhead']['idproject']; ?>"><?= $data['prhead']['namaproject']; ?></option>
                                                    <?php foreach($data['project'] as $proj) : ?>
                                                    <option value="<?= $proj['idproject']; ?>"><?= $proj['namaproject']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="regdate">Tanggal Request</label>
                                                <input type="date" name="reqdate" id="reqdate" class="datepicker form-control" value="<?= $data['prhead']['tglrequest']; ?>" >
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <!-- PR Item -->
                        <div class="header">
                            <h2>
                                Purchase Request Item
                            </h2>

                            <ul class="header-dropdown m-r--5">                                
                                <button type="button" id="btn-save" class="btn btn-primary waves-effect pull-right">Save</button>
                            </ul>
                        </div>
                        <div class="body">
                                <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table id="dg" class="easyui-datagrid" style="width:98%;height:200px" toolbar="#toolbar" fitColumns="true" singleSelect="true">
                                        <thead>
                                            <tr>
                                                <th field="namabrg"  width="500">Item Name</th>
                                                <th field="quantity" width="120">Quantity</th>
                                                <th field="unit"     width="80">Unit</th>
                                                <th field="remark"   width="350">Remark</th> 
                                            </tr>
                                        </thead>
                                    </table>
                                    <div id="toolbar">
                                        <button class="easyui-linkbutton" iconCls="icon-add" plain="true" id="add-new-item">Add New Item</button>
                                        <button class="easyui-linkbutton" iconCls="icon-edit" plain="true" id="edit-pr-item">Change PR Item</button>
                                        <button class="easyui-linkbutton" iconCls="icon-remove" plain="true" id="delete-pr-item">Delete PR Item</button>
                                    </div>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="largeModalLabel">Add Purchase Request Item</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="namabrg">Item Name</label>
                                            <input type="text" name="namabrg" id="namabrg" class="form-control" placeholder="Item Name" autocomplete="false" > 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="">-</label>
                                            <button id="btn-pilih-barang" class="form-control btn btn-primary">Pilih Barang</button> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-8">
                                    <div class="form-group">
                                        <div class="btn btn-primary btn-sm float-left" style="width:100%;">
                                            <span>Upload Photo Barang</span>
                                            <input type="file" id="ifile" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                                    <div class="form-group">
                                        <button class="form-control btn btn-primary" id="btn-clear-file">Clear File</button>
                                    </div>
                                </div>  
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="podate">Quantity</label>
                                            <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Quantity" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="satuan">Unit</label>
                                            <input type="text" name="satuan" id="satuan" class="form-control" placeholder="Unit">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <!-- <div class="form-group">
                                        <div class="form-line">
                                            <label for="harga">Price</label>
                                            <input type="text" name="harga" id="harga" class="form-control" placeholder="Price">
                                        </div>
                                    </div> -->
                                    <input type="hidden" name="harga" id="harga" class="form-control" placeholder="Price">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea name="remark" class="form-control" id="remark" cols="30" rows="3" placeholder="Remark"></textarea>
                                        </div>
                                    </div>
                                </div>                                                                     
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" id="btn-add-item" class="btn btn-primary">Add Item</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="barangModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-m" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="barangModal">Pilih Barang</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-responsive" id="list-barang" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Item Name</th>
                                            <th>Unit</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
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
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">TUTUP</button>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        
        $(function(){
            let detail_order_beli = [];
            var kodebrg           = '';
            var namabrg           = '';
            var action            = '';
            var imgupload         = [];

            loaddatabarang();
            function loaddatabarang(){
                $('#list-barang').dataTable({
                    "ajax": base_url+'/barang/listbarang',
                    "columns": [
                        { "data": "kodebrg" },
                        { "data": "namabrg" },
                        { "data": "satuan" },
                        {"defaultContent": "<button class='btn btn-primary btn-xs'>Pilih</button>"}
                    ],
                    // "bDestroy": true,
                    "paging":   true,
                    "searching":   true
                });

                $('#list-barang tbody').on( 'click', 'button', function () {
                    var table = $('#list-barang').DataTable();
                    selected_data = [];
                    selected_data = table.row($(this).closest('tr')).data();
                    
                    kodebrg = selected_data.kodebrg;
                    $('#namabrg').val(selected_data.namabrg);
                    $('#satuan').val(selected_data.satuan);
                    $('#barangModal').modal('hide');
                } );
            }

            $.ajax({
                url: base_url+'/pr/getpritem/'+$('#prnum').val(),
                type: 'GET',
                dataType: 'json',
                cache:false,
                success: function(result){
                    for(var i=0; i<result.length; i++){
                        let object = new Object();
                        object["kodebrg"]  = result[i].kodebrg;
                        object["namabrg"]  = result[i].namabrg;
                        object["jmlPesan"] = result[i].jumlah;
                        object["satuan"]   = result[i].satuan;
                        object["harga"]    = result[i].harga;
                        object["total"]    = result[i].harga * result[i].jumlah;
                        object["remark"]    = result[i].remark;

                        detail_order_beli.push(object);		
                        // setpritem();
                        var count = $('#dg').datagrid('getRows');
                        $('#dg').datagrid('appendRow',{
                            item        : count.length + 1,
                            namabrg     : result[i].namabrg,
                            quantity	: result[i].jumlah,
                            unit        : result[i].satuan.toUpperCase(),
                            price       : formatRupiah(result[i].harga,''), 
                            subtot      : formatRupiah(result[i].jumlah * result[i].harga, ''),
                            remark      : result[i].remark
                        });

                        $('#dg').datagrid('reload');
                    }
                },error: function(err){
                }
            });

            $('#btn-pilih-barang').on('click', function(){
                $('#barangModal').modal('show')
            })

            $('#add-new-item').on('click', function(){
                $('#largeModalLabel').html('Add New Item')
                $('#largeModal').modal('show');
                $('#btn-add-item').html('Add Item');
                action = 'add';
            })

            $('#edit-pr-item').on('click', function(){
                var row      = $('#dg').datagrid('getSelected');					
                var rowIndex = $("#dg").datagrid("getRowIndex", row);
                if(rowIndex == "-1"){
                    showErrorMessage('No data selected')
                }else{
                    $('#largeModalLabel').html('Edit Item')
                    $('#largeModal').modal('show');
                    $('#btn-add-item').html('Update Item');
                    action = 'edit';

                    kodebrg = row.kodebrg;
                    $('#namabrg').val(row.namabrg);
                    $('#jumlah').val(row.quantity);
                    $('#satuan').val(row.unit);
                    $('#harga').val(row.price);
                    $('#remark').val(row.remark);
                }
            })

            $('#delete-pr-item').on('click', function(){
                deleterow();
            })

            $('#namabrg').on('input', function(){
                // autocomplete_produk($('#namabrg').val())
            })

            function deleterow(){
                var row = $('#dg').datagrid('getSelected');		
                console.log(row)			
                var rowIndex = $("#dg").datagrid("getRowIndex", row);

                var index = rowIndex+1;
                $('#dg').datagrid('deleteRow', rowIndex);
                removeitem(rowIndex)

                // deletepritem
                $.ajax({
                    url: base_url+'/pr/deletepritem/'+$('#prnum').val()+'/'+row.item,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                        console.log(result)
                    }
                });
            }

            function autocomplete_produk(namaproduk){
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

            $('#btn-save').on('click', async function(){
                    $("#btn-save").attr("disabled", true);
                    if(detail_order_beli.length > 0){
                        if ($('#reqdate').val() == ''){
                            showErrorMessage('Tanggal Request belum diisi')
                        }else{
                            let object = new Object();
                            let tglorder = $('#reqdate').val();
                            let tnote    = $('#note').val();

                            var oheader = {};
                            var oitem   = {};
                            var prdata  = {};
                            var header  = [];
                            var items   = [];

                            oheader.tglorder   = tglorder;
                            oheader.note       = $('#note').val();
                            oheader.project    = $('#project').val();
                            header.push(oheader);

                            for(var i=0; i < detail_order_beli.length; i++){
                                oitem = {};
                                oitem.pritem   = i + 1;
                                oitem.kodebrg  = detail_order_beli[i].kodebrg;
                                oitem.namabrg  = detail_order_beli[i].namabrg;
                                oitem.jmlPesan = detail_order_beli[i].jmlPesan;
                                oitem.satuan   = detail_order_beli[i].satuan.toUpperCase();
                                var aharga = detail_order_beli[i].harga.split('.');  
                                var xharga = '';
                                for(var x=0; x < aharga.length; x++){
                                    xharga = xharga+''+aharga[x];
                                }
                                oitem.harga    = xharga;
                                oitem.remark   = detail_order_beli[i].remark;
                                items.push(oitem);
                            }

                            prdata = {
                                'header' : header,
                                'items'  : items
                            }

                            $.ajax({
                                url: base_url+'/pr/updatepr/'+$('#prnum').val(),
                                data: prdata,
                                type: 'POST',
                                dataType: 'json',
                                cache:false,
                                success: await function(result){
                                    
                                },error: await function(err){
                                    showErrorMessage(JSON.stringify(err))
                                    $("#btn-save").attr("disabled", false);
                                }
                            }).done(function(data){
                                if(imgupload.length > 0 ){
                                    uploadfile($('#prnum').val());
                                }else{
                                    showSuccessMessage('PR ' + $('#prnum').val() + ' updated')
                                    detail_order_beli = [];
                                    setpritem();
                                    $("#btn-save").attr("disabled", false);
                                }
                            });;
                            
                            $('#note').val('');
                        }
                    }else{
                        showErrorMessage('Request Item Masih Kosong')
                    }       
                    
                    $("#btn-save").attr("disabled", false);
            })

            function uploadfile(prnum){
                for(var i = 0; i < imgupload.length; i++){
                    var fd = new FormData();
                    var item = (i+1)*1;
                    var _item = imgupload[i].pritem;
                    fd.append('file',imgupload[i].files);
                    $.ajax({
                        url: base_url+'/pr/uploadfile/'+prnum+'/'+_item,
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(response){
							showSuccessMessage('PR ' + prnum + ' updated')
                                    detail_order_beli = [];
                                    setpritem();
                                    $("#btn-save").attr("disabled", false);
                        },
                        error: function(err){
                            showErrorMessage(JSON.stringify(err))
                        }
                    });
                }
            }

            $('#btn-add-item').on('click', function(){		
                
                let jmlPesan = $('#jumlah').val();
                let satuan   = $('#satuan').val();
                namabrg      = $('#namabrg').val();

                if(namabrg == ""){
                    showErrorMessage('Input Item Name')
                }else if(jmlPesan == ""){
                    showErrorMessage('Input Quantity')
                }else if(satuan == ""){
                    showErrorMessage('Input Unit')
                }else{
                    if(action === 'add'){
                        var aharga = $('#harga').val().split('.');
                        var xharga = '';
                        for(var x=0; x < aharga.length; x++){
                            xharga = xharga+''+aharga[x];
                        }
                        let object = new Object();
                        object["kodebrg"]  = kodebrg;
                        object["namabrg"]  = $('#namabrg').val();
                        object["jmlPesan"] = jmlPesan;
                        object["satuan"]   = satuan.toUpperCase();
                        object["harga"]    = xharga;
                        object["total"]    = xharga * jmlPesan;
                        object["remark"]   = $('#remark').val();

                        detail_order_beli.push(object);	

                        var count = $('#dg').datagrid('getRows');

                        $('#dg').datagrid('appendRow',{
                            item        : count.length + 1,
                            namabrg     : $('#namabrg').val(),
                            quantity	: jmlPesan,
                            unit        : satuan.toUpperCase(),
                            price       : $('#harga').val(), 
                            subtot      : formatRupiah(xharga * jmlPesan, ''),
                            remark      : $("#remark").val()
                        });

                        var pritem = (detail_order_beli.length)*1;
                        var files = $('#ifile')[0].files[0];
                        if(files){
                            let imgobject = new Object();
                            imgobject["pritem"] = pritem;
                            imgobject["files"]  = files;
                            imgupload.push(imgobject)
                        }
                        
                        console.log(imgupload)
                        $("#ifile").val('');

                    }else if(action === 'edit'){
                        var row      = $('#dg').datagrid('getSelected');					
                        var rowIndex = $("#dg").datagrid("getRowIndex", row);

                        var aharga = $('#harga').val().split('.');
                        var xharga = '';
                        for(var x=0; x < aharga.length; x++){
                            xharga = xharga+''+aharga[x];
                        }

                        detail_order_beli[rowIndex].kodebrg  = kodebrg;
                        detail_order_beli[rowIndex].namabrg  = $('#namabrg').val();
                        detail_order_beli[rowIndex].jmlPesan = jmlPesan;
                        detail_order_beli[rowIndex].satuan   = satuan.toUpperCase();
                        detail_order_beli[rowIndex].harga    = xharga;
                        detail_order_beli[rowIndex].total    = xharga * jmlPesan;
                        detail_order_beli[rowIndex].remark   = $('#remark').val();

                        var files = $('#ifile')[0].files[0];
                        
                        if(files){
                            imgupload.splice(rowIndex,1);
                            let imgobject = new Object();
                            imgobject["pritem"] = (rowIndex+1)*1;;
                            imgobject["files"]  = files;
                            imgupload.push(imgobject)
                        }
                        console.log(imgupload)
                        $("#ifile").val('');

                        doeditRow(rowIndex, $('#namabrg').val(), jmlPesan, satuan, xharga, $("#remark").val());
                    }

                    $('#dg').datagrid('reload');
                    $('#largeModal').modal('hide');
                    kodebrg = '';
                    namabrg = '';
                    clearinput();
                }
            })

            function createCell(i, row, text) {
                var btn = document.createElement('input');
                var td = document.createElement('td');
                var content = document.createTextNode(text);

                if(text == 'Edit'){			
                    btn.type = "button";
                    btn.className = "btn btn-primary btn-xs";
                    btn.value     = 'Edit';
                    td.appendChild(btn);
                    td.addEventListener('click', function() { 
                        edititem(i);
                    }, false);
                }else if(text == 'Hapus'){			
                    btn.type = "button";
                    btn.className = "btn btn-danger btn-xs";
                    btn.value     = 'Hapus';
                    td.appendChild(btn);
                    td.addEventListener('click', function() { 
                        removeitem(i);
                    }, false);
                }else{
                    td.appendChild(content);
                }	
                row.appendChild(td);
            }

            function clearinput(){
                $('#namabrg').val('');
                $('#jumlah').val('');
                $('#satuan').val('');
                $('#harga').val('');
                $('#remark').val('');
            }

            function removeitem(index){
                detail_order_beli.splice(index, 1);
                setpritem();
            }

            function doeditRow(rowIndex, namabrg, qty, unit, price, remark) {
                alert(kodebrg)
                $('#dg').datagrid('updateRow', {
                    index: rowIndex,
                    row: {
                        kodebrg   : kodebrg,
                        namabrg   : namabrg,
                        quantity  : qty,
                        unit      : unit,
                        price     : formatRupiah(price,''),
                        subtot    : formatRupiah(price * qty, ''),
                        remark    : remark
                    }
                });
            }

            function setpritem(){
                console.log(detail_order_beli)
                $("#tbl-body").html('');
                for(var i = 0; i < detail_order_beli.length; i++){
                    let num   = i + 1;
                    var tr = document.createElement('tr');
                    createCell(i, tr, num);
                    createCell(i, tr, detail_order_beli[i].namabrg);
                    createCell(i, tr, detail_order_beli[i].jmlPesan);
                    createCell(i, tr, detail_order_beli[i].satuan);	
                    createCell(i, tr, formatRupiah(detail_order_beli[i].harga,''));	
                    createCell(i, tr, formatRupiah(detail_order_beli[i].total,''));			
                    createCell(i, tr, 'Hapus');			
                    // document.querySelectorAll('#tbl-body')[0].appendChild(tr);
                }

                $('#nmBarang').val('');
                $('#jmlPesan').val('');
                $('#hargabarang').val('');
            }

            var harga  = document.getElementById('harga');

            harga.addEventListener('keyup', function(e){
                harga.value = formatRupiah(this.value, '');
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