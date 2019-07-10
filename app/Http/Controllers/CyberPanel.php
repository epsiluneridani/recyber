<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RemotelyLiving\PHPDNS\Resolvers;

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
    
    public function getDnsRecords($domain, $type = "A", $first = TRUE){
        $googleResolver = new Resolvers\GoogleDNS();
        $cloudflareResolver = new Resolvers\CloudFlare();
        $localResolver = new Resolvers\LocalSystem();
        $chainResolver = new Resolvers\Chain($cloudflareResolver, $googleResolver, $localResolver);
        $records = $chainResolver->randomly()->getRecords($domain,$type);
        
        if($records->count() <= 0) {
            return NULL;
        }
        
        if($first){
            return $records->pickFirst()->toArray();
        } else {
            $formatted = array();
            foreach($records as $record){
                $formatted[] = $record->toArray();
            }
            return $formatted;
            
            //dd($formatted); //->toArray());
            //return 
            
            
        }
        
        
        
        //dd($records->find(1));
        //dd($records);
        //$nameserver = dns_get_record($domain, DNS_NS);
        //$a_record = dns_get_record($domain, DNS_A);
        //dd(array('nameserver'=>$nameserver,'a'=>$a_record));
    }
    
    public function isValidNSRecord($domain, $strict = TRUE)
    {
        $client_domains = $this->getDnsRecords($domain, "NS", FALSE);
        $server_domains = $this->getDnsRecords($this->server_hostname, "NS", FALSE);
        
        $server_ns_servers = NULL;
        foreach($server_domains as $server_domain){
            $server_ns_servers[] = $server_domain['data'];
        }
        
        $client_ns_servers = NULL;
        $missing = FALSE;
        if($client_domains)
        {
            
            foreach($client_domains as $client_domain){
                $client_ns_servers[] = $client_domain['data'];
            }
            
            $checked = array();
            foreach($server_ns_servers as $server_ns_server){
                $result = in_array($server_ns_server, $client_ns_servers);
                $checked[$server_ns_server] = $result;
                if(!$result){
                    $missing = TRUE;
                }
            }
        } else {
            $missing = TRUE;
        }
        
        return array('current'=>$client_ns_servers, 'requires' => $server_ns_servers, 'result' => !$missing);
        
        //$result = ($client_domain['data'] == $server_domain['data']) ? TRUE : FALSE;
        
        //$result = in_array($client_domain['data'], $server_ns_servers);
        
        //return array('current' => $client_domain['data'], 'requires' => $server_ns_servers, 'result' => $result);
        
        //return ($client_domain['data'] == $server_domain['data']) ? TRUE : FALSE;
        
    }
    
    public function isValidARecord($domain){
        $client_domain = $this->getDnsRecords($domain, "A");
        $server_domain = $this->getDnsRecords($this->server_hostname, "A");
        
        $result = (!empty($client_domain) && $client_domain['IPAddress'] == $server_domain['IPAddress']) ? TRUE : FALSE;
        
        return array('current' => (!empty($client_domain)) ? $client_domain['IPAddress'] : NULL, 'requires' => $server_domain['IPAddress'], 'result' => $result);
        
        //return ($client_domain['IPAddress'] == $server_domain['IPAddress']) ? TRUE : FALSE;
    }
    
    public function isValidDomain($domain)
    {
        $this->server_hostname = "zbyte.dns-cloud.net";
        $ns = $this->isValidNSRecord($domain);
        $a = $this->isValidARecord($domain);
        $result = ($ns['result'] OR $a['result']) ? TRUE : FALSE;
        return $result;
        //dd(array('ns'=>$ns,'a'=>$a,'result'=>$result));
        //return ($this->isValidNSRecord($domain) OR $this->isValidARecord($domain)) ? TRUE : FALSE;
    }
    
    public function domain_setup(Request $req)
    {
        //dd($req->method());
        $response = array();
        if($req->method()=== "POST"){
            //$validated_data = $req->validate([
            //    'domain' => 'required|url'    
            //]);
            $validate = $req->validate([
                'domain' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $is_valid = preg_match(
                            "/^([a-zA-Z0-9][a-zA-Z0-9-_]*\.)*[a-zA-Z0-9]*[a-zA-Z0-9-_]*[[a-zA-Z0-9]+$/", $value
                        );
                        if(!$is_valid){
                            $fail('The :attribute must be a valid domain without an http 
            protocol e.g. google.com, www.google.com');
                        }
                        if(!$this->isValidDomain($value)){
                            $fail('The :attribute must have a valid NS or A record.');
                        }
                    },
                ],
            ]);
        }
        return view('setup.domain', array('response'=>$response));
    }
    
}
