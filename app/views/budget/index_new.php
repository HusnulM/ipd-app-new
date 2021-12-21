    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card" id="div-po-item">
                        <!-- PO Item -->
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
                            <ul class="header-dropdown m-r--5">                                
                                <a href="<?= BASEURL; ?>/budgeting/create" class="btn btn-success waves-effect pull-right">ADD Budget</a>
                            </ul>
                        </div>
                        <div class="body">                                
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered table-striped table-hover" style="width:100%;font-size: 12px; font-weight: bold;">
                                    <thead>
                                        <th>No</th>
                                        <th>Department</th>
                                        <th>Budget Period (Year)</th>
                                        <th style="text-align:right;">Budget Allocated</th>
                                        <th style="text-align:right;">Issuing Budget</th>
                                        <th style="text-align:right;">Balance</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        $(function(){
            function format ( d, results ) {
                console.log(results)
                var html = '';
                html = `<table class="table">
                       <thead>
                            <tr style="">
                                <th style="text-align:right;background-color:green;color:white;">Allocation</th>
                                <th colspan="4" style="text-align:right;background-color:green;color:white;">Total Issued</th>
                                <th></th>
                            </tr>
                       </thead>
                       <tbody>`;
                for(var i = 0; i < results.length; i++){
                    html +=`
                       <tr>
                            <td style="text-align:right;"> `+ results[i].description +` </td>
                            <td colspan="4" style="text-align:right;"> `+ formatNumber(results[i].amount) +` </td>
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
                "ajax": base_url+"/budgeting/readBudgeting",
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "deptname" },
                    { "data": "budget_period" },
                    { "data": "allocated", "className" : "text-right",
                        render: function(data, type, row){                            
                            return formatNumber(data);
                        }
                    },
                    { "data": "issuing_amount", "className" : "text-right",
                        render: function(data, type, row){                            
                            return formatNumber(data);
                        }
                    },
                    { "data": "balance", "className" : "text-right",
                        render: function(data, type, row){                            
                            return formatNumber(data);
                        }
                    }
                ],
                "order": [[1, 'asc']],
                "pageLength": 50,
                "lengthMenu": [50, 100, 200, 500]
            } );
            
            // Add event listener for opening and closing details
            $('#example tbody').on('click', 'td.details-control', function () {
                var tabledata = $('#example').DataTable();
                var tr = $(this).closest('tr');
                var row = tabledata.row( tr );
                var d = row.data();

                console.log(d)
                $.ajax({
                    url: base_url+'/budgeting/readBudgetDetail/'+d.department+'/'+d.budget_period,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){
                    }
                }).done(function(data){
                    if ( row.child.isShown() ) {
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        row.child( format(row.data(), data) ).show();
                        tr.addClass('shown');
                    }
                });
            } );
        })
    </script>