<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dns/check/{domain?}', function($domain=""){
    abort(404);
    if(!empty($domain))
    {
        $nameserver = dns_get_record($domain, DNS_NS);
        $a_record = dns_get_record($domain, DNS_A);
        dd(array('Nameservers'=>$nameserver,'A Records'=>$a_record));
    } else {
        dd(array());
    }
});

Route::match(['GET','POST'],'/setup/domain', 'CyberPanel@domain_setup');

Route::get('/dns/{domain?}', 'CyberPanel@isValidDomain');

Route::get('/test', function(){
    abort(404);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://zbyte.dns-cloud.net:8090/api/cyberPanelVersion");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // In real life you should use something like:
    curl_setopt($ch, CURLOPT_POSTFIELDS, 
             //http_build_query(
             json_encode(
                array(
                     'adminUser' => 'admin',
                     'adminPass' => 'HelloWorld#321', 
                     'username' => 'admin',
                     'password' => 'HelloWorld#321'
                    //  'domainName' => 'localhost.net',
                    //  'ownerEmail' => 'mydomain@localhost.net',
                    //  'packageName' => 'default',
                    //  'websiteOwner' => 'mydomainln',
                    //  'ownerPassword' => 'somethingGreatAndPowerful1990'
                     )
                )
            );
    //Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close ($ch);
    dd($response);
    //return $response;
    //dd($response);
    
});

Route::get('/home', 'HomeController@index')->name('home');
