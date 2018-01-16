<?php
require 'vendor/autoload.php';
use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun('key-8a14df0c7f3b08f3d96693a347e78454');
$domain = "nini-sia-punk.rocks";

foreach ($_POST as $key => $value) {
    $$key = $value;
}
# Make the call to the client.
$result = $mgClient->sendMessage("$domain",
  array('from'    => $emailPengirim,
        'to'      => $emailTujuan,
        'subject' => $subjectEmail,
        'html'    => $isiEmail));
?>
