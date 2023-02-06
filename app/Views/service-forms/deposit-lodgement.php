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
                  <h2 class="nk-block-title fw-normal">Deposits/Lodgement</h2>
                  <div class="nk-block-des">
                    <p>Submit a new deposit here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-blood-head-content">
                    <h4 class="nk-block-title">New Deposit Form</h4>
                  </div>
                </div>
                <form enctype="multipart/form-data" class="form-validate" id="deposit-lodgement">
                  <div class="row gy-4">
                    <div class="col-lg-6">
                      <div class="card card-preview">
                        <div class="card-inner">
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title">Deposit Details</span>
                          </div>
                          <div class="row gy-3">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <label for="payment-type" class="form-label font-weight-bold">Payment Type <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <select name="payment_type" id="payment-type" class="form-select form-control" required>
                                    <option value="default">Default Value</option>
                                    <option value="1">Cash</option>
                                    <option value="2">Cheque</option>
                                    <option value="3">Transfer</option>
                                    <option value="4">Draft</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="coop-bank" class="form-label font-weight-bold">Coop Bank <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <select name="coop_bank" id="coop-bank" class="form-select form-control" required>
                                    <option value="default">Default Value</option>
                                    <?php if (!empty($coop_banks)): foreach ($coop_banks as $coop_bank):?>
                                      <option value="<?=$coop_bank['coop_bank_id']?>">
                                        <?=$coop_bank['account_no']?>, <?=$coop_bank['bank']['bank_name']?> - <?=$coop_bank['description']?>
                                      </option>
                                    <?php endforeach; endif;?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="payment-date" class="form-label font-weight-bold">Payment Date <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control date-picker" id="payment-date" name="payment_date" placeholder="Select date of payment" required>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="payment-amount" class="form-label font-weight-bold">Payment Amount <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control number" id="payment-amount" name="payment_amount" required>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="payment-proof" class="form-label font-weight-bold">Proof of Payment <span class="text-danger"> *</span></label>
                                <div class="form-control-wrap">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="payment-proof" name="payment_proof" required>
                                    <label class="custom-file-label" for="payment-proof">Choose file</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="preview-block mt-5">
                            <span class="preview-title-lg overline-title">Purpose</span>
                          </div>
                          <div class="row gy-3">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <label for="scheme-type" class="form-label font-weight-bold">Scheme Type</label>
                                <div class="form-control-wrap">
                                  <select name="scheme_type" id="scheme-type" class="form-select form-control">
                                    <option value="default">Default Value</option>
                                    <option value="1">Loan</option>
                                    <option value="2">Savings</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="scheme" class="form-label font-weight-bold">Scheme</label>
                                <div class="form-control-wrap">
                                  <select name="scheme" id="scheme" class="form-select form-control">
                                    <option value="default">Default Value</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="scheme-amount" class="form-label font-weight-bold">Amount</label>
                                <div class="form-control-wrap">
                                  <input type="text" class="form-control number" id="scheme-amount" name="scheme_amount">
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <button type="button" class="btn btn-sm btn-primary" id="add-scheme" disabled>Add</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="card card-preview">
                        <div class="card-inner">
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title">Purpose Breakdown</span>
                          </div>
                          <div class="row">
                            <div class="col-12">
                              <table class="mt-4 table table-bordered" id="scheme-table">
                                <thead class="thead-light">
                                <tr>
                                  <th scope="col" width="10%">S/n</th>
                                  <th scope="col" width="70%">Scheme</th>
                                  <th scope="col" width="20%">Amount</th>
                                  <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <th colspan="4">Enter scheme details</th>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <div class="col-12">
                              <div class="alert alert-icon alert-warning mt-2 mb-1" role="alert" id="amounts-unequal">
                                <em class="icon ni ni-alert-circle"></em> Please note, the purpose breakdown amount sum must equal <strong>Payment Amount</strong> to submit this deposit.
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <button class="btn btn-primary" id="submit-deposit" disabled>Submit Deposit</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
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
<?php include(APPPATH.'/Views/_deposit-lodgement-script.php')?>
</body>
</html>
