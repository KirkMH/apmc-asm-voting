<?php
// the message
$msg = "Second batch test \n with CC this time. Using AUTH email";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

    $header = "From:ACE Medical Center Aklan <emailauth@acemc-aklan.com> \r\n";
    $header .= "Reply-To: emailauth@acemc-aklan.com\r\n";
    $header .= "Return-Path: emailauth@acemc-aklan.com\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";
    $header .= "Content-type: text/html\r\n";
    /*
$headers = "From: emailauth@acemc-aklan.com" . "\r\n" .
"CC: test-00adsfeo6@srv1.mail-tester.com";
*/
// send email
$emailadd = 'test-sccdq6bj9@srv1.mail-tester.com';
mail($emailadd,"My subject",$msg, $headers);
echo 'sent to '. $emailadd;
/*
// another way to call error_log():
error_log("You messed up!\r\n", 3, "my-emailerrors.log");
}
*/
// // Notify administrator by email if we run out of FOO
// if (!($foo = allocate_new_foo())) {
//     error_log("Big trouble, we're all out of FOOs!", 1,
//                "herminioyatarjr@gmail.com");
// }
?>