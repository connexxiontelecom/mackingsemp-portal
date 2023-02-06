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
                  <h2 class="nk-block-title fw-normal">Loan Application</h2>
                  <div class="nk-block-des">
                    <p>Submit a new loan application here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-blood-head-content">
                    <h4 class="nk-block-title">New Loan Application Form </h4>
                  </div>
                </div>
                <div class="row gy-4">
                  <div class="col-lg-7">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <form enctype="multipart/form-data" class="form-validate" id="loan-application">
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title">Loan Details</span>
                          </div>
                          <div class="row gy-3">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <label class="form-label font-weight-bolder" for="loan-type">Loan Type <span
                                    class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <select class="form-select form-control" data-search="on" id="loan-type"
                                          name="loan_type" required <?php if (!$bank): ?>disabled<?php endif; ?>>
                                    <option value="default">Default Option</option>
                                    <?php if (!empty($loan_types)): foreach ($loan_types as $loan_type): ?>
                                      <option value="<?= $loan_type['loan_setup_id'] ?>">
                                        <?= $loan_type['loan_description'] ?>
                                      </option>
                                    <?php endforeach; endif; ?>
                                  </select>
                                  <div id="loan-type-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder" for="loan-duration">Loan Duration (Months)
                                  <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control" id="loan-duration" name="loan_duration"
                                         required disabled>
                                  <div id="loan-duration-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder" for="loan-amount">Loan Amount <span
                                    class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control number" id="loan-amount" name="loan_amount"
                                         required disabled>
                                  <div id="loan-amount-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder">File Attachment <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="loan-attachment"
                                           name="loan_attachment" required disabled>
                                    <label class="custom-file-label" for="loan-attachment">Choose file</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title mt-5">Guarantor Details</span>
                          </div>
                          <div class="row gy-3">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <label class="form-label font-weight-bolder" for="guarantor-1">1st Guarantor (Staff ID)
                                  <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control" id="guarantor-1" name="guarantor_1" disabled>
                                  <div id="guarantor-1-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder" for="guarantor-2">2nd Guarantor (Staff ID)
                                  <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control" id="guarantor-2" name="guarantor_2" disabled>
                                  <div id="guarantor-2-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <button class="btn btn-primary">Submit Application</button>
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
                          <span class="preview-title-lg overline-title">Loan Terms</span>
                        </div>
                        <?php if (!$bank): ?>
                          <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="get-started">
                            <em class="icon ni ni-alert-circle"></em> Sorry, you have not updated your bank information
                            and cannot proceed. Please, update your bank information details or contact the secretariat
                          </div>
                        <?php else: ?>
                          <div class="alert alert-icon alert-info mt-1 mb-1" role="alert" id="get-started">
                            <em class="icon ni ni-alert-circle"></em> Please select a loan type to get started.
                          </div>
                        <?php endif; ?>

                        <div class="alert alert-icon alert-secondary mt-1 mb-1" role="alert" id="loan-details" hidden>
                          <em class="icon ni ni-alert-circle"></em> These are the details for your selected loan
                          <ul class="list list-sm list-checked" id="loan-details-list"></ul>
                        </div>
                        <!--qualification age-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="qualification-age-passed"
                             hidden>
                          <em class="icon ni ni-check-circle"></em> You have been a member long enough to qualify for
                          this loan.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="qualification-age-failed"
                             hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>.
                          You have not been a member long enough to qualify for this loan
                        </div>
                        <!--loan duration-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="loan-duration-passed"
                             hidden>
                          <em class="icon ni ni-check-circle"></em> Your selected loan duration is valid.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="loan-duration-failed"
                             hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>.
                          Your loan duration exceeds the repayment period for this loan.
                        </div>
                        <!--loan amount-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="loan-amount-passed"
                             hidden>
                          <em class="icon ni ni-check-circle"></em> Your selected loan amount is valid.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="loan-amount-failed"
                             hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>.
                          Your loan amount does not fall within the credit limit range.
                        </div>
                        <!--psr amount-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="loan-psr-passed" hidden>
                          <em class="icon ni ni-check-circle"></em> Your PSR loan amount is valid.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="loan-psr-failed" hidden>

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
<?php include(APPPATH . '/Views/_loan-application-script.php') ?>
</body>
</html>
