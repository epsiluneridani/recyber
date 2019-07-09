@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-5">
            <h1>Websites</h1>
            <div class="card mb-2">
                <div class="card-header">ZBDNS.CF</div>

                <div class="card-body">
                    
                    <div class="row mt-3 mb-3">
                        <div class="col-md-3 col-sm-6 text-center">
                            <a href="https://zbyte.dns-cloud.net:8090/websites/zbyte.dns-cloud.net" class="btn btn-link text-dark" target="_blank"><i class="fas fa-tools fa-2x"></i><br/>Control Panel</a>
                        </div>
                        <div class="col-md-3 col-sm-6  text-center">
                            <a href="https://zbyte.dns-cloud.net:8090/filemanager/zbyte.dns-cloud.net" class="btn btn-link text-dark" target="_blank"><i class="fas fa-file-code fa-2x"></i><br/>File Manager</a>
                        </div>
                        <div class="col-md-3 col-sm-6 text-center d-none">
                            <a href="https://zbyte.dns-cloud.net:8090/phpmyadmin/index.php" class="btn btn-link text-dark" target="_blank"><i class="fas fa-database fa-2x"></i><br/>Database</a>
                        </div>
                        <div class="col-md-3 col-sm-6 text-center d-none">
                            <a href="https://zbyte.dns-cloud.net:8090/rainloop/index.php" class="btn btn-link text-dark" target="_blank"><i class="fas fa-envelope fa-2x"></i><br/>Email</a>
                        </div>
                        <div class="col-md-3 col-sm-6 text-center d-none">
                            <a href="/changepassword" class="btn btn-link text-dark"><i class="fas fa-unlock-alt fa-2x"></i><br/>Change Password</a>
                        </div>
                        <div class="col-md-3 col-sm-6 text-center">
                            <a href="/removewebsite" class="btn  btn-link text-dark"><i class="fas fa-times-circle fa-2x text-danger"></i><br/>Remove Website</a>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h2>Cyberpanel</h2>
            <div class="card">
                <div class="card-body">
                    Username: cb_zbdnsc<br/>
                    <a href="/changepassword">Change Password</a><br/>
                    Nameservers:<br/>
                    <ul><li>ns1.zbyte.dns-cloud.net</li><li>ns2.zbyte.dns-cloud.net</li></ul>
                </div>
            </div>
            <h2 class="mt-2">Package</h2>
            <div class="card">
                <div class="card-header">PHC-LITE</div>
                <div class="card-body">
                    Website<br/>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%;">1/1</div>
                    </div><br/>
                    DNS<br/>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%;">1/1</div>
                    </div><br/>
                    Database<br/>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 33%;">1/3</div>
                    </div><br/>
                    FTP<br/>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 33%;">1/3</div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/upgrade">Upgrade</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
