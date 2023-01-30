<?php
/* Наш склад */
$stock = ['data1'=>100, 'data2'=>200, 'data3'=>300];

/* Основная функция */
function get_stock($method_name, $args, $extra) {

  $code = $args[0];
  global $stock;

  if (isset($stock[$code]))
    return "ответ: '$code' " . $stock[$code];
  else
    return ['faultCode' => 1, 'faultString' => "Данные для запроса '$code' не найдены! "];
}

/* Создаем XML-RPC сервер и регистрируем функцию */
$server = xmlrpc_server_create();
xmlrpc_server_register_method($server, "getStock", "get_stock");

// Принимаем запрос
$request = file_get_contents("php://input");

/* Отдаем правильный заголовок*/
header('Content-Type: text/xml;charset=utf-8');

/* Отдаем результат */
print xmlrpc_server_call_method($server, $request, null);
?>