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
                            <a href="<?= BASEURL; ?>/reportslip/exportreportgrpo/<?= $data['strdate']; ?>/<?= $data['enddate']; ?>/<?= $data['dept']; ?>" target="_blank" class="btn bg-blue">
                               <i class="material-icons">cloud_download</i> EXPORT DATA
                            </a>

                            <a href="<?= BASEURL; ?>/reportslip" class="btn bg-blue">
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
                                        <th>Slip Number</th>
                                        <th>Slip Date</th>
                                        <th>Department</th>
                                        <th>Note</th>
                                        <th>Reject Note</th>
                                        <th>Request By</th>
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
            var departm = "<?= $data['dept']; ?>";

            function format ( d, results ) {
                console.log(results)
                var html = '';
                html = `<table class="table table-bordered table-striped" style="padding-left:50px;width:100%;">
                       <thead>
                            <th>Slip Item</th>
                            <th>Material</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                       </thead>
                       <tbody>`;
                for(var i = 0; i < results.length; i++){
                    var qty = '';
                    qty = results[i].quantity;
                    qty = qty.replaceAll('.000','');
                    // qty = qty.replaceAll('.',',');
                    html +=`
                       <tr>
                            <td style="text-align:right;"> `+ results[i].request_item +` </td> 
                            <td> `+ results[i].material +` </td>                            
                            <td> `+ results[i].matdesc +` </td>
                            <td style="text-align:right;"> `+ formatNumber(qty) +` </td>
                            <td> `+ results[i].unit +` </td>
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
                "ajax": base_url+"/reportslip/getheaderdata/"+strdate+"/"+enddate+"/"+departm,
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    // {"defaultContent": "<button class='btn btn-primary btn-xs'>Print</button>"},
                    {"data": "requestnum",
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
                    { "data": "requestnum" },
                    { "data": "request_date" },
                    { "data": "department" },
                    { "data": "request_note" },
                    { "data": "reject_note" },
                    { "data": "request_by" },
                    // { "data": "request_status"}
                    { "data": "request_status", 
                        "render": function (data, type, row) {
                            console.log(data)
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

                window.open(base_url+"/reportslip/printslip/data?reqnum="+selected_data.requestnum, '_blank');
            } ); 

            $('#example tbody').on( 'click', '.btnAttachment', function () {
                var table = $('#example').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                console.log(selected_data);
                // alert('view attachment')
                $('#tbl-attachment-body').html('');
                $.ajax({
                    url: base_url+'/reportslip/getattachment/'+selected_data.requestnum,
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
                                    <a href="<?= BASEURL; ?>/efile/request-slip/`+ data[i].efile +`" target="_blank">`+data[i].efile+`</a>
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
                    url: base_url+'/reportslip/getdetaildata/'+d.requestnum,
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