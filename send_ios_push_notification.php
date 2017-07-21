$deviceToken = '{insert device token here}';
	
$url = 'ssl://gateway.sandbox.push.apple.com:2195'; //use this for development
//$url = 'ssl://gateway.push.apple.com:2195'; //use this for production

$ctx = stream_context_create();
stream_context_set_option ( $ctx, 'ssl', 'local_cert', '{path / name of your pem file}'.pem ); 
stream_context_set_option ( $ctx, 'ssl', 'passphrase', '{password for your pem file}' );

$fp = stream_socket_client (
	$url, $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx
);

if ( !$fp )
	throw Exception ( "Failed to connect: $err $errstr" );
if ( $logger ) $logger->Write ( 'Connected to APNS' );


$body['aps'] = [
	'alert' => $message,
	'sound' => $sound,
	'url' => $link
];

$payload = json_encode ( $body );

$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

$result = fwrite ( $fp, $msg, strlen($msg) );

fclose ( $fp );

if ( !$result )
	throw Exception ( 'Error writing to socket' );

return True;