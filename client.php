<?php
header('Content-Type: text/html;charset=utf-8');

$arrMessage = [];
	
function make_request($request_xml, &$arrMessage, $code) {
	
	$opts = [
		'http'=>[
			'method' => "POST",
			'header' => "User-Agent: PHPRPC/1.0\r\n" .
						"Content-Type: text/xml\r\n" .
						"Content-length: " . strlen($request_xml) . "\r\n",
			'content' => $request_xml
		]
	];
	
	$context = stream_context_create($opts);		
	$retval  = file_get_contents('http://xml-rpc/server.php', false, $context);
	$data    = xmlrpc_decode($retval);
	
	if (is_array($data) && xmlrpc_is_fault($data)) {

		$arrMessage[] = "<hr>Невозможно получить данные $code";
		$arrMessage[] = "Error Code: " . $data['faultCode'];
		$arrMessage[] = "Error Message: " . $data['faultString'];
		
	}else{
		
		$arrMessage[] = $data;
	}
}


/* Код необходимой запроса */
$code = "data1";
$request_xml = xmlrpc_encode_request('getStock', $code);
make_request($request_xml, $arrMessage, $code);


/* Код необходимой полки */
$code = "data2";
$request_xml = xmlrpc_encode_request('getStock', $code);
make_request($request_xml, $arrMessage, $code);


/* Код необходимой полки (которой нет) */
$code = "data4";
$request_xml = xmlrpc_encode_request('getStock', $code);
make_request($request_xml, $arrMessage, $code);


/* Вывод результата */
print '<pre>'; 
	foreach ($arrMessage as $message){
		print $message.  "\n";
	}
print '</pre>';
?>
