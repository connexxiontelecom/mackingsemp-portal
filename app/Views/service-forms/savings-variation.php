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
          <div class="nk-content-body">
            <div class="components-preview">
              <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                  <h2 class="nk-block-title fw-normal">Savings Variation</h2>
                  <div class="nk-block-des">
                    <p>Submit a new savings variation here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-blood-head-content">
                    <h4 class="nk-block-title">New Savings Variation Form</h4>
                  </div>
                </div>
                <div class="row gy-4">
                  <div class="col-lg-7">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <form class="form-validate" id="withdrawal-application">
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title">Savings Variation Details</span>
                          </div>
                          <div class="row gy-3">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <label for="savings-type" class="form-label font-weight-bold">Savings Type <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <select name="savings_type" id="savings-type" class="form-select form-control" required>
                                    <option value="default">Default Value</option>
                                    <?php if (!empty($savings_types)): foreach ($savings_types as $savings_type):?>
                                      <option value="<?=$savings_type['contribution_type_id']?>">
                                        <?=$savings_type['contribution_type_name']?>
                                      </option>
                                    <?php endforeach; endif;?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="form-group">
                                <label for="variation-start-month" class="form-label font-weight-bold">Variation Start Month <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <select name="variation_start_month" id="variation-start-month" class="form-select form-control" required>
                                    <option value="default">Default Value</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="form-group">
                                <label for="variation-start-year" class="form-label font-weight-bold">Variation Start Year <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <select name="variation_start_year" id="variation-start-year" class="form-select form-control" required>
                                    <option value="default">Default Value</option>
                                    <?php for($i = date('Y'); $i <= date('Y')+5; $i++):?>
                                      <option value="<?=$i?>"><?=$i?></option>
                                    <?php endfor; ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="variation-amount" class="form-label font-weight-bold">Variation Amount <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control number" id="variation-amount" name="variation_amount" required>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <button class="btn btn btn-primary">Submit Application</button>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-5">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <div class="preview-block">
                          <span class="preview-title-lg overline-title">Savings Variation Terms</span>
                        </div>
                        <div class="alert alert-icon alert-info mt-1 mb-1" role="alert" id="get-started">
                          <em class="icon ni ni-alert-circle"></em> Please select a savings type to get started.
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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
<?php //include(APPPATH.'/Views/_withdrawal-application-script.php'); ?>
</body>
</html>
