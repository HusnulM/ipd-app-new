<section class="content">
        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form action="<?= BASEURL; ?>/material/update" method="POST">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <?= $data['menu']; ?>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <button type="submit" id="btn-save" class="btn bg-blue"  data-type="success">
                                    <i class="material-icons">save</i> <span>SAVE</span>
                                </button>

                                <a href="<?= BASEURL; ?>/material" type="button" id="btn-back" class="btn bg-red"  data-type="success">
                                    <i class="material-icons">highlight_off</i> <span>CANCEL</span>
                                </a>
                            </ul>
                        </div>
                        <div class="body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#basic_data_view" data-toggle="tab">
                                        <i class="material-icons">description</i> Basic Data
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="basic_data_view">
                                    <div class="row clearfix">
                                    <br>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="">Part Code / Material Code</label>
                                                    <input type="text" name="kodebrg" id="kodebrg" class="form-control" placeholder="Part Code" autocomplete="off" required="true" readonly value="<?= $data['material']['material']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="">Brand</label>
                                                    <input type="text" name="brand" id="brand" class="form-control" placeholder="Brand" autocomplete="off" value="<?= $data['material']['brand']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="">Description / Model</label>
                                                    <input type="text" name="namabrg" id="namabrg" class="form-control" placeholder="Description" required="true" autocomplete="off" value="<?= $data['material']['matdesc']; ?>">
                                                </div>
                                            </div>
                                        </div>   
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="">Supplier</label>
                                                    <input type="text" name="supplier" id="supplier" class="form-control" placeholder="Supplier" autocomplete="off" value="<?= $data['material']['supplier']; ?>">
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="">Base UOM</label>
                                                    <input type="text" name="satuan" id="satuan" class="form-control" placeholder="Base UOM" required="true" autocomplete="off" value="<?= $data['material']['matunit']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="">Standard Price</label>
                                                    <input type="text" name="stdprice" id="stdprice" class="form-control" placeholder="Standard Price" required="true" autocomplete="off" value="<?= $data['material']['stdprice']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
    </section>

    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        var alt_uom = [];
        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            // $("#stdprice").keydown(function(event){
            //     if(event.keyCode == 190) {
            //         event.preventDefault();
            //         showErrorMessage("Untuk decimal separator gunakan ( , )")
            //         return false;
            //     }
            // });

            var harga  = document.getElementById('stdprice');

            var stdprice = harga.value;
            stdprice = stdprice.toString();
            // stdprice = stdprice.replace('.',',');
            // alert(formatRupiah(stdprice, '2'));
            // alert(stdprice)
            // harga.value = formatRupiah(stdprice, '2');
            harga.value = formatNumber(stdprice);

            harga.addEventListener('keyup', function(e){
                // harga.value = formatRupiah(this.value, '2');
                harga.value = formatNumber(this.value);
            });

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function formatRupiah(angka, prefix){
                var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                split   		  = number_string.split(','),
                sisa     		  = split[0].length % 3,
                rupiah     		  = split[0].substr(0, sisa),
                ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
                
                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? ',' : '';
                    rupiah += separator + ribuan.join(',');
                }
                
                rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }
        });    
    </script>