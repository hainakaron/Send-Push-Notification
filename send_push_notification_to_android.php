$msg = [
	'message' => '*insert your message here*',
	'url' => '*insert the link to be opened when the notification is clicked*',
	'title' => '*insert the title of your notification here*',
	'sound' => '*insert the name of the custom notification sound*',
];

$fields = [
	'registration_ids' => [ *insert device token here* ],
	'data' => $msg
];

$headers = [
	'Authorization: key=' . *insert the api access key here*,
	'Content-Type: application/json'
];

$fields = json_encode ( $fields );

$ch = curl_init();
curl_setopt ( $ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

$text = curl_exec ( $ch );
$result = json_decode ( $text, true );
curl_close ( $ch );

//detect invalid registration ids due to uninstallation of the app
$result_message = $result['results'][0];
if ( array_key_exists ( 'error', $result_message ) )
{
	if ( ( $result_message['error'] == 'InvalidRegistration' ) or ( $result_message['error'] == 'NotRegistered' ) )
		//do something here
}
