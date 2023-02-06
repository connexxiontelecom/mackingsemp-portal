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
                  <h2 class="nk-block-title fw-normal">Withdrawal Application</h2>
                  <div class="nk-block-des">
                    <p>Submit a new withdrawal application here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-blood-head-content">
                    <h4 class="nk-block-title">New Withdrawal Application Form</h4>
                  </div>
                </div>
                <div class="row gy-4">
                  <div class="col-lg-7">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <form class="form-validate" id="withdrawal-application">
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title">Withdrawal Details</span>
                          </div>
                          <div class="row gy-3">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <label for="savings-type" class="form-label font-weight-bold">Savings Type <span
                                    class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <select name="savings_type" id="savings-type" class="form-select form-control"
                                          required <?php if (!$bank): ?>disabled<?php endif; ?>>
                                    <option value="default">Default Value</option>
                                    <?php if (!empty($savings_types)): foreach ($savings_types as $savings_type): ?>
                                      <option value="<?= $savings_type['contribution_type_id'] ?>">
                                        <?= $savings_type['contribution_type_name'] ?>
                                      </option>
                                    <?php endforeach; endif; ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="withdrawal-amount" class="form-label font-weight-bold">Withdrawal Amount
                                  <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control number" id="withdrawal-amount"
                                         name="withdrawal_amount" required disabled>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder">File Attachment</label>
                                <div class="form-control-wrap">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="withdrawal-attachment"
                                           name="withdrawal_attachment" disabled>
                                    <label class="custom-file-label" for="withdrawal-attachment">Choose file</label>
                                  </div>
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
                          <span class="preview-title-lg overline-title">Withdrawal Terms</span>
                        </div>
                        <?php if (!$bank): ?>
                          <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="get-started">
                            <em class="icon ni ni-alert-circle"></em> Sorry, you have not updated your bank information
                            and cannot proceed. Please, update your bank information details or contact the secretariat
                          </div>
                        <?php else: ?>
                          <div class="alert alert-icon alert-info mt-1 mb-1" role="alert" id="get-started">
                            <em class="icon ni ni-alert-circle"></em> Please select a savings type to get started.
                          </div>
                        <?php endif; ?>


                        <div class="alert alert-icon alert-secondary mt-1 mb-1" role="alert" id="withdraw-details"
                             hidden>
                          <em class="icon ni ni-alert-circle"></em> These are the withdrawal details for your savings
                          account
                          <ul class="list list-sm list-checked pt-2" id="savings-details-list"></ul>
                        </div>
                        <!--withdrawal amount-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="withdrawal-amount-passed"
                             hidden>
                          <em class="icon ni ni-check-circle"></em> Your selected withdrawal amount is valid.
                          <div class="mt-1" id="withdrawal-amount-details"></div>
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="withdrawal-amount-failed"
                             hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>.
                          Your withdrawal amount exceeds the amount you can withdraw.
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
      <?php include(APPPATH . '/Views/_footer.php'); ?>
    </div>
  </div>
</div>
<?php include(APPPATH . '/Views/_scripts.php'); ?>
<?php include(APPPATH . '/Views/_withdrawal-application-script.php'); ?>
</body>
</html>
