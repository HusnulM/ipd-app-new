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
                            </div>

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
            $('#btn-process').on('click', function(){
                window.location.href = base_url+'/reportpo/display/'+$('#strdate').val()+'/'+$('#enddate').val()+'/'+$('#department').val();
            });
        })
    </script>