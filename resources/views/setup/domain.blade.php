@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Setup</h1>
            <div class="card">
                <div class="card-header">Domain</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>Please point the ns record for your domain to:</p>
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">NS1</div>
                                </div>
                                <input type="text" class="form-control" value="ns1.zbyte.dns-cloud.net" readonly/>
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">NS2</div>
                                </div>
                                <input type="text" class="form-control" value="ns2.zbyte.dns-cloud.net" readonly/>
                            </div>
                            <ul class="d-none">
                                <li>ns1.zbyte.dns-cloud.net</li>
                                <li>ns2.zbyte.dns-cloud.net</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="/setup/domain">
                                @csrf
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class=" fa fa-globe"></i></span>
                                    </div>
                                    <input type="text" name="domain" class="form-control @error('domain') is-invalid @enderror"/>
                                    @error('domain')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-primary btn-lg mt-3" type="submit">Check</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection