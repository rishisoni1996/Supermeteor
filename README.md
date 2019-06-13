# Supermeteor
Supermeteor is PHP SDK use to create message and email.

How to use:

1. For sending sms:

pass type, phone, message as function parameter,
Here is the sample function call for send sms.

$message = new Supermeteor('<secret_key>');
$result = $message->SendMessage('<type>', '+XXXXXXXXX', 'your message');

2. For sending email:

pass email, subject, message as function parameter,
Here is the sample function call for send email.

$message = new Supermeteor('<secret_key>');
$result = $message->SendEmail('mail@email.com', 'subject', 'your message');
