
    <div class="wraper">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 mx-auto">
                    <section id="login-body" class="pt-3">
                        <div class="card border-0 shadow pt-3">
                            <div class="card-header bg-transparent border-bottom-0 pb-0 text-center">
                                <div class="mt-3">
                                    <span class="card-info text-center">Arahkan kamera ke QR Code</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
                                    <div class="col-sm-12">
                                        <video id="preview" class="p-1 border" style="width:100%;"></video>
                                    </div>
                                    <script type="text/javascript">
                                        var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
                                        scanner.addListener('scan', function(content){
                                            window.location.href = "<?= base_url('absensi/absen/'.$keterangan.'/') ?>"+content;
                                        });
                                        Instascan.Camera.getCameras().then(function (cameras){
                                            if(cameras.length>0){
                                                scanner.start(cameras[0]);
                                                $('[name="options"]').on('change', function(){
                                                    if($(this).val()==1){
                                                        if(cameras[0]!=""){
                                                            scanner.start(cameras[0]);
                                                        }else{
                                                            alert('No Front camera found!');
                                                        }
                                                    }else if($(this).val()==2){
                                                        if(cameras[1]!=""){
                                                            scanner.start(cameras[1]);
                                                        }else{
                                                            alert('No Back camera found!');
                                                        }
                                                    }
                                                });
                                            }else{
                                                console.error('No cameras found.');
                                                alert('No cameras found.');
                                            }
                                        }).catch(function(e){
                                            console.error(e);
                                            alert(e);
                                        });
                                    </script>
                                    <div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">
                                      <label class="btn btn-primary active">
                                        <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
                                      </label>
                                      <label class="btn btn-secondary">
                                        <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
                                      </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
