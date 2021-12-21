<section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="msg-alert">
                        <?php
                            Flasher::msgInfo();
                        ?>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>
                                Purchase Request
                            </h2>
							
                            <?php if($_SESSION['usr']['userlevel'] === 'Staff') : ?>
                            <ul class="header-dropdown m-r--5">                                
							<a href="<?= BASEURL; ?>/pr/create" class="btn btn-success waves-effect pull-right">Create Purchase Request</a>
							</ul>
                            <?php endif; ?>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="prlist"></table>
                                <!-- <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Request</th>
                                            <th>Tanggal Request</th>
                                            <th>Keterangan</th>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach ($data['prdata'] as $pr) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $pr['prnum']; ?></td>
                                                <td><?= $pr['tglrequest']; ?></td>
                                                <td><?= $pr['note']; ?></td>
                                                <td><?= $pr['namaproject']; ?></td>
                                                <td>Open</td>
                                                <td>
                                                    <a href="<?= BASEURL; ?>/pr/detail/<?= $pr['prnum']; ?>/2" type="button" class="btn btn-success">Detail</a>
                                                    <?php if($_SESSION['usr']['userlevel'] === 'Staff') : ?>
                                                        <a href="<?= BASEURL; ?>/pr/edit/<?= $pr['prnum']; ?>" type="button" class="btn btn-primary">Edit</a>
                                                        <a href="<?= BASEURL; ?>/pr/delete/<?= $pr['prnum']; ?>" type="button" class="btn btn-danger">Hapus</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>

    <script>
        let usertype = "<?= $_SESSION['usr']['userlevel']; ?>";
        $('#prlist').datagrid({
            view: detailview,
            detailFormatter:function(index,row){
                return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
            },
                onExpandRow: function(index,row){
                $('#ddv-'+index).panel({
                            border:true,
                            cache:false,
                            href:base_url + '/app/views/pr/detailoutstanding.php?prnum='+row.prnum,

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
                    url:base_url+'/pr/getoutstandingpr', 
                    columns:[[
                        {field:'prnum',title:'PR Num',width:90},
                        {field:'note',title:'Keterangan',width:150,editor:'text',nowrap:true},
                        {field:'namaproject',title:'Project',width:200,editor:'text'},
                        {field:'createdby',title:'Created By',width:80,editor:'text'},
                        {field:'createdon',title:'Created Date',width:80,editor:'text'},
                        {field:'tglrequest',title:'Required Date',width:100,editor:'text'},
                        {field:'approvestat',title:'Aprooval Status',width:100,editor:'text',
                            styler: function(value,row,index){
                                if (row.status == "1") {
                                    return 'background-color:yellow;color:black;font-weight: bold;';
                                }else if (row.status == "2") {
                                    return 'background-color:green;color:white;font-weight: bold;';
                                }else if (row.status == "3") {
                                    return 'background-color:#red;color:white;font-weight: bold;';
                                }else if (row.status == "4") {
                                    return 'background-color:#c57530;color:white;font-weight: bold;';
                                }else if (row.status == "5") {
                                    return 'background-color:#c57530;color:white;font-weight: bold;';
                                }else{
                                    return 'background-color:yellow;color:black;font-weight: bold;';
                                }
                            }
                        },
                        {field:'Action3',title:'',width:65,align:'center',
                            formatter:function(value,row,index){
                                var a = '<a href="javascript:void(0)" onclick="closepr(this)">Close PR</a>';                                
                                return a;
                            },
                            styler: function(value,row,index){
                                return 'background-color:white;color:blue;';
                            }
                        }
                    ]],				
            });

            function closepr(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#prlist').datagrid('getRows')[x].prnum;
                window.location.href = base_url+"/pr/close/"+v;
            }

            function edit(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#prlist').datagrid('getRows')[x].prnum;
                window.location.href = base_url+"/pr/edit/"+v;
            }

            function hapus(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#prlist').datagrid('getRows')[x].prnum;
                window.location.href = base_url+"/pr/delete/"+v;
            }

            function approve(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#prlist').datagrid('getRows')[x].prnum;
                window.location.href = base_url+"/pr/approvepr/"+v;
            }

            function reject(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#prlist').datagrid('getRows')[x].prnum;
                window.location.href = base_url+"/pr/rejectpr/"+v;
            }
    </script>