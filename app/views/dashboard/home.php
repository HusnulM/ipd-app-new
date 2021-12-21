    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="card">
                    <div class="header">
                        <h2>DASHBOARD</h2>    
                    </div>            
                    <div class="body" style="height:450px;">
                        <a href="#" class="tile-default">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="info-box bg-orange hover-expand-effect">
                                    <div class="icon">
                                        <i class="material-icons">note_add</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">
                                            Open Purchase Request
                                        </div>
                                        <div style="font-size:20px;font-weight:bold;text-align:right;">
                                            <?= $data['totalpr']['total']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
        
                        <a href="#" class="tile-default">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="info-box bg-cyan hover-expand-effect">
                                    <div class="icon">
                                        <i class="material-icons">shopping_cart</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">Issuing Budget</div>
                                        <div style="font-size:20px;font-weight:bold;text-align:right;">
                                            <?= number_format($data['budget']['issuing_amount'], 2, '.', ','); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
        
                        <a href="#" class="tile-default">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="info-box bg-light-green hover-expand-effect">
                                    <div class="icon">
                                        <i class="material-icons">receipt</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">Balance</div>
                                        <div style="font-size:20px;font-weight:bold;">
                                            <?= number_format(($data['budget']['amount']+$data['budget']['issuing_amount'])-$data['budget']['issuing_amount'], 2, '.', ','); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a> 
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function(){
            $('div').css('cursor', 'pointer');
        })
    </script>