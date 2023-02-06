<?php
$session = session();
?>
<!DOCTYPE html>
<html lang="en" class="js">
<?php include(APPPATH.'/Views/_head.php'); ?>
<body class="nk-body npc-crypto bg-white has-sidebar">
<div class="nk-app-root">
  <div class="nk-main">
    <?php include(APPPATH.'/Views/_sidebar.php'); ?>
    <div class="nk-wrap">
      <?php include(APPPATH.'/Views/_header.php'); ?>
      <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
          <div class="mk-content-body">
            <div class="components-preview">
              <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                  <h2 class="nk-block-title fw-normal">Notifications</h2>
                  <div class="nk-block-des">
                    <p>View all your notifications here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <ul class="nk-nav nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link" href="/notifications">All</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/unread-notifications">Unread <span class="badge badge-primary"><?=$unseen_count?></span></a>
                </li>
              </ul><!-- .nav-tabs -->
              <div class="nk-block nk-block-sm">
                <div class="nk-block-head nk-block-head-sm">
                  <div class="nk-block-between">
                    <div class="nk-block-head-content">
                      <h6 class="nk-block-title">All Notifications</h6>
                    </div>
                  </div>
                </div><!-- .nk-block-head -->
                <?php if (!empty($notifications)): ?>
                  <div class="tranx-list tranx-list-stretch card card-bordered">
                    <?php foreach ($notifications as $notification):?>
                      <a href="/notifications/view-notification/<?=$notification['notification_id']?>" class="tranx-item">
                        <div class="tranx-col">
                          <div class="tranx-info">
                            <div class="tranx-badge">
                              <span class="tranx-icon">
                                <?php if ($notification['type'] == 'guarantor_notification'):?>
                                  <em class="icon icon-circle bg-warning-dim ni ni-clipboad-check"></em>
                                <?php endif; ?>
                              </span>
                            </div>
                            <div class="tranx-data">
                              <div class="tranx-label font-weight-bolder"><?=$notification['topic']?></div>
                              <div class="tranx-date">
                                <?php
                                  $date = date_create($notification['created_at']);
                                  echo date_format($date, 'd M Y H:i:s a');
                                ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="tranx-col">
                          <div class="tranx-amount">
                            <?php if ($notification['type'] == 'guarantor_notification'):?>
                              <span class="badge badge-pill badge-outline-warning">requires action</span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </a><!-- .nk-tranx-item -->
                    <?php endforeach;?>
                  </div><!-- .card -->
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include(APPPATH.'/Views/_footer.php'); ?>
    </div>
  </div>
</div>
<?php include(APPPATH.'/Views/_scripts.php'); ?>
</body>
</html>
