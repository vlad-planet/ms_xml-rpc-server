# version php 7.4

# Использование XML-RPC

## Простой RPC сервер для обмена данными. Вызов удалённых процедур

#### XML-RPC запрос

```php
<methodCall>
	<methodName>getStock</methodName>
	<params>
		<param>
			<value><i4>3</i4></value>
		</param>
	</params>
</methodCall>
```

#### XML-RPC ответ

```php
<methodResponse>
	<params>
		<param>
			<value><i4>300</i4></value>
		</param>
		<param>
			<value><string>Sony Vayo</string></value>
		</param>
	</params>
</methodResponse>
```

#### Подключить расширение php_xmlrpc.dll

## Создание XML-RPC сервера

```php
// Описание службы
$stock = [
 "a"=>100,
 "b"=>200,
 "c"=>300,
 "d"=>400,
 "e"=>500
];

function get_stock($methodName, $arguments, $extra){
	global $stock;
	global $stock;
	
	$code = $arguments[0];
	if(is_set($stock[$code]))
		return $stock[$code];
	return ["faultCode" => 1, "faultString" => "Нет такой полки"];
}

// Создание сервера
$server = xmlrpc_server_create();

// Добавление функции, которая будет видна клиенту
xmlrpc_server_register_method($server, "getStock", "get_stock");

// Приём запроса
$request = file_get_contents("php://input");

// Обработка запроса
echo xmlrpc_server_call_method($server, $request, null);
```

## Создание XML-RPC клиента

```php
// Создание запроса
$server = xmlrpc_encode_request("getStock", "b");

// XML-RPC запрос и получение ответа
$response = запрос_любым_способом_методом_POST("URL");

// Декодирование ответа
$result = xmlrpc_decode("URL");

if( xmlrpc_is_fault($result) )
	echo $result["faultString"];
else
	echo $result;
```

## Контекст потока

```php
// Формирование необходимых данных
$options = [
	"http"=> [
		"method" => "GET",
		"header" => "User-Agent: PHPBot\r\n".
		"Cookie: user=John\r\n"
	]
];

// Создание контекста потока
$context = stream_context_create($options);

// Запрос с использованием созданного контекста потока
echo file_get_contents("http://xml-rpc/server.php", false, $context);

// Получение ответа при использовании fopen()
$f = fopen("http://xml-rpc/server.php", "r", false, $context);
echo stream_get_contents($f);

// Получение заголовков ответа
print_r( stream_get_meta_data($f) );
```
