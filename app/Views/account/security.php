<?php
$session = session();
?>
<!DOCTYPE html>
<html lang="en" class="js">
<?php include(APPPATH . '/Views/_head.php'); ?>
<body class="nk-body npc-crypto bg-white has-sidebar">
<div class="nk-app-root">
  <div class="nk-main">
    <?php include(APPPATH . '/Views/_sidebar.php'); ?>
    <div class="nk-wrap">
      <?php include(APPPATH . '/Views/_header.php'); ?>
      <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
          <div class="nk-content-body">
            <div class="components-preview">
              <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                  <h2 class="nk-block-title fw-normal">Account Settings</h2>
                  <div class="nk-block-des">
                    <p>Manage your membership account.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <ul class="nk-nav nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link" href="/account">Personal</a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link " href="/security">Security</a>
                </li>
              </ul><!-- .nk-menu -->
              <div class="nk-block">
                <div class="nk-block-head">
                  <div class="nk-block-head-content">
                    <h5 class="nk-block-title">Security Settings</h5>
                    <div class="nk-block-des">
                      <p>These settings will help you keep your account secure.</p>
                    </div>
                  </div>
                </div><!-- .nk-block-head -->
                <div class="card card-bordered">
                  <div class="card-inner-group">
                    <div class="card-inner">
                      <div class="between-center flex-wrap flex-md-nowrap g-3">
                        <div class="nk-block-text">
                          <h6>Change Password</h6>
                          <p>Set a unique password to protect your account.</p>
                        </div>
                        <div class="nk-block-actions flex-shrink-sm-0">
                          <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                            <li class="order-md-last">
                              <a href="javascript:void(0)" data-toggle="modal" data-target="#change-password"
                                 class="btn btn-primary">Change Password</a>
                            </li>
                            <!--                            <li>-->
                            <!--                              <em class="text-soft text-date fs-12px">Last changed: <span>Oct 2, 2019</span></em>-->
                            <!--                            </li>-->
                          </ul>
                        </div>
                      </div>
                    </div><!-- .card-inner -->
                  </div><!-- .card-inner-group -->
                </div><!-- .card -->

              </div>
            </div><!-- .components-preview -->
          </div>
        </div>
      </div>
      <?php include(APPPATH . '/Views/_footer.php'); ?>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="change-password">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
      <div class="modal-body modal-body-lg">
        <h5 class="title">Change Password</h5>
        <form class="form-validate" id="change-password-form">
          <div class="row gy-4 pt-5">
            <div class="col-12">
              <div class="form-group">
                <label class="form-label font-weight-bolder" for="current-password">Current Password <span
                    class="text-danger"> *</span></label>
                <input type="password" id="current-password" class="form-control" name="current-password">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="form-label font-weight-bolder" for="new-password">New Password <span
                    class="text-danger"> *</span></label>
                <input type="password" id="new-password" class="form-control" name="password">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label class="form-label font-weight-bolder" for="confirm-password">Confirm Password <span
                    class="text-danger"> *</span></label>
                <input type="password" id="confirm-password" class="form-control" name="confirm-password">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group mt-3">
                <button class="btn btn-primary">Submit</button>
              </div>
            </div>
          </div>
        </form><!-- .tab-pane -->

      </div><!-- .modal-body -->
    </div><!-- .modal-content -->
  </div><!-- .modal-dialog -->
</div><!-- .modal -->
<?php include(APPPATH . '/Views/_scripts.php'); ?>
<?php include(APPPATH . '/Views/_account-settings-script.php') ?>
</body>
</html>
