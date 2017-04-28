<?php
namespace MembersList;

class Member {
    public $email;
    public $firstname;
    public $languages;
    public $lastname;
    public $location;
    public $newsletter = FALSE;
    public $postalcode;
    public $username;

    public $statistics;

    public function __construct() {
/*
      $this->email = $_POST['email'];
      $this->username = $_POST['username'];
      $this->firstname = $_POST['firstname'];
      $this->lastname = $_POST['lastname'];

      if (isset($_POST['location']) && !empty($_POST['location'])) { $this->location = $_POST['location']; }
      if (isset($_POST['postalcode']) && !empty($_POST['postalcode'])) { $this->postalcode = $_POST['postalcode']; }
      if (isset($_POST['languages']) && !empty($_POST['languages']) && is_array($_POST['languages'])) { $this->languages = $_POST['languages']; }
      if (isset($_POST['newsletter']) && $_POST['newsletter'] == 1) { $this->newsletter = TRUE; }
*/
    }
}

namespace MembersList\Member;

class Statistics {
  public $changes = 0;
  public $changes_be = 0;
  public $changesets = 0;
  public $changesets_be = 0;
  public $since;
}