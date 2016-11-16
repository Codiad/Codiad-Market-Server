<?php

    header('Content-Type: application/json');

    if(isset($_GET['i']) && isset($_GET['t'])) {
        // track plugin install
        file_put_contents("../data/".date("Y-W").".log", $_SERVER['REMOTE_ADDR'].",".lookupGeoLocation($_SERVER['REMOTE_ADDR']).",".$_GET['t'].",".$_GET['i'].",".$_GET['r']."\r\n", FILE_APPEND | LOCK_EX);
    } else {
        
        if(isset($_GET['o']) && $_GET['o'] != '') {
            // optout existing plugins
            foreach(explode(",", $_GET['o']) as $data) {
                $line = explode(":", $data);
                file_put_contents("../data/".date("Y-W").".log", $_SERVER['REMOTE_ADDR'].",".lookupGeoLocation($_SERVER['REMOTE_ADDR']).",".$line[0].",".$line[1].",\r\n", FILE_APPEND | LOCK_EX);
            }    
        }
                
        // get current market
        $data = getcwd().'/../data';
        $ip = array();
        $country = array();
        $type = array();
        $ext = array();
        
        
        foreach (scandir($data) as $name){
            if($name == '.' || $name == '..' || $name == '.htaccess'){
                continue;
            }
            if(is_file($data.'/'.$name)){
                $file = fopen($data.'/'.$name,'r');
                while(!feof($file)) { 
                    $line = explode(",",fgets($file));
    
                    if(!in_array($line[0],$ip)) {
                        array_push($ip,$line[0]);
                        
                        if(!array_key_exists(strtoupper($line[1]),$country)) {
                            $country[strtoupper($line[1])] = 1;
                        } else {
                            $country[strtoupper($line[1])]++;
                        }
                        
                        if(!array_key_exists(trim($line[2]),$type)) {
                            $type[trim($line[2])] = 1;
                        } else {
                            $type[trim($line[2])]++;
                        }
                    }
                    
                    if($line[3] != '' && substr($line[3], 0, 6) != "Codiad") {
                        $line[3] = 'Codiad-'.$line[3];
                    }
                    if(!array_key_exists(trim($line[3]),$ext)) {
                        $ext[trim($line[3])] = array($line[0]);
                    } else {
                        if(!in_array($line[0], $ext[trim($line[3])])) {
                            array_push($ext[trim($line[3])], $line[0]);
                        }
                    }
                }
                fclose($file);
            }
        }
        
        $plugins = json_decode(file_get_contents('http://codiad.com/plugins.json'),true);
        $themes = json_decode(file_get_contents('http://codiad.com/themes.json'),true);
        $tmp = array();
        
        foreach($plugins as $plugin) {
            $plugin['type'] = 'plugins';
            
            if(isset($ext[trim(array_pop(explode('/', $plugin['url'])))])) {
                $plugin['count'] = sizeof($ext[trim(array_pop(explode('/', $plugin['url'])))]); 
            } else {
                $plugin['count'] = 0;
            }
                
            array_push($tmp, $plugin);
        }
        
        foreach($themes as $theme) {
            $theme['type'] = 'themes';
            
            if(isset($ext[trim(array_pop(explode('/', $theme['url'])))])) {
                $theme['count'] = sizeof($ext[trim(array_pop(explode('/', $theme['url'])))]);
            } else {
                $theme['count'] = 0;
            }
                
            array_push($tmp, $theme);
        } 
        
        
        echo json_encode($tmp);  
    }
    
    //////////////////////////////////////////////////
    // COUNTRY LOOKUP
    //////////////////////////////////////////////////
      
    function lookupGeoLocation($ip){
        $info = file_get_contents("http://who.is/whois-ip/ip-address/$ip");
        list($a, $b) = explode('country:        ', $info);
        $country = substr($b,0,2);
        if(trim($country) == '') {
            $country = file_get_contents('http://api.hostip.info/country.php?ip='.$ip);
        }
        if(trim($country) == '' || trim($country) == 'XX') {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
            $country = $details->country;
        }
        return $country;
    }
        
?>
