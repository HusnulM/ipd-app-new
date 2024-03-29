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
                                                <label for="strdate">Issuing Date</label>
                                                <input type="date" name="strdate" id="strdate" class="datepicker form-control" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>    
                                    </div>  
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="enddate">to Date</label>
                                                <input type="date" name="enddate" id="enddate" class="datepicker form-control" value="<?php echo date('Y-m-d'); ?>">
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
                var strdate = $('#strdate').val();
                var enddate = $('#enddate').val();

                window.location.href = base_url+'/stockreport/issuingstockview/'+strdate+'/'+enddate;
            })
        })
    </script>