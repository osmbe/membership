<?php
require('../bootstrap.php');

// TODO
// * Add location field to MailChimp request (> timezone)

if (isset($_POST['action'], $_POST['email'], $_POST['action']) && $_POST['action'] === 'submit') {
  $member = new class {
    public $email;
    public $firstname;
    public $languages;
    public $lastname;
    public $location;
    public $newsletter = FALSE;
    public $postalcode;
    public $username;

    public function __construct() {
      $this->email = $_POST['email'];
      $this->firstname = $_POST['firstname'];
      $this->lastname = $_POST['lastname'];

      if (isset($_POST['username']) && !empty($_POST['username'])) { $this->username = $_POST['username']; }
      if (isset($_POST['location']) && !empty($_POST['location'])) { $this->location = $_POST['location']; }
      if (isset($_POST['postalcode']) && !empty($_POST['postalcode'])) { $this->postalcode = $_POST['postalcode']; }
      if (isset($_POST['languages']) && !empty($_POST['languages']) && is_array($_POST['languages'])) { $this->languages = $_POST['languages']; }
    }
  };

  $data = array(
    'email_address' => $member->email,
    'status' => 'subscribed',
    'ip_signup' => $_SERVER['REMOTE_ADDR'],
    'timestamp_signup' => date('c'),
    'merge_fields' => array(
      'FNAME' => $member->firstname,
      'LNAME' => $member->lastname
    )
  );

  if (!is_null($member->username)) { $data['merge_fields']['OSMUSER'] = $member->username; }
  if (!is_null($member->location)) { $data['merge_fields']['PLACE'] = $member->location; }
  if (!is_null($member->postalcode)) { $data['merge_fields']['POSTCODE'] = $member->postalcode; }

  if (!is_null($member->languages)) $data['language'] = $member->languages[0];

  $data['interests'] = array(
    $config['mailchimp']['newsletter.interests.en'] => (!is_null($member->languages) && in_array('en', $member->languages)),
    $config['mailchimp']['newsletter.interests.fr'] => (!is_null($member->languages) && in_array('fr', $member->languages)),
    $config['mailchimp']['newsletter.interests.nl'] => (!is_null($member->languages) && in_array('nl', $member->languages))
  );

  $url_newsletter = 'https://us13.api.mailchimp.com/3.0/lists/5c2416bba6/members/'.md5($data['email_address']);
  $list_newsletter = mailchimp_api($url_newsletter, $data);
  //var_dump($list_newsletter);
  if ($list_newsletter['code'] !== 200) {
    $error = $list_newsletter['response'];
  } else {
    $success = TRUE;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= _('OpenStreetMap Belgium') ?> - <?= _('Become a member') ?></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/9e012c6050.js"></script>
  </head>
  <body>
    <div class="container" style="margin: 25px auto;">
      <h1><i class="fa fa-envelope-o" aria-hidden="true"></i> <?= _('Subscribe to the newsletter') ?></h1>
      <hr>
      <p>
        The Newsletter will let you know when we are organizing some event (Missing Maps, a meeting, a presentation) or when we have Big News. Don't expect more than one or two messages per month.
      </p>
      <p>
        <?= _('If you also want to become a member') ?>, <a href="index.php?lang=<?= $lang ?>"><?= _('click here') ?></a> !
      </p>
      <hr>
<?php if (isset($error)) { ?>
      <div class="alert alert-danger" role="alert">
        <strong><?= _('Oops') ?>!</strong> <?= _($error->detail) ?><br>
        <small><a href="<?= _($error->type) ?>" target="_blank" class="alert-link"><?= _('More details about this error.') ?></a></small>
      </div>
<?php } ?>
<?php if (isset($success) && $success === TRUE) { ?>
      <div class="alert alert-success" role="alert">
        <strong><?= _('Well done') ?>!</strong> <?= _('You are now subscribed to the OpenStreetMap Belgium newsletter') ?> !<br>
      </div>
<?php } ?>
      <form class="index.php" method="post">
        <div class="form-group">
          <label for="inputEmail"><?= _('Email address') ?> *</label>
          <input type="email" class="form-control" id="inputEmail" name="email" required="required">
          <small class="form-text text-muted"><?= _('We\'ll never share your email with anyone else.') ?></small>
        </div>
        <div class="form-group">
          <label for="inputFirstname"><?= _('First name') ?> *</label>
          <input type="text" class="form-control" id="inputFirstname" name="firstname" required="required">
        </div>
        <div class="form-group">
          <label for="inputLastname"><?= _('Last name') ?> *</label>
          <input type="text" class="form-control" id="inputLastname" name="lastname" required="required">
        </div>
        <div class="form-group">
          <label for="inputLocation"><?= _('City / Town / Village') ?></label>
          <input type="text" class="form-control" id="inputLocation" name="location">
        </div>
        <div class="form-group">
          <label for="inputPostalCode"><?= _('Postal code') ?></label>
          <input type="text" class="form-control" id="inputPostalCode" name="postalcode">
        </div>
        <div class="form-group">
          <label for="inputOSMUsername"><?= _('OpenStreetMap username') ?></label>
          <input type="text" class="form-control" id="inputOSMUsername" name="username">
        </div>
        <div class="form-group">
          <label for="inputLocation"><?= _('Prefered languages') ?></label><br>
          <div class="form-check form-check-inline">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" id="checkboxNL" name="languages[]" value="nl"> <?= _('Dutch') ?>
            </label>
          </div>
          <div class="form-check form-check-inline">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" id="checkboxFR" name="languages[]" value="fr"> <?= _('French') ?>
            </label>
          </div>
          <div class="form-check form-check-inline">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" id="checkboxEN" name="languages[]" value="en" checked="checked"> <?= _('English') ?>
            </label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block" name="action" value="submit"><?= _('Subscribe to the newsletter') ?></button>
      </form>
    </div>
  </body>
</html>