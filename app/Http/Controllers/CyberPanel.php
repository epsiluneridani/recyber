<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CyberPanel extends Controller
{
    
    private $admin_username = "";
    private $admin_password = "";
    private $server_hostname = "";
    private $port = "8090";
    
    public function __construct()
    {
        //parent::__construct();
    }
    
    public function set_server($servername, $username, $password){
        $this->server_hostname = $servername;
        $this->admin_username = $username;
        $this->admin_password = $password;
    }
    
    private function execute($action = 'verifyConn', $data=array(), $is_post = TRUE)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://".$this->server_hostname.":".$this->port."/api/".$action);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $postdata = array(
            'adminUser' => $this->admin_username,
            'adminPass' => $this->admin_password
        );
        
        $postdata = array_merge_recursive($postdata, $data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        $response = curl_exec($ch);
        curl_close ($ch);
    
        return $response;
        
    }
    
    public function add_website($admin, $password, $website, $email, $package = "default")
    {
        $response = $this->execute('createWebsite',
            array(
                'username' => $admin,
                'password' => $password,
                'domainName' => $website,
                'ownerEmail' => $email,
                'packageName' => $package
            )
        );
        
    }
    
    public function remove_website($website){
        $response = $this->execute('deleteWebsite',
            array(
                'domainName' => $website
            )
        );
        
    }
    
    public function change_package($website, $new_package){
        $response = $this->execute('changePackageAPI',
            array(
                'domainName' => $website,
                'packageName' => $new_package
            )
        );
    }
    
    public function set_state($website, $state = "active"){
        $response = $this->execute('changePackageAPI',
            array(
                'domainName' => $website,
                'state' => ($state == 'active' OR $state === TRUE) ? "Activate" : "Suspend" 
            )
        );
        
    }
    
    public function get_user_info($username){
        $response = $this->execute('getUserInfo',
            array(
                'username' => $username
            )
        );
    }
    
    public function set_user_pass($username, $password){
        $response = $this->execute('changeUserPassAPI',
            array(
                'websiteOwner' => $username,
                'ownerPassword' => $password
            )
        );
    }
    
    public function login_as($username, $password){
        $response = $this->execute(
                'loginAPI',
                array(
                    'username' => $username,
                    'password' => $password
                )
            );
    }
    
    public function getDnsRecords($domain){
        $nameserver = dns_get_record($domain, DNS_NS);
        $a_record = dns_get_record($domain, DNS_A);
        dd(array('nameserver'=>$nameserver,'a'=>$a_record));
    }
    
    public function isValid($domain)
    {
        $client_domain = $this->getDnsRecords($domain);
        $server_domain = $this->getDnsRecords($this->server_hostname);
        
        
        
    }
    
}
