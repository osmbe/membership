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

/* ***********************************************
 * Call Mailchimp API v3
 */
function hdyc_api($username) {
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://hdyc.neis-one.org/search/'.urlencode($username));
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  $r = curl_exec($ch);

  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  curl_close($ch);

  return array(
    'code' => $httpcode,
    'response' => json_decode($r)
  );
}

/* ***********************************************
 * Create members.json
 */
function get_members($file) {
  global $config;

  $url_members = 'https://us13.api.mailchimp.com/3.0/lists/'.$config['mailchimp']['members.id'].'/members?count=250&status=subscribed';
  $r = mailchimp_api($url_members); $members = $r['response']->members;

  $json = array();
  foreach ($members as $m) {
    $member = new MembersList\Member();
    $member->firstname = $m->merge_fields->FNAME;
    $member->lastname = $m->merge_fields->LNAME;
    $member->location = $m->merge_fields->PLACE;
    $member->username = $m->merge_fields->OSMUSER;

    if (!empty($member->username)) {
      $hdyc = hdyc_api($member->username);
      if ($hdyc['code'] == 200 && isset($hdyc['response']->contributor)) {
        $func = function($value) { return explode('=', $value); };
        $countries = (isset($hdyc['response']->countries->countries) ? explode(';', $hdyc['response']->countries->countries) : array());
        $countries = array_map($func, $countries);
        $belgium = NULL; foreach ($countries as $c) { if ($c[0] === 'Belgium' || $c[1] === 'be') { $belgium = array( $c[2], $c[3] ); break; } }

        $member->statistics = new MembersList\Member\Statistics();
        $member->statistics->changes = (isset($hdyc['response']->changesets->changes) ? $hdyc['response']->changesets->changes : 0);
        $member->statistics->changes_be = (isset($belgium) ? $belgium[1] : 0);
        $member->statistics->changesets = (isset($hdyc['response']->changesets->no) ? $hdyc['response']->changesets->no : 0);
        $member->statistics->changesets_be = (isset($belgium) ? $belgium[0] : 0);
        $member->statistics->since = $hdyc['response']->contributor->since;
      }
    }

    $json[] = $member;
  }

  usort($json, 'sort_members');

  return file_put_contents($file, json_encode($json));
}

/* ***********************************************
 * Sort members.json
 */
function sort_members($a, $b) {
  if (strcasecmp($a->lastname, $b->lastname) === 0) {
    return strcasecmp($a->firstname, $b->firstname);
  }
  return strcasecmp($a->lastname, $b->lastname);
}