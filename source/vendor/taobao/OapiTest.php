<?php
include "TopSdk.php";
date_default_timezone_set('Asia/Shanghai');

$c = new DingTalkClient(DingTalkConstant::$CALL_TYPE_OAPI, DingTalkConstant::$METHOD_GET , DingTalkConstant::$FORMAT_JSON);
$req = new OapiGettokenRequest;
$req->setCorpid("dingc95d22c053c528xx");
$req->setCorpsecret("y2bvq4CbSV0TupI0bTg");
$resp=$c->execute($req, "accessToken","https://oapi.dingtalk.com/gettoken");
var_dump($resp)

?>