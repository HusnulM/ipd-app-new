    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <form action="<?= BASEURL; ?>/budgeting/save" method="POST" enctype="multipart/form-data">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    <?= $data['menu']; ?>
                                </h2>

                                <ul class="header-dropdown m-r--5">
                                    <a href="<?= BASEURL; ?>/budgeting" class="btn bg-red">
                                        <i class="material-icons">highlight_off</i> <span>CANCEL</span>
                                    </a>
                                    <button type="submit" class="btn bg-blue">
                                        <i class="material-icons">save</i> <span>SAVE</span>
                                    </button>
                                </ul>
                            </div>
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="department">Department</label>
                                                <select class="form-control show-tick" name="department" id="department" required>
                                                    <option value="">Select Department</option>
                                                    <?php foreach($data['department'] as $row) : ?>
                                                        <option value="<?= $row['id']; ?>"><?= $row['department']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>    
                                    </div>                               

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="period">Budget Period (Year)</label>
                                                <input type="text" class="form-control" name="period" id="period" value="<?= date('Y'); ?>" required>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label for="amount">Budget Amount</label>
                                                <input type="text" class="form-control" name="amount" id="amount" required>
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

    <script>
        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                event.preventDefault();
                return false;
                }
            });

            var amount = document.getElementById('amount');

            amount.addEventListener('keyup', function(e){
                amount.value = formatRupiah(this.value, '');
            });

            function formatRupiah(angka, prefix){
                var number_string = angka.toString().replace(/[^.\d]/g, '').toString(),
                split   		  = number_string.split('.'),
                sisa     		  = split[0].length % 3,
                rupiah     		  = split[0].substr(0, sisa),
                ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);
            
                if(ribuan){
                    separator = sisa ? ',' : '';
                    rupiah += separator + ribuan.join(',');
                }
            
                rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }
        });
    </script>