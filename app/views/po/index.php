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
                                Purchase Order
                            </h2>
							<?php if($_SESSION['usr']['userlevel'] === 'Staff') : ?>
                            <ul class="header-dropdown m-r--5">                                
							<a href="<?= BASEURL; ?>/po/prlist" class="btn btn-success waves-effect pull-right">Create Purchase Order</a>
							</ul>
                            <?php endif; ?>
                            <ul class="header-dropdown m-r--5">                                
							<a href="<?= BASEURL; ?>/po/create" class="btn btn-success waves-effect pull-right">Create Purchase Order</a>
							</ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. PO</th>
                                            <th>Type PO</th>
                                            <th>Tanggal Order</th>
                                            <th>Vendor</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach ($data['podata'] as $podata) : ?>
                                            <?php $no++; ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $podata['ponum']; ?></td>
                                                <td>
                                                    <?php if($podata['potype'] === "PO01") : ?>
                                                        PO Stock
                                                    <?php else: ?>
                                                        PO Lokal
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $podata['podat']; ?></td>
                                                <td><?= $podata['namavendor']; ?></td>
                                                <td><?= $podata['note']; ?></td>
                                                <td>Open</td>
                                                <td>
                                                    <a href="<?= BASEURL; ?>/po/detail/data?ponum=<?= $podata['ponum']; ?>" type="button" class="btn btn-success">Detail</a>
                                                    <a href="<?= BASEURL; ?>/po/delete/data?ponum=<?= $podata['ponum']; ?>" class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let usertype = "<?= $_SESSION['usr']['userlevel']; ?>";
        $('#polist').datagrid({
            view: detailview,
            detailFormatter:function(index,row){
                return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
            },
                onExpandRow: function(index,row){
                $('#ddv-'+index).panel({
                            border:true,
                            cache:false,
                            href:base_url + '/app/views/po/detailpo.php?ponum='+row.ponum,

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
                    enableCellEditing:true,
                    pagination:true,
                    pageList:[10,20,50,100,150,200],
                    idField:'ponum',
                    url:base_url+'/po/listopenpo', 
                    columns:[[
                        {field:'ponum',title:'PO Num',width:90},
                        {field:'keterangan',title:'Note',width:150,editor:'text',nowrap:true},
                        // {field:'project',title:'Project',width:200,editor:'text',nowrap:true},
                        {field:'namasup',title:'Vendor',width:200,editor:'text',nowrap:true},
                        // {field:'createdon',title:'Created Date',width:80,editor:'text'},
                        {field:'tgl_order',title:'Order Date',width:100,editor:'text'},
                        {field:'apstats',title:'Aprooval Status',width:100,editor:'text',
                            styler: function(value,row,index){
                                if (row.approvestat == "1") {
                                    return 'background-color:yellow;color:black;font-weight: bold;';
                                }else if (row.approvestat == "2") {
                                    return 'background-color:#3ea5ef;color:white;font-weight: bold;';
                                }else if (row.approvestat == "3") {
                                    return 'background-color:red;color:white;font-weight: bold;';
                                }else if (row.approvestat == "4") {
                                    return 'background-color:#c57530;color:white;font-weight: bold;';
                                }else if (row.approvestat == "5") {
                                    return 'background-color:#c57530;color:white;font-weight: bold;';
                                }else{
                                    return 'background-color:yellow;color:black;font-weight: bold;';
                                }
                            }
                        },
                        {field:'Action',title:'',width:60,align:'center',
                            formatter:function(value,row,index){
                                if(usertype === 'Admin'){
                                    var a = '<a href="javascript:void(0)" onclick="approve(this)">Approve</a>';
                                }else{
                                    var a = '<a href="javascript:void(0)" onclick="edit(this)">Edit</a>';
                                }
                                
                                return a;
                            },
                            styler: function(value,row,index){
                                return 'background-color:white;color:blue;';
                            }
                        },
                        {field:'Action2',title:'',width:60,align:'center',
                            formatter:function(value,row,index){
                                if(usertype === 'Admin'){
                                    var a = '<a href="javascript:void(0)" onclick="reject(this)">Reject</a>';
                                }else{
                                    var a = '<a href="javascript:void(0)" onclick="hapus(this)">Delete</a>';
                                }
                                
                                return a;
                            },
                            styler: function(value,row,index){
                                return 'background-color:white;color:blue;';
                            }
                        }
                    ]],				
            });

            function edit(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#polist').datagrid('getRows')[x].ponum;
                window.location.href = base_url+"/po/edit/"+v;
            }

            function hapus(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#polist').datagrid('getRows')[x].ponum;
                window.location.href = base_url+"/po/delete/"+v;
            }

            function approve(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#polist').datagrid('getRows')[x].ponum;
                window.location.href = base_url+"/po/approvepo/"+v;
            }

            function reject(target){
                var tr = $(target).closest('tr.datagrid-row');
                var x  = (tr.attr('datagrid-row-index'));
                var v  = $('#polist').datagrid('getRows')[x].ponum;
                window.location.href = base_url+"/po/rejectpo/"+v;
            }
    </script>