<?php
/*
 * Copyright (C) 2016 Chi Hoang
 * All rights reserved
 *
 */
 
require_once 'IP2LocationCalc.php';
echo ini_get("memory_limit")."\n";
ini_set("memory_limit","1200M");

//$test = sprintf('%u',(int)12312 & 0xFFFFE0);

class morton {
    
    /*
    function xy2d_morton($x, $y)
    {
        $x = ($x | ($x << 16)) & 0x0000FFFF0000FFFF;
        $x = ($x | ($x << 8)) & 0x00FF00FF00FF00FF;
        $x = ($x | ($x << 4)) & 0x0F0F0F0F0F0F0F0F;
        $x = ($x | ($x << 2)) & 0x3333333333333333;
        $x = ($x | ($x << 1)) & 0x5555555555555555;
    
        $y = ($y | ($y << 16)) & 0x0000FFFF0000FFFF;
        $y = ($y | ($y << 8)) & 0x00FF00FF00FF00FF;
        $y = ($y | ($y << 4)) & 0x0F0F0F0F0F0F0F0F;
        $y = ($y | ($y << 2)) & 0x3333333333333333;
        $y = ($y | ($y << 1)) & 0x5555555555555555;    
        return($x | ($y << 1));
    }
    
    // morton_1 - extract even bits
    
    function morton_1($x)
    {
        $x = $x & 0x5555555555555555;
        $x = ($x | ($x >> 1)) & 0x3333333333333333;
        $x = ($x | ($x >> 2)) & 0x0F0F0F0F0F0F0F0F;
        $x = ($x | ($x >> 4)) & 0x00FF00FF00FF00FF;
        $x = ($x | ($x >> 8)) & 0x0000FFFF0000FFFF;
        $x = ($x | ($x >> 16)) & 0xFFFFFFFFFFFFFFFF;
        return $x;
    }
    */
    
    function morton($x) {
        
        $y = $x >> 1;        
        
        $x = $x & 0x555555555555;
        $x = ($x | ($x >> 1)) & 0x333333333333;
        $x = ($x | ($x >> 2)) & 0x0F0F0F0F0F0F;
        $x = ($x | ($x >> 4)) & 0x00FF00FF00FF;
        $x = ($x | ($x >> 8)) & 0x0000FFFF0000;
        $x = ($x | ($x >> 16)) & 0xFFFFFFFFFFFF;

        $y = $y & 0x555555555555;
        $y = ($y | ($y >> 1)) & 0x333333333333;
        $y = ($y | ($y >> 2)) & 0x0F0F0F0F0F0F;
        $y = ($y | ($y >> 4)) & 0x00FF00FF00FF;
        $y = ($y | ($y >> 8)) & 0x0000FFFF0000;
        $y = ($y | ($y >> 16)) & 0xFFFFFFFFFFFF;
        
        $x = ($x | ($x << 16)) & 0x0000FFFF0000;
        $x = ($x | ($x << 8)) & 0x00FF00FF00FF;
        $x = ($x | ($x << 4)) & 0x0F0F0F0F0F0F;
        $x = ($x | ($x << 2)) & 0x333333333333;
        $x = ($x | ($x << 1)) & 0x555555555555;
    
        $y = ($y | ($y << 16)) & 0x0000FFFF0000;
        $y = ($y | ($y << 8)) & 0x00FF00FF00F0F;
        $y = ($y | ($y << 4)) & 0x0F0F0F0F0F0F0;
        $y = ($y | ($y << 2)) & 0x3333333333333;
        $y = ($y | ($y << 1)) & 0x5555555555555;
        
        return substr(base_convert($x | ($y << 1),10,4),0,6);
    }
    
    function xy2d_morton($x, $y)
    {
        $x = ($x | ($x << 16)) & 0x0000FFFF0;
        $x = ($x | ($x << 8)) & 0x00FF00FF0;
        $x = ($x | ($x << 4)) & 0x0F0F0F0F0;
        $x = ($x | ($x << 2)) & 0x333333333;
        $x = ($x | ($x << 1)) & 0x555555555;
    
        $y = ($y | ($y << 16)) & 0x0000FFFF0;
        $y = ($y | ($y << 8)) & 0x00FF00FF0;
        $y = ($y | ($y << 4)) & 0x0F0F0F0F0;
        $y = ($y | ($y << 2)) & 0x333333333;
        $y = ($y | ($y << 1)) & 0x555555555;    
        //return($x | ($y << 1));
        return substr(base_convert($x | ($y << 1),10,4),0,6);
    }
    
    // morton_1 - extract even bits
    
    function morton_1($x)
    {
        $x = $x & 0x555555555;
        $x = ($x | ($x >> 1)) & 0x33333333;
        $x = ($x | ($x >> 2)) & 0x0F0F0F0F;
        $x = ($x | ($x >> 4)) & 0x00FF00FF;
        $x = ($x | ($x >> 8)) & 0x0000FFFF;
        $x = ($x | ($x >> 16)) & 0xFFFFFFFF;
        return $x;
    }
    
    function d2xy_morton($d)
    {
        $x = $this->morton_1($d);
        $y = $this->morton_1($d >> 1);
        return array($x,$y);
    }
}

class hilbert {

        //http://blog.notdot.net/2009/11/Damn-Cool-Algorithms-Spatial-indexing-with-Quadtrees-and-Hilbert-Curves
 
    var $rev_map = ['a' => [ 
                                [2, 'd'], 
                                [0, 'a'], 
                                [3, 'a'], 
                                [1, 'c'] 
                            ], 
                    'b' => [ 
                                [3, 'c'], 
                                [1, 'b'], 
                                [2, 'b'], 
                                [0, 'd'] 
                    ], 
                    'c' => [ 
                                [3, 'b'], 
                                [0, 'c'], 
                                [2, 'c'], 
                                [1, 'a'] 
                    ], 
                    'd' => [ 
                                [2, 'a'], 
                                [1, 'd'], 
                                [3, 'd'], 
                                [0, 'b'] 
                    ], 
                ];
    	
    function hilbert2quad($hilbert) { 
     
        $r = $this->rev_map['a'][$hilbert >> 30]; 
        $key = $r[0];
        $hilbert &= 1073741823;
     
        $r = $this->rev_map[$r[1]][$hilbert >> 28]; 
        $key .= $r[0];
        $hilbert &= 268435455;        
      
        $r = $this->rev_map[$r[1]][$hilbert >> 26]; 
        $key .= $r[0];
        $hilbert &= 67108863;
        
        $r = $this->rev_map[$r[1]][$hilbert >> 24]; 
        $key .= $r[0];
        $hilbert &= 16777215;
      
        $r = $this->rev_map[$r[1]][$hilbert >> 22]; 
        $key .= $r[0];
        
        return $key;
    }	
}


$db = new \IP2Location\Database('./databases/IP2LOCATION-LITE-DB11.BIN', \IP2Location\Database::FILE_IO);
//$db = new \IP2Location\Database('./databases/IP2LOCATION-LITE-DB11.IPV6.BIN', \IP2Location\Database::FILE_IO);

//$fin=$db->ipCount["4"];

//$smask = ip2long("255.255.0.0");
//$nmask = $i & $smask;    
//$test = long2ip($i);

//53.209.98.170

//9409893
//3781642
//3781639
//31226844
//121586529
//3758095872,223.255.254.0,121012416
//3758096128,223.255.255.0,121012448
//3758096384,224.0.0.0,121012480
//4294967295,255.255.255.255,121012512


//201.192.22.159        => -1
//188.127.252.64        => 5
//51.255.98.110         => 65  
//51.254.178.243        => 44
//51.254.16.72          => 5  

$h = new hilbert();
//$m = new morton();

$kdx1=$key=$z=$y=0;
$long=$lat="";
$result=$cache=[];
$s=ip2long("0.0.0.0");
$e=ip2long("255.255.255.255");
//$s=ip2long("53.0.0.0");
//$e=ip2long("60.0.0.0");
//$e = sprintf('%u', ip2long("255.255.255.0"));

/*
echo $test=$db->readWord(65+113181696+32*1000)."\n";
echo long2ip(3546889728)."\n";
echo long2ip(1075420160)."\n";
echo long2ip($test)."\n";

for ($i=113181696;$i<113181696+19464192;$i+=32) {
    $test=$db->readWord(65+$i);
    if ($test==0) {
        break;
    }
    echo $test.",".long2ip($test).",$i\n";
}
echo $i;
die();
*/
for ($i=$s;$i<$e;$i+=256) {
    
    if($z==9000) {
        //echo "$z,$i";
        $z=0;
        ++$key;
    }
    //$key = $h->hilbert2quad($i);
    //$key = (string) $m->morton($i);
    
    switch(array_key_exists($key,$result))
    {
        case false:
            $result[$key][0]=0;            
            echo "$y:".long2ip($i).":".$key."\n";
            break;
        default: break;
    }
    $records = $db->lookup(long2ip($i), \IP2Location\Database::ALL);
    ++$z;
    if ($records['countryCode']!="This parameter is unavailable in selected .BIN data file. Please upgrade."
        && $records['countryCode']!="Invalid IP address."
        && ($records['latitude']!=$lat || $records['longitude']!=$long)
        ) {
        $lat=$records['latitude'];
        $long=$records['longitude'];
        //$cache[$i]=$db->idx;
        switch($result[$key][0]) {
            case 0:
                $result[$key][0]=$db->idx;
                $result[$key][1]=$i;
                break;
            default:
                $result[$key][3]=$i;
                
                /*
                if ($i>ip2long("188.0.0.0") && $i<ip2long("189.0.0.0")) {
                    $result[$key][2]=$db->idx+5;
                } else if($i>ip2long("51.0.0.0") && $i<ip2long("52.0.0.0")) {
                    $result[$key][2]=$db->idx+65; 
                } else {
                    $result[$key][2]=$db->idx;
                }
                */
                
                $result[$key][2]=$db->idx;
                break;
        }
        ++$y;        
        if ($i%1000==0) {
            echo "IP:".long2ip($i).",K:$key,No:$db->idx"."\n";    
        }
        if ($db->idx>=121012512) break;
    } else if ($i%50000==0) {
        echo "No:$i\n";
    }
    if ($y>3781642) break;
}
echo $y.":".count($result);

file_put_contents('file1.txt', "");
file_put_contents('file2.txt', "");

$offset=$kdx1=$kdx1bakup=$f1bakup=$idx1bakup=0;
$last=$end=false;
$fb1=$fb2=$f1=$f2=0;$str1=$str2="";
foreach ($result as $key => $fields)
{
    if ($fields[0]=="")
    {
        $f1=$fb1; $f2=$fb2;
    } else {
        $fb1=$f1=$fields[0];
        if(isset($fields[2]) && $fields[2]=="") {
            $f2=$fb2;
        } else {
            $fb2=$f2=$fields[2];
        }
    }
    if (($f2<$f1) || ($f2==0 && $f1==0)) {
        ++$offset;
        continue;
    }
    if ($end==true) continue;
    
    $bs1=(($f2+$f1)>>1);
    
    if (($f1==3781640 && $f2==3781638 && $bs1==3781639) || $key == "12000" || $end==true) {
        $f1=(int)3781638;
        $f2=(int)3781642;
        $bs1=(int)3781640;
        $end=true;
    };
       
    $kdx1 = $db->readWord(65+$f1*32);
    if (($kdx1-$kdx1bakup)==0 && $last==true) continue;
    $last = false;
    
    $kdx2 = $end==false ? $db->readWord(65+$f2*32) : 3758095872+4193792*2;
    $kdx1 = $kdx1 == 16777216 ? 0 : $kdx1;
    $kdx1 = $kdx1 == 16777472 ? 0 : $kdx1;
    
    $bs2=(($f1+$bs1)>>1);
    $bs3=(($bs1+$f2)>>1);
    
    $bs4=(($f1+$bs2)>>1);
    $bs5=(($bs2+$bs1)>>1);
    
    $bs6=(($bs1+$bs3)>>1);
    $bs7=(($bs3+$f2)>>1);
    
    $bs8=(($f1+$bs4)>>1);
    $bs9=(($bs4+$bs2)>>1);
    
    $bs10=(($bs2+$bs5)>>1);
    $bs11=(($bs5+$bs1)>>1);
    
    $bs12=(($bs1+$bs6)>>1);
    $bs13=(($bs6+$bs3)>>1);
    
    $bs14=(($bs3+$bs7)>>1);
    $bs15=(($bs7+$f2)>>1);
    
    $idx1 = $db->readWord(65+$bs8*32);    
    $idx2 = $db->readWord(65+$bs4*32);    
    $idx3 = $db->readWord(65+$bs9*32);    
    $idx4 = $db->readWord(65+$bs2*32);    
    $idx5 = $db->readWord(65+$bs10*32);    
    $idx6 = $db->readWord(65+$bs5*32);    
    $idx7 = $db->readWord(65+$bs11*32);    
    $idx8 = $db->readWord(65+$bs1*32);    
    $idx9 = $db->readWord(65+$bs12*32);    
    $idx10 = $db->readWord(65+$bs6*32);    
    $idx11 = $db->readWord(65+$bs13*32);    
    $idx12 = $db->readWord(65+$bs3*32);                             
    $idx13 = $db->readWord(65+$bs14*32);    
    $idx14 = $db->readWord(65+$bs7*32);    
    $idx15 = $db->readWord(65+$bs15*32);    
        
    $str1 .= "\"".($key-$offset)."\"=>[\"".($kdx1-$kdx1bakup)."\",\"".
                             ($kdx2-$kdx1)."\",\"".
                             (($f1*32)-$f1bakup)."\",\"".
                            (($f2*32)-($f1*32))."\",\"".
                            (($bs1*32)-($f1*32))."\",\"a\"=>[\"".
                            ($idx1-$idx1bakup)."\",\"".
                            ($idx2-$idx1)."\",\"".
                            ($idx3-$idx2)."\",\"".
                            ($idx4-$idx3)."\",\"".
                            ($idx5-$idx4)."\",\"".
                            ($idx6-$idx5)."\",\"".
                            ($idx7-$idx6)."\",\"".
                            ($idx8-$idx7)."\",\"".
                            ($idx9-$idx8)."\",\"".
                            ($idx10-$idx9)."\",\"".
                            ($idx11-$idx10)."\",\"".
                            ($idx12-$idx11)."\",\"".
                            ($idx13-$idx12)."\",\"".
                            ($idx14-$idx13)."\",\"".
                            ($idx15-$idx14)."\"]],";
   
   $str2 .= "\"".($key-$offset)."\"=>[".($kdx1).",".
                             ($kdx2).",".
                             (($f1*32)).",".
                            (($f2*32)).",".
                            (($bs1*32)).",\"a\"=>[".
                            ($idx1).",".
                            ($idx2-$idx1).",".
                            ($idx3-$idx2).",".
                            ($idx4-$idx3).",".
                            ($idx5-$idx4).",".
                            ($idx6-$idx5).",".
                            ($idx7-$idx6).",".
                            ($idx8-$idx7).",".
                            ($idx9-$idx8).",".
                            ($idx10-$idx9).",".
                            ($idx11-$idx10).",".
                            ($idx12-$idx11).",".
                            ($idx13-$idx12).",".
                            ($idx14-$idx13).",".
                            ($idx15-$idx14)."]],";
                            
    //echo ($kdx1-$kdx1bakup).",$kdx1bakup,$f1bakup,$idx1bakup\n";
    
    $kdx1bakup=$kdx1;
    $f1bakup=$f1*32;
    $idx1bakup=$idx1;  
   
    
    /*                       
                            $str1 .= "\"$key\"=>[0x".dechex(($f1*32)).",0x".
                            dechex((($f2*32)-($f1*32))).",0x".
                            dechex((($bs1*32)-($f1*32))).",\"a\"=>[0x".
                            dechex($idx1).",0x".
                            dechex(($idx2-$idx1)).",0x".
                            dechex(($idx3-$idx2)).",0x".
                            dechex(($idx4-$idx3)).",0x".
                            dechex(($idx5-$idx4)).",0x".
                            dechex(($idx6-$idx5)).",0x".
                            dechex(($idx7-$idx6)).",0x".
                            dechex(($idx8-$idx7)).",0x".
                            dechex(($idx9-$idx8)).",0x".
                            dechex(($idx10-$idx9)).",0x".
                            dechex(($idx11-$idx10)).",0x".
                            dechex(($idx12-$idx11)).",0x".
                            dechex(($idx13-$idx12)).",0x".
                            dechex(($idx14-$idx13)).",0x".
                            dechex(($idx15-$idx14))."]],";

    */
    /*                       
    $str1 .= "\"$key\"=>[".($f1*32).",".
                            (($f2*32)-($f1*32)).",".
                            (($bs1*32)-($f1*32)).",\"a\"=>[".
                            $idx1.",".
                            ($idx2-$idx1).",".
                            ($idx3-$idx2).",".
                            ($idx4-$idx3).",".
                            ($idx5-$idx4).",".
                            ($idx6-$idx5).",".
                            ($idx7-$idx6).",".
                            ($idx8-$idx7).",".
                            ($idx9-$idx8).",".
                            ($idx10-$idx9).",".
                            ($idx11-$idx10).",".
                            ($idx12-$idx11).",".
                            ($idx13-$idx12).",".
                            ($idx14-$idx13).",".
                            ($idx15-$idx14)."]],";
    */
}
file_put_contents('file1.txt', $str1, FILE_APPEND);
file_put_contents('file2.txt', $str2, FILE_APPEND);

/*
$str="";
file_put_contents('cache.txt', "");
foreach ($cache as $key => $fields)
{
    $str .="$key,$fields,";
}
file_put_contents('cache.txt', $str, FILE_APPEND);
*/
