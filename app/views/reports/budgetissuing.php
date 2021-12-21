    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
                        </div>
                        <div class="body">
                            <form>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="reqdate1">Department</label>
                                                <select class="form-control show-tick" name="department" id="department" required>
                                                    <option value="ALL">All Departments</option>
                                                    <?php foreach($data['department'] as $row) : ?>
                                                        <option value="<?= $row['id']; ?>"><?= $row['department']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="pmonth">Period (Month)</label>
                                                <select name="pmonth" id="pmonth" class="form-control">
                                                    <option value="ALL">All Period (Month)</option>
                                                    <option value="01">January</option>
                                                    <option value="02">February</option>
                                                    <option value="03">March</option>
                                                    <option value="04">April</option>
                                                    <option value="05">May</option>
                                                    <option value="06">June</option>
                                                    <option value="07">July</option>
                                                    <option value="08">August</option>
                                                    <option value="09">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                                <!-- <input type="text" name="pmonth" id="pmonth" class="datepicker form-control" value="<?php echo date('m'); ?>"> -->
                                            </div>
                                        </div>    
                                    </div>    
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="pyear">Period (Year)</label>
                                                <input type="text" name="pyear" id="pyear" class="datepicker form-control" value="<?php echo date('Y'); ?>">
                                            </div>
                                        </div>    
                                    </div>    
                                </div>
                                

                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
                var deptid = $('#department').val();
                var month  = $('#pmonth').val();
                var year   = $('#pyear').val();

                window.location.href = base_url+'/reports/budgetissuingview/'+deptid+'/'+month+'/'+year
            })
        })
    </script>