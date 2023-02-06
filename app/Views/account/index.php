<?php
//$session = session();
//print_r($cooperator)
$dob = '';
if ($cooperator['cooperator_dob']) {
  $dob = date_create($cooperator['cooperator_dob']);
}
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
                <li class="nav-item">
                  <a class="nav-link" href="/security">Security</a>
                </li>
              </ul><!-- .nk-menu -->
              <div class="nk-block">
                <div class="nk-block-head">
                  <div class="nk-block-head-content">
                    <h5 class="nk-block-title">Personal Information</h5>
                    <div class="nk-block-des">
                      <p>Basic and official information that you use on SLB Staff Coop.</p>
                    </div>
                  </div>
                </div><!-- .nk-block-head -->
                <div class="card card-bordered">
                  <div class="card-inner-group">
                    <div class="card-inner">
                      <div class="between-center flex-wrap flex-md-nowrap g-3">
                        <?php if (!$cooperator['cooperator_passport']): ?>
                          <div class="nk-block-text">
                            <div class="user-avatar rounded md">
                            <span>
                              <?= mb_substr($session->get('firstname'), 0, 1) . ' ' . mb_substr($session->get('lastname'), 0, 1) ?>
                            </span>
                            </div>
                          </div>
                        <?php else: ?>
                          <div class="nk-block-text">
                            <img src="/uploads/passports/<?= $cooperator['cooperator_passport'] ?>" style="width: 4.5em"
                                 alt="display picture" class="img-fluid rounded">
                          </div>
                        <?php endif; ?>
                        <div class="nk-block-actions flex-shrink-sm-0">
                          <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                            <li class="ml-4 order-md-last">
                              <a href="javascript:void(0)" data-toggle="modal" data-target="#profile-edit"
                                 class="btn btn-primary">
                                Update Profile
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div><!-- .card-inner -->
                  </div><!-- .card-inner-group -->
                </div><!-- .card -->
                <div class="nk-data data-list">
                  <div class="data-head">
                    <h6 class="overline-title">Basics</h6>
                  </div>
                  <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                    <div class="data-col">
                      <span class="data-label">First Name</span>
                      <span class="data-value"><?= $cooperator['cooperator_first_name'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Other Name</span>
                      <span class="data-value"><?= $cooperator['cooperator_other_name'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Last Name</span>
                      <span class="data-value"><?= $cooperator['cooperator_last_name'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Date of Birth</span>
                      <?php if ($dob): ?>
                        <span
                          class="data-value"><?= date_format($dob, "d F, Y") ?></span>
                      <?php else: ?>
                        <span class="data-value">-</span>
                      <?php endif ?>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Email</span>
                      <span class="data-value"><?= $cooperator['cooperator_email'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Address</span>
                      <span class="data-value"><?= $cooperator['cooperator_address'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">City</span>
                      <span class="data-value"><?= $cooperator['cooperator_city'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">State</span>
                      <?php if ($cooperator['state']): ?>
                        <span class="data-value"><?= $cooperator['state']['state_name'] ?></span>
                      <?php else: ?>
                        <span class="data-value">-</span>
                      <?php endif ?>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                </div><!-- .nk-data -->
                <div class="nk-data data-list">
                  <div class="data-head">
                    <h6 class="overline-title">Official</h6>
                  </div>
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Department</span>
                      <?php if ($cooperator['department']): ?>
                        <span class="data-value"><?= $cooperator['department']['department_name'] ?></span>
                      <?php else: ?>
                        <span class="data-value">-</span>
                      <?php endif ?>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Location</span>
                      <?php if ($cooperator['location']): ?>
                        <span class="data-value"><?= $cooperator['location']['location_name'] ?></span>
                      <?php else: ?>
                        <span class="data-value">-</span>
                      <?php endif ?>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Payroll Group</span>
                      <?php if ($cooperator['payroll_group']): ?>
                        <span class="data-value"><?= $cooperator['payroll_group']['pg_name'] ?></span>
                      <?php else: ?>
                        <span class="data-value">-</span>
                      <?php endif ?>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Contribution Amount</span>
                      <span class="data-value"><?= number_format($cooperator['cooperator_savings'], 2) ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                </div><!-- .nk-data -->
                <div class="nk-data data-list">
                  <div class="data-head">
                    <h6 class="overline-title">Bank</h6>
                  </div>
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Bank</span>
                      <?php if ($cooperator['bank']): ?>
                        <span class="data-value"><?= $cooperator['bank']['bank_name'] ?></span>
                      <?php else: ?>
                        <span class="data-value">-</span>
                      <?php endif ?>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Account Number</span>
                      <span class="data-value"><?= $cooperator['cooperator_account_number'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Bank Branch</span>
                      <span class="data-value"><?= $cooperator['cooperator_bank_branch'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Sort Code</span>
                      <span class="data-value"><?= $cooperator['cooperator_sort_code'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                </div>

                <div class="nk-data data-list">
                  <div class="data-head">
                    <h6 class="overline-title">Next of Kin</h6>
                  </div>
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Full Name</span>
                      <span class="data-value"><?= $cooperator['cooperator_kin_fullname'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Address</span>
                      <span class="data-value"><?= $cooperator['cooperator_kin_address'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Email</span>
                      <span class="data-value"><?= $cooperator['cooperator_kin_email'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Phone</span>
                      <span class="data-value"><?= $cooperator['cooperator_kin_phone'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                  <div class="data-item">
                    <div class="data-col">
                      <span class="data-label">Relationship</span>
                      <span class="data-value"><?= $cooperator['cooperator_kin_relationship'] ?></span>
                    </div>
                    <div></div>
                  </div><!-- .data-item -->
                </div>
              </div>
            </div><!-- .components-preview -->
          </div>
        </div>
      </div>
      <?php include(APPPATH . '/Views/_footer.php'); ?>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="profile-edit">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
      <div class="modal-body modal-body-lg">
        <h5 class="title">Update Profile</h5>
        <ul class="nk-nav nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#picture">Display Picture</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#bank">Bank</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#nok">Next of Kin</a>
          </li>
        </ul><!-- .nav-tabs -->
        <div class="tab-content">
          <div class="tab-pane active" id="picture">
            <form enctype="multipart/form-data" class="form-validate" id="display-picture">
              <div class="row gy-4">
                <div class="col-12">
                  <div class="form-group">
                    <label class="form-label font-weight-bolder">Display Picture <span
                        class="text-danger"> *</span></label>
                    <div class="form-control-wrap">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="dp" name="dp" accept="image/*" required>
                        <label class="custom-file-label" for="dp">Choose file</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mt-3">
                    <button class="btn btn-primary">Upload Picture</button>
                  </div>
                </div>
              </div>
            </form><!-- .tab-pane -->

          </div><!-- .tab-pane -->
          <div class="tab-pane" id="bank">
            <form class="row gy-4 form-validate" id="update-bank-form">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="application_bank_id">Bank <span class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <select class="form-control form-select" data-search="on" name="application_bank_id"
                            id="application_bank_id" required>
                      <?php foreach ($banks as $bank): ?>
                        <option
                          value="<?= $bank['bank_id']; ?>"><?= $bank['bank_name']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="application_account_number">Account Number <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <input type="text" class="form-control" name="application_account_number"
                           id="application_account_number" required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="application_bank_branch">Bank Branch <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <input type="text" class="form-control" name="application_bank_branch"
                           id="application_bank_branch" required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="application_sort_code">Sort Code <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <input type="text" class="form-control" name="application_sort_code"
                           id="application_sort_code" required>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                  <li>
                    <button class="btn btn-primary">Update Bank</button>
                  </li>
                  <li>
                    <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                  </li>
                </ul>
              </div>
            </form>
          </div><!-- .tab-pane -->
          <div class="tab-pane" id="nok">
            <form class="row gy-4 form-validate" id="update-nok">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="cooperator_kin_fullname">Full Name <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <input type="text" class="form-control" name="cooperator_kin_fullname"
                           id="cooperator_kin_fullname" required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="cooperator_kin_relationship">Relationship <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <select class="form-control form-select" data-search="on"
                            name="cooperator_kin_relationship"
                            id="cooperator_kin_relationship" required>
                      <option
                        value="sibling">Sibling
                      </option>
                      <option
                        value="spouse">Spouse
                      </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="cooperator_kin_email">Email Address <span class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <input type="email" class="form-control" name="cooperator_kin_email"
                           id="cooperator_kin_email" required
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="cooperator_kin_phone">Phone Number <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <input type="text" class="form-control" name="cooperator_kin_phone"
                           id="cooperator_kin_phone" required
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="cooperator_kin_percentage">Percentage <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                    <input type="number" class="form-control" name="cooperator_kin_percentage"
                           id="cooperator_kin_percentage" required
                    >
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label" for="cooperator_kin_address">Address <span
                      class="text-danger">*</span></label>
                  <div class="form-control-group">
                            <textarea class="form-control no-resize" name="cooperator_kin_address"
                                      id="cooperator_kin_address" rows="1" required
                            ></textarea>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                  <li>
                    <button class="btn btn-primary">Update Next of Kin</button>
                  </li>
                  <li>
                    <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                  </li>
                </ul>
              </div>
            </form>
          </div><!-- .tab-pane -->
        </div><!-- .tab-content -->
      </div><!-- .modal-body -->
    </div><!-- .modal-content -->
  </div><!-- .modal-dialog -->
</div><!-- .modal -->
<?php include(APPPATH . '/Views/_scripts.php'); ?>
<?php include(APPPATH . '/Views/_account-settings-script.php') ?>
</body>
</html>
