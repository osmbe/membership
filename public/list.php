<?php
require('../bootstrap.php');

$members = json_decode(file_get_contents('../data/members.json'));
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= _('OpenStreetMap Belgium') ?> - <?= _('List of members') ?></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/9e012c6050.js"></script>
  </head>
  <body>
    <div class="container" style="margin: 25px auto;">
      <h1><i class="fa fa-users" aria-hidden="true"></i> <?= _('List of members') ?> <small class="text-muted"><?= count($members) ?></small></h1>
      <table class="table table-striped table-sm">
        <thead>
          <th><?= _('Name') ?></th>
          <th><?= _('OSM Username') ?></th>
          <th><?= _('OSM Statistics') ?></th>
          <th><?= _('Location') ?></th>
        </thead>
        <tbody>
<?php foreach ($members as $m) { ?>
          <tr>
            <td><?= $m->firstname.' '.$m->lastname ?></td>
            <td><a href="https://www.openstreetmap.org/user/<?= rawurlencode($m->username) ?>" target="_blank"<?= (is_null($m->statistics) ? ' class="text-danger"' : '') ?>><?= $m->username ?></a></td>
            <td>
<?php if (!is_null($m->statistics)) { ?>
                <ul class="list-unstyled small" style="margin-bottom:0;">
                  <li><?= sprintf(_('Since %s'), $m->statistics->since) ?></li>
                  <li>
                    <a href="http://hdyc.neis-one.org/?<?= rawurlencode($m->username) ?>" target="_blank">
                      <em><?= _('Changes') ?> :</em> <?= _('World').' : '.number_format($m->statistics->changes, 0, ',', '.') ?> - <?= _('Belgium').' : '.number_format($m->statistics->changes_be, 0, ',', '.') ?>
                    </a>
                  </li>
                </ul>
<?php } ?>
            </td>
            <td><?= $m->location ?></td>
          </tr>
<?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" class="text-right text-muted small"><?= _('Last update') ?>: <?= date('d.m.Y H:i:s', filemtime('../data/members.json')) ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <script src="//code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  </body>
</html>
