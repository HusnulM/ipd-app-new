<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Report Purchase Request Order
                        </h2>
                    </div>
                    <div class="body">
                        <form>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="reqdate1">PO Date</label>
                                            <input type="date" name="reqdate1" id="strdate" class="datepicker form-control" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>    
                                </div>

                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="reqdate1">-</label>
                                            <input type="date" name="reqdate1" id="enddate" class="datepicker form-control" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>    
                                </div>                                    
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6">
                                    <label for="poselect"></label>
                                    <select name="poselect" id="poselect" class="form-control">
                                        <option value="ALL">All</option>
                                        <option value="O">Open PO Only</option>
                                        <option value="R">Already Receipt Only</option>
                                    </select>
                                </div>
                                <!-- <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="checkbox" id="basic_checkbox_2" class="filled-in form-control"/>
                                        <label for="basic_checkbox_2">Open PO Only</label>
                                    </div>  
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <input type="checkbox" id="basic_checkbox_3" class="filled-in form-control"/>
                                        <label for="basic_checkbox_3">Already Receipt Only</label>
                                    </div>  
                                </div> -->
                            </div>
                                
                            <!-- <div class="row clearfix">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="department">Department</label>
                                            <select name="department" id="department" class="form-control">
                                                <option value="0">All Department</option>
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
                            </div> -->

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <button type="button" id="btn-process" class="btn btn-primary"  data-type="success">Show Data</button>
                                    </div>    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>
    
    
    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        $(function(){
            // var _openpo = 'N';
            
            // $('#basic_checkbox_2').on('change', function(){
            //     if(_openpo === 'N'){
            //         _openpo = 'Y'
            //     }else{
            //         _openpo = 'N'
            //     }
            // });

            $('#btn-process').on('click', function(){
                window.location.href = base_url+'/reportpo/display/'+$('#strdate').val()+'/'+$('#enddate').val()+'/'+$('#poselect').val();
            });
        })
    </script>