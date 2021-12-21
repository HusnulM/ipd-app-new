<section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card" id="div-po-item">
                        <div class="header">
                            <h2>
                                Report Purchase Request
                            </h2>
                            
                            <ul class="header-dropdown m-r--5">                                
							<button id="btn-print" type="button" class="btn btn-primary">Print PR</button>
                            <!-- <button id="btn-excel" type="button" class="btn btn-primary">Export to Excel</button> -->
                            <a href="<?= BASEURL; ?>/reports/reportpr" type="button" class="btn btn-primary">Back</a>
							</ul>
                        </div>
                        <div class="body">                                
                            <div class="table-responsive">                                
                                <table id="prlist"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
    
    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>

        var strdate = "<?= $data['strdate']; ?>";
        var enddate = "<?= $data['enddate']; ?>";
        var status  = "<?= $data['status']; ?>";
        var deptid  = "<?= $data['deptid']; ?>";

        $('#prlist').datagrid({
            view: detailview,
            detailFormatter:function(index,row){
                return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
            },
                onExpandRow: function(index,row){
                $('#ddv-'+index).panel({
                            border:true,
                            cache:false,
                            href:base_url + '/app/views/pr/detailpr.php?prnum='+row.prnum,

                            onLoad:function(){
                                $('#prlist').datagrid('fixDetailRowHeight',index);
                            }
                        });
                        $('#prlist').datagrid('fixDetailRowHeight',index);
                    },
                    width:'100%',
                    height:400,
                    singleSelect:true,
                    resizable:true,
                    fitColumns:true,
                    pagination:true,
                    pageList:[10,20,50,100,150,200],
                    idField:'prnum',
                    url:base_url+'/reports/laporanprdata/'+strdate+'/'+enddate+'/'+deptid+'/'+status, 
                    columns:[[
                        {field:'prnum',title:'PR Num',width:60},
                        {field:'prtypename',title:'PR Type',width:100},
                        {field:'note',title:'Note',width:150,editor:'text',nowrap:true},
                        {field:'department',title:'Department',width:100,editor:'text'},
                        {field:'prdate',title:'PR Date',width:100,editor:'text'},
                        {field:'createdon',title:'Created On',width:80,editor:'text'},
                        {field:'createdby',title:'Created By',width:80,editor:'text'},
                    ]],				
            });

        $(function(){
            $('#btn-print').on('click', function(){
                // window.location.href = base_url+'/pr/print/'
                var row = $('#prlist').datagrid('getSelected');
                if (row){
                    // window.location.href = mainurl+"/purchasing/printPR/"+row.prnum;
                    window.open(base_url+"/pr/printpr/"+row.prnum, '_blank');
                }else{
                    showErrorMessage('No data selected');
                }
            });

            $('#btn-excel').on('click', function(){
                // window.location.href = base_url+'/pr/print/'
                var row = $('#prlist').datagrid('getSelected');
                if (row){
                    window.open(base_url+"/pr/export_excel/"+row.prnum, '_blank');
                }else{
                    showErrorMessage('No data selected');
                }
            });

            function showSuccessMessage(message) {
                swal({title: "Berhasil", text: message, type: "success"},
                    function(){ 
                        window.location.href = base_url+'/pr';
                    }
                );
            }

            function showErrorMessage(message){
                swal("Error", message, "error");
            }
        })
    </script>