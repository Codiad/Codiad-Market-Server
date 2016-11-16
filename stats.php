<?php

    //header('Content-Type: application/json');

    $data = getcwd().'/data';
    $ip = array();
    $country = array();
    $type = array();
    $ext = array();
    $week = array();
    
    foreach (scandir($data) as $name){
        if($name == '.' || $name == '..' || $name == '.htaccess'){
            continue;
        }
        if(is_file($data.'/'.$name)){
            $file = fopen($data.'/'.$name,'r');
            while(!feof($file)) { 
                $line = explode(",",fgets($file));
                $hash = hash("crc32", $line[0]);

                if($line[3] != 'default' && strtolower($line[3]) != 'clear') {
                    if(!in_array($line[0],$ip)) {
                        array_push($ip,$line[0]);
                        
                        if(!array_key_exists(substr($name,0,-4),$week)) {
                            $week[substr($name,0,-4)] = 1;
                        } else {
                            $week[substr($name,0,-4)]++;
                        }
                        
                        if(!array_key_exists(strtoupper($line[1]),$country)) {
                            $country[strtoupper($line[1])] = 1;
                        } else {
                            $country[strtoupper($line[1])]++;
                        }
                    }
                    
                    if(trim($line[2]) != '') {
                        $line[2] = strtoupper($line[2]);
                        if(!array_key_exists(trim($line[2]),$type)) {
                            $type[trim($line[2])] = 1;
                        } else {
                            $type[trim($line[2])]++;
                        }
                    }
                    
                    if(trim($line[3]) != '' && trim($line[3]) != 'https:') {
                        if(substr($line[3], 0, 6) != "Codiad") {
                            $line[3] = 'Codiad-'.$line[3];
                        }
                        $line[3] = strtoupper($line[3]);
                        if(!array_key_exists(trim($line[3]),$ext)) {
                            $ext[trim($line[3])] = array($line[0]);
                        } else {
                            if(!in_array($line[0], $ext[trim($line[3])])) {
                                array_push($ext[trim($line[3])], $line[0]);
                            }
                        }
                    }
                }
            }
            fclose($file);
        }
    }
    
    $addon = array(); 
    foreach($ext as $key=>$value) {
        $key = str_replace('CODIAD-','',$key);
        $addon[$key] = sizeof($value);
    }
    
    $data = array();
    $data["Installation_Country"] = $country;
    $data["Request_Type"] = $type;
    $data["Installation_Addon"] = $addon;

?>
<html>
      <head>
        <title>Codiad Market Stats</title>
        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
          google.load('visualization', '1.0', {'packages':['corechart']});
          google.load('visualization', '1', {'packages': ['geochart']});
          google.setOnLoadCallback(drawChart);

          function drawChart() {
            <?php
                foreach($data as $title=>$stat) {
                    arsort($stat);
                    $tmp = "var data".$title." = new google.visualization.DataTable();\n";
                    $tmp .= "data".$title.".addColumn('string', 'name');\n";
                    $tmp .= "data".$title.".addColumn('number', 'count');\n";
                    $tmp .= "data".$title.".addRows([";
                    foreach($stat as $key=>$value) {
                        if(trim($key) == '') {
                            $key = "Unknown";
                        }
                        $tmp .= "['".$key."', ".$value."],\n";
                    }
                    $tmp = substr($tmp,0,-1)."]);\n";
                    $tmp .= "var chart = new google.visualization.PieChart(document.getElementById('".$title."'));\n";
                    $tmp .= "chart.draw(data".$title.", {'title':'".str_replace("_"," ",$title)."',pieHole: 0.4,chartArea:{left:10,top:20,width:\"100%\",height:\"100%\"},'width':400, 'height':400});\n";
                                        
                    echo $tmp."\n";
                }
            ?>
            
            var chart = new google.visualization.GeoChart(document.getElementById('worldmap'));
            chart.draw(dataInstallation_Country, {colorAxis:{minValue: 1,  maxValue: 250}});
          }
        </script>
      </head>

      <body>
        <table>
        <th align="left" colspan="<?php echo sizeof($data); ?>"><font style="font-family:Arial;">Codiad Marketplace - Graphs are rendered based on <?php echo sizeof($ip); ?> unique installations</font></th>
        <tr>
        <?php
            $i = 1;
            foreach($data as $title=>$stat) {
                echo "<td><div id=".$title."></div></td>";
                if($i % 3 == 0) {
                    echo "</tr><tr>";
                }
                $i++;
            }
        ?>
        </tr>
        <td align="center" colspan="<?php echo sizeof($data); ?>"><div id="worldmap" style="width: 95%; height: 95%;"></div></td>
        </table>
      </body>
    </html>
