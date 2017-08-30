<?php
include $setup['path']."/sy-inc/MailChimp.php";

$MailChimp = new \Drewm\MailChimp('0a61c5c38e72aa4e580a8e2ef92802cc-us3');
/*
$result = $MailChimp->call('lists/subscribe', array(
                'id'                => 'b1234346',
                'email'             => array('email'=>'davy@example.com'),
                'merge_vars'        => array('FNAME'=>'Davy', 'LNAME'=>'Jones'),
                'double_optin'      => false,
                'update_existing'   => true,
                'replace_interests' => false,
                'send_welcome'      => false,
            ));
print_r($result);
*/

print "<pre>";
// print_r($MailChimp->call('lists/list'));
print "</pre>";

$lists = $MailChimp->call('lists/list');

print "Total: ".$lists['total'];
$x = 0;
while($x < $lists[total]) { 
	print "<li>".$lists[data][$x][name]." ID: ".$lists[data][$x][id]." Members: ".$lists[data][$x][stats][member_count];
	$x++;
}


foreach($lists AS $alists) { 
	print "<li>".$alists['name'];
	
	print_r($alists[2]);
}


/*
$result = $MailChimp->call('lists/abuse-reports', array(
            'id'                => '71ac585da2',
            ));

print "<pre>";
print_r($result);
print "</pre>";

$result = $MailChimp->call('lists/members', array(
            'id'                => '71ac585da2',
            'status'                => 'unsubscribed',
            ));

print "<pre>";
print_r($result);
print "</pre>";


$result = $MailChimp->call('campaigns/list', array(
                'id'                => '3292fefd5d',
            ));

print "<li>".$result[data][0]['subject'];
print "<li>".$result[data][0]['summary']['unique_opens'];
print "<li>".$result[data][0]['summary']['unsubscribes'];


print "<pre>";
print_r($result);
print "</pre>";
*/