<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
        <form action="<?= BASEURL; ?>/requestslip/saveprice" method="POST" enctype="multipart/form-data">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?> | <b><?= $data['reqheader']['requestnum']; ?></b>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="note">Note</label>
                                        <input type="text" name="reqnote" id="note" class="form-control" placeholder="Note" value="<?= $data['reqheader']['request_note']; ?>" readonly>
                                        <input type="hidden" name="requestnum" value="<?= $data['reqheader']['requestnum']; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="regdate">Request Date</label>
                                        <input type="date" name="reqdate" id="reqdate" class="datepicker form-control" value="<?= $data['reqheader']['request_date']; ?>" readonly>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="requestor">Requestor</label>
                                        <input type="text" class="form-control" name="requestor" id="requestor" value="<?= $data['reqheader']['request_by']; ?>" readonly>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="department">Department</label>
                                        <input type="text" class="form-control" name="department" id="department" value="<?= $data['reqheader']['department']; ?>" readonly>
                                    </div>
                                </div>    
                            </div>
                        </div>                            
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2>
                            Request Slip Items
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Part Number</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <!-- <th>Price</th> -->
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-pr-body" class="mainbodynpo">
                                        <?php $count = 0; ?>
                                        <?php foreach($data['reqdetails'] as $row): ?>
                                            <?php $count += 1; ?>
                                            <tr counter="`+ count +`" id="tr`+ count +`">
                                                <td class="nurut"> 
                                                    <?= $count; ?>
                                                    <input type="hidden" name="itm_no[]" value="<?= $row['request_item']; ?>" />
                                                </td>
                                                <td style="width:300px;"> 
                                                    <a href="#" data-imagepath="<?= BASEURL; ?>/images/material-images/<?= $row['image'] ?>" data-partcode="<?= $row['material'] ?>`" class="img-preview"><?= $row['material'] ?> - <?= $row['matdesc'] ?></a>

                                                    <input type="hidden" name="itm_material[]"class="form-control materialCode" required="true" value="<?= $row['material'] ?>" readonly/>
                                                </td>
                                                <td style="text-align:right;"> 
                                                    <?php if (strpos($row['quantity'], '.000') !== false) {
                                                        echo number_format($row['quantity'], 0);
                                                    }else{
                                                        echo number_format($row['quantity'], 3);
                                                    } ?>   
                                                    <input type="hidden" name="itm_qty[]" class="form-control inputNumber" style="text-align:right;" required="true" autocomplete="off" value="<?= $row['quantity'] ?>"/>
                                                </td>
                                                <td> 
                                                    <?= $row['unit']; ?>
                                                    <input type="hidden" name="itm_unit[]" class="form-control" required="true" value="<?= $row['unit'] ?>" readonly/>
                                                </td>
                                                <!-- <td> 
                                                    <input type="text" name="itm_price[]" class="form-control" style="text-align:right;" required="true" value="<?= $row['unit_price']; ?>"/>
                                                </td> -->
                                                
                                                <!-- <td>
                                                    <button type="button" class="btn btn-danger btn-sm removePO hideComponent" counter="`+count+`" id="btnRemove`+count+`" data-count="`+ count +`">Remove</button>
                                                </td> -->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <ul class="pull-right">    
                                    <a href="<?= BASEURL; ?>/requestslip/requestlist" class="btn bg-red">
                                        <i class="material-icons">highlight_off</i> <span>CANCEL</span>
                                    </a>
                                    <button type="submit" class="btn bg-blue">
                                        <i class="material-icons">save</i> <span>SUBMIT PRICE</span>
                                    </button>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="errorModalText">Info</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-responsive" id="tbl-err-msg" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-err-body">
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

    <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="imagePreviewModalLabel">Part Image</h4>
                </div>
                <div class="modal-body">
                    <div class="box" style="text-align:center;">
                        <img src="#" alt="image" id="materil-image" style="width:350px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="zoomin()">
                        <i class="material-icons">zoom_in</i>
                    </button>
                    
                    <button type="button" class="btn btn-primary" onclick="zoomout()"> 
                        <i class="material-icons">zoom_out</i>
                    </button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>            
    </div>
</section>
    

    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>

        function zoomin() {
            var GFG = document.getElementById("materil-image");
            var currWidth = GFG.clientWidth;
            if(currWidth <= 750){
                GFG.style.width = (currWidth + 100) + "px";
            }
        }
          
        function zoomout() {
            var GFG = document.getElementById("materil-image");
            var currWidth = GFG.clientWidth;
            GFG.style.width = (currWidth - 100) + "px";
        }

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                event.preventDefault();
                return false;
                }
            });
        });
        
        $(function(){
            let detail_order_beli = [];
            var kodebrg           = '';
            var namabrg           = '';
            var action            = '';
            var imgupload         = [];
            var count = 0;

            function materialExists(material) {
                return detail_order_beli.some(function(el) {
                    return el.material === material;
                }); 
            }

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            $('.img-preview').on('click', function(){
                var _data = $(this).data();
                var imgContent = document.getElementById("materil-image");
                imgContent.style.width = '350px';
                $('#imagePreviewModalLabel').html('Part '+ _data.partcode + ' Image');
                $('#materil-image').attr('src', '');
                $('#materil-image').attr('src', _data.imagepath);
                $('#imagePreviewModal').modal('show');
            });

            function renumberRows() {
                $(".mainbodynpo > tr").each(function(i, v) {
                    $(this).find(".nurut").text(i + 1);
                });
            }            

            $('#form-pr-data').on('submit', function(event){
                event.preventDefault();
                
                var formData = new FormData(this);
                console.log($(this).serialize())
                    $.ajax({
                        url:base_url+'/requestslip/save',
                        method:'post',
                        data:formData,
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend:function(){
                            showBasicMessage();
                        },
                        success:function(data)
                        {
                        	console.log(data);
                        },
                        error:function(err){
                            showErrorMessage(JSON.stringify(err))
                        }
                    }).done(function(result){
                        console.log(result)
                        if(result.msgtype === "1"){
                            showSuccessMessage(result.message +' : '+ result.reqnum)
                        }else{
                            showErrorMessage(JSON.stringify(result))
                        }
                    })
            });

            function removeitem(index){
                detail_order_beli.splice(index, 1);
                // setpritem();
            }

            function formatRupiah(angka, prefix){
                var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                split   		  = number_string.split('.'),
                sisa     		  = split[0].length % 3,
                rupiah     		  = split[0].substr(0, sisa),
                ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
            
                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? ',' : '';
                    rupiah += separator + ribuan.join('.');
                }
            
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }

            function showBasicMessage() {
                swal({title:"Loading...", text:"Please wait...", showConfirmButton: false});
            }

            function showSuccessMessage(message) {
                // swal("Success", message, "success");
                swal({title: "Success!", text: message, type: "success"},
                    function(){ 
                        window.location.href = base_url+'/requestslip';
                    }
                );
            }

            function showErrorMessage(message){
                swal("Error", message, "error");
            }
        })

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }        
    </script>
    