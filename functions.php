<?php
/* ***********************************************
 * Call Mailchimp API v3
 */
function mailchimp_api($url, $data = NULL) {
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_USERPWD, 'osmbe:'.MAILCHIMP_API_KEY);
  if (!is_null($data)) {
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  }

  $r = curl_exec($ch);

  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  curl_close($ch);

  return array(
    'code' => $httpcode,
    'response' => json_decode($r)
  );
}
