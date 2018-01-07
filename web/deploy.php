<?php 
$log = "####### ".date('Y-m-d H:i:s'). " #######\n";
if($_GET['token'] != '' &&  $_GET['token'] == '61ddc724be699a5e54ffb03ed6998593f4fbda92')
{
    $commands = array(
            'echo $PWD',
            'whoami',
            'git pull',
            'git status',
    //	'git submodule sync',
    //	'git submodule update',
    //	'git submodule status',
    //    'test -e /usr/share/update-notifier/notify-reboot-required && echo "system restart required"',
    );
    $output = "\n";
    foreach($commands AS $command){
        // Run it
        $tmp = shell_exec("$command 2>&1");
        // Output
        $output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
        $output .= htmlentities(trim($tmp)) . "\n";
        $log  .= "\$ $command\n".trim($tmp)."\n";
    }
    $log .= "\n";
}else{
    $log  .= "\Request without token - nasty thing...\n";
    $log .= "\n";
}
file_put_contents ('deploy-log.txt',$log,FILE_APPEND);
echo $output; 

