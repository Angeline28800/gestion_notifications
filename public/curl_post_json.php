<?php
$url = 'http://notifications/aff2';

$curl = curl_init();

$fields = '[{"event":"open","time":1651156799,"MessageID":91760843779795072,"Message_GUID":"2bc73e2e-adf1-4a84-802d-b6b6f4cb415a","email":"psop28@afec.fr","mj_campaign_id":0,"mj_contact_id":2690835541,"customcampaign":"","ip":"81.249.81.185","geo":"FR","agent":"Mozilla\/4.0 (compatible; ms-office; MSOffice 16)","CustomID":"","Payload":""}]';
/*
[[
    '["event": "open",
    "time": 1433103519,
    "MessageID": 19421777396190490,
    "email": "api@mailjet.com",
    "mj_campaign_id": 7173,
    "mj_contact_id": 320,
    "customcampaign": "",
    "CustomID": "elloworld",
    "Payload": "",
    "ip": "27.0.0.",
    "geo": "US",
    "agent": "Mozilla/5.0 (Windows NT 5.1; rv:11.0) Gecko Firefox/11.0",
    "hard_bounce": true,
    "Payload": ""]']]
;
*/
$json_string = $fields;

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, TRUE);
curl_setopt($curl, CURLOPT_POSTFIELDS, $json_string);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($curl);
var_dump($data);

curl_close($curl);
