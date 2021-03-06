<?php
//20-11-2020
//started on 04-07-2018
// La app di Heroku si puo richiamare da browser con
//			https://esp014rele.herokuapp.com/
// Account Heroku:  dariomelucci@gmail.com   pwd:  Bg_142666
// Account GitHub:	attiliomelucci@libero.it pwd:  Bg142666    name: attilio26

/*API key = 1355761807:AAFcv6jHSsqypU-9z83neRKMXmMwKZudrwg

da browser request ->   https://esp014rele.herokuapp.com/register.php
           answer  <-   {"ok":true,"result":true,"description":"Webhook was set"}
In questo modo invocheremo lo script register.php che ha lo scopo di comunicare a Telegram
l�indirizzo dell�applicazione web che risponder� alle richieste del bot.

da browser request ->   https://api.telegram.org/bot1355761807:AAFcv6jHSsqypU-9z83neRKMXmMwKZudrwg/getMe
           answer  <-   {"ok":true,"result":{"id":1355761807,"is_bot":true,"first_name":"Esp014rele","username":"Esp014bot","can_join_groups":true,
					 "can_read_all_group_messages":false,"supports_inline_queries":false}}

riferimenti:
https://gist.github.com/salvatorecordiano/2fd5f4ece35e75ab29b49316e6b6a273
https://www.salvatorecordiano.it/creare-un-bot-telegram-guida-passo-passo/
*/

//------passaggio da getupdates a  WEBHOOK
//da browser request ->   https://api.telegram.org/bot1355761807:AAFcv6jHSsqypU-9z83neRKMXmMwKZudrwg/setWebhook?url=https://esp014rele.herokuapp.com/execute.php
//					 answer  <-   {"ok":true,"result":true,"description":"Webhook was set"}
//          From now If the bot is using getUpdates, will return an object with the url field empty.
//------passaggio da webhook a  GETUPDATES
//da browser request ->   https://api.telegram.org/bot1355761807:AAFcv6jHSsqypU-9z83neRKMXmMwKZudrwg/setWebhook?url=
//					 answer  <-   {"ok":true,"result":true,"description":"Webhook was deleted"}

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if(!$update)
{
  exit;
}

function clean_html_page($str_in){
	$startch = strpos($str_in,"</header><h2>") + 13 ;							//primo carattere utile da estrarre
	$endch = strpos($str_in," </a></h2><foot");									//ultimo carattere utile da estrarre
	$str_in = substr($str_in,$startch,$endch - $startch);				// substr(string,start,length)
  $str_in = str_replace("<a href='?a="," ",$str_in);
  $str_in = str_replace("r><h2>"," ",$str_in);
	$str_in = str_replace(" </a></h2><h2>"," ",$str_in);
	$str_in = str_replace("0'/>"," ",$str_in);
	$str_in = str_replace("1'/>"," ",$str_in);
	$str_in = str_replace("2'/>"," ",$str_in);
	$str_in = str_replace("3'/>"," ",$str_in);
	$str_in = str_replace("4'/>"," ",$str_in);
	$str_in = str_replace("5'/>"," ",$str_in);
	$str_in = str_replace("6'/>"," ",$str_in);
	$str_in = str_replace("7'/>"," ",$str_in);	
	$str_in = str_replace("8'/>"," ",$str_in);	
	$str_in = str_replace("9'/>"," ",$str_in);	
	return $str_in;
}

$message = isset($update['message']) ? $update['message'] : "";
$messageId = isset($message['message_id']) ? $message['message_id'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$firstname = isset($message['chat']['first_name']) ? $message['chat']['first_name'] : "";
$lastname = isset($message['chat']['last_name']) ? $message['chat']['last_name'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$date = isset($message['date']) ? $message['date'] : "";
$text = isset($message['text']) ? $message['text'] : "";

// pulisco il messaggio ricevuto togliendo eventuali spazi prima e dopo il testo
$text = trim($text);
// converto tutti i caratteri alfanumerici del messaggio in minuscolo
$text = strtolower($text);

header("Content-Type: application/json");

//ATTENZIONE!... Tutti i testi e i COMANDI contengono SOLO lettere minuscole
$response = '';

if(strpos($text, "/start") === 0 || $text=="ciao" || $text == "help"){
	$response = "Ciao $firstname, benvenuto! \n List of commands : 
	/mur1 		-> GPIO0 LOW  /mur0 		-> GPIO0 HIGH
	/stu1  		-> GPIO1 LOW  /stu0  		-> GPIO1 HIGH 
	/r21  		-> GPIO2 LOW  /r20  		-> GPIO2 HIGH 
	/tlc1 		-> GPIO3 LOW  /tlc0 		-> GPIO3 HIGH 
	/rf1  		-> GPIOx LOW  /rf0  		-> GPIOx HIGH 
	/stato 		-> Stato rele     \n/verbose -> parametri del messaggio";
}

//<-- Comandi al rele GPIO0
elseif(strpos($text,"mur1")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=0");
	$response = clean_html_page($resp);
}
elseif(strpos($text,"mur0")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=1");
	$response = clean_html_page($resp);
}

//<-- Comandi al rele GPIO1
elseif(strpos($text,"stu1")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=2");
	$response = clean_html_page($resp);
}
elseif(strpos($text,"stu0")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=3");
	$response = clean_html_page($resp);
}

//<-- Comandi al rele GPIO2
elseif(strpos($text,"r21")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=4");
	$response = clean_html_page($resp);
}
elseif(strpos($text,"r20")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=5");
	$response = clean_html_page($resp);
}

//<-- Comandi al rele GPIO3
elseif(strpos($text,"tlc1")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=6");
	$response = clean_html_page($resp);
}
elseif(strpos($text,"tlc0")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=7");
	$response = clean_html_page($resp);
}

//<-- Comandi a TUTTI i rele
elseif(strpos($text,"rf1")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=8");
	$response = clean_html_page($resp);
}
elseif(strpos($text,"rf0")){
	$resp = file_get_contents("http://dario95.ddns.net:28019/?a=9");
	$response = clean_html_page($resp);
}


//<-- Lettura stato dei rele 
elseif(strpos($text,"stato")){
	$resp = file_get_contents("http://dario95.ddns.net:28019");
	$response = clean_html_page($resp);
}

//<-- Manda a video la risposta completa
elseif($text=="/verbose"){
	$response = "chatId ".$chatId. "   messId ".$messageId. "  user ".$username. "   lastname ".$lastname. "   firstname ".$firstname ;		
	$response = $response. "\n\n Heroku + dropbox gmail.com";
}
else
{
	$response = "Unknown command!";			//<---Capita quando i comandi contengono lettere maiuscole
}
// Gli EMOTICON sono a:     http://www.charbase.com/block/miscellaneous-symbols-and-pictographs
//													https://unicode.org/emoji/charts/full-emoji-list.html
//													https://apps.timwhitlock.info/emoji/tables/unicode
// la mia risposta � un array JSON composto da chat_id, text, method
// chat_id mi consente di rispondere allo specifico utente che ha scritto al bot
// text � il testo della risposta
$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";
// imposto la keyboard
$parameters["reply_markup"] = '{ "keyboard": [
["/tlc1 \ud83d\udd34", "/r21 \ud83d\udd34", "/stu1 \ud83d\udd34", "/mur1 \ud83d\udd34"],
["/tlc0 \ud83d\udd35", "/r20 \ud83d\udd35", "/stu0 \ud83d\udd35", "/mur0 \ud83d\udd35"],
["/rf0 \ud83d\udd35", "/rf1 \ud83d\udd34"],
["/stato \u2753"]],
 "resize_keyboard": true, "one_time_keyboard": false}';
// converto e stampo l'array JSON sulla response
echo json_encode($parameters);
?>