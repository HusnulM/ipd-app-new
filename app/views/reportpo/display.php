<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" id="div-po-item">
                    
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?>
                        </h2>
                        <ul class="header-dropdown m-r--5">                                
                            <a href="<?= BASEURL; ?>/exportdata/exportreportpo/<?= $data['strdate']; ?>/<?= $data['enddate']; ?>/<?= $data['openpo']; ?>" target="_blank" class="btn bg-blue">
                               <i class="material-icons">cloud_download</i> EXPORT DATA
                            </a>

                            <a href="<?= BASEURL; ?>/reportpo" class="btn bg-blue">
                               <i class="material-icons">backspace</i> BACK
                            </a>
                        </ul>
                    </div>
                    <div class="body">                                
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered table-striped table-hover" style="width:100%;font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>PO Number</th>
                                        <th>PO Date</th>
                                        <th>Note</th>
                                        <th>Reject Note</th>
                                        <th>Created By</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    
                                </tfoot>
                            </table>
                        </div>
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
                        <table class="table table-responsive" id="tbl-attachment" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Attachment</th>
                            </thead>
                            <tbody id="tbl-attachment-body">
                                
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
        $(function(){
            var strdate = "<?= $data['strdate']; ?>";
            var enddate = "<?= $data['enddate']; ?>";
            var openpo  = "<?= $data['openpo']; ?>";

            // alert(openpo)

            function format ( d, results ) {
                console.log(results)
                var html = '';
                html = `<table class="table table-bordered table-striped" style="padding-left:50px;width:100%;">
                       <thead>
                            <th>PO Item</th>
                            <th>Material</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Receipt Qty</th>
                            <th>Open Qty</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                       </thead>
                       <tbody>`;
                for(var i = 0; i < results.length; i++){
                    var qty = '';
                    var receiptqty = '';
                    var openQty = 0;
                    qty = results[i].quantity;
                    receiptqty = results[i].grqty;
                    qty = qty.replaceAll('.000','');
                    receiptqty = receiptqty.replaceAll('.000','');
                    
                    openQty = (qty*1)-(receiptqty*1);
                    // qty = qty.replaceAll('.',',');
                    html +=`
                       <tr>
                            <td style="text-align:right;"> `+ results[i].poitem +` </td> 
                            <td> `+ results[i].material +` </td>                            
                            <td> `+ results[i].matdesc +` </td>
                            <td style="text-align:right;"> `+ formatNumber(qty) +` </td>
                            
                            <td style="text-align:right;"> `+ formatNumber(receiptqty) +` </td>
                            
                            <td style="text-align:right;"> `+ formatNumber(openQty) +` </td>
                            <td> `+ results[i].unit +` </td>
                            <td style="text-align:right;"> `+ formatNumber(results[i].price) +` </td>
                       </tr>
                       `;
                }

                html +=`</tbody>
                        </table>`;
                return html;
            }   

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            var table = $('#example').DataTable( {
                "ajax": base_url+"/reportpo/getheaderdata/"+strdate+"/"+enddate+"/"+openpo,
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    {"data": "ponum",
                        "render": function (data, type, row) {
                            console.log(row)
                            if (row.is_rejected === 'Y') {
                                return '';
                            }
                            else {
                                return "<button class='btn btn-primary btn-xs btnPrint'>Print</button>";
                            }
                        }
                    },
                    {"defaultContent": "<button class='btn btn-primary btn-xs btnAttachment'>View Attachment</button>"},
                    { "data": "ponum" },
                    { "data": "podat" },
                    { "data": "note" },
                    { "data": "reject_note" },
                    { "data": "createdby" },
                    { "data": "approvestat", 
                        "render": function (data, type, row) {
                            console.log(row)
                            if (data == 99) {
                                return 'Rejected';
                            }else if (data == 1) {
                                return 'Open';
                            }
                            else {
                                return 'Approved';
                            }
                        }
                    }
                ],
                "order": [[1, 'asc']],
                "pageLength": 50,
                "lengthMenu": [50, 100, 200, 500]
            } );

            $('#example tbody').on( 'click', '.btnPrint', function () {
                var table = $('#example').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                console.log(selected_data);

                window.open(base_url+"/reportpo/printpo/data?ponum="+selected_data.ponum, '_blank');
            } ); 

            $('#example tbody').on( 'click', '.btnAttachment', function () {
                var table = $('#example').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                console.log(selected_data);
                // alert('view attachment')
                $('#tbl-attachment-body').html('');
                $.ajax({
                    url: base_url+'/reportpo/getattachment/'+selected_data.ponum,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                    }
                }).done(function(data){
                    // return html;
                    console.log(data)
                    if(data.length > 0){
                        var irows = 0;
                        for(var i = 0; i < data.length; i++){
                            irows += 1;
                            $('#tbl-attachment-body').append(`
                            <tr>
                                <td>`+ irows +`</td>
                                <td>
                                    <a href="<?= BASEURL; ?>/efile/po/`+ data[i].efile +`" target="_blank">`+data[i].efile+`</a>
                                </td>
                            </tr>
                            `);
                        }
                        $('#attachmentModal').modal('show');
                    }else{
                        alert('Attachment not found')
                    }
                    
                });
            } ); 
            
            // Add event listener for opening and closing details
            $('#example tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                
                // console.log(row.data())
                var d = row.data();
                $.ajax({
                    url: base_url+'/reportpo/getpodetail/'+d.ponum,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                    }
                }).done(function(data){
                    // return html;
                    // console.log(data)
                    if ( row.child.isShown() ) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        row.child( format(row.data(), data) ).show();
                        tr.addClass('shown');
                    }
                });
            } );
        })
    </script>