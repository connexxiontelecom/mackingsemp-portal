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
                  <h2 class="nk-block-title fw-normal">Finished Loans</h2>
                  <div class="nk-block-des">
                    <p>View activity on your finished loans here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-blood-head-content">
                    <h4 class="nk-block-title">Your Finished Loans</h4>
                  </div>
                </div>
                <div class="card card-preview">
                  <div class="card-inner">
                    <table class="datatable-init table">
                      <thead>
                      <tr>
                        <th>Loan Type</th>
                        <th class="text-right">Principal</th>
                        <th class="text-right">Interest</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Outstanding</th>
                        <th class="text-right">Encumbered</th>
                        <th class="text-right">View</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($finished_loans)): foreach ($finished_loans as $finished_loan): ?>
                          <tr>
                            <td>
                              <?=$finished_loan['loan_type']?>
                            </td>
                            <td class="text-right">
                              <?=number_format($finished_loan['loan_principal'], 2)?>
                            </td>
                            <td class="text-right">
                              <?=number_format($finished_loan['total_interest'], 2)?>
                            </td>
                            <td class="text-right">
                              <?=number_format($finished_loan['total_cr'], 2)?>
                            </td>
                            <td class="text-right">
                              <?=number_format($finished_loan['loan_balance'], 2)?>
                            </td>
                            <td class="text-right">
                              <?=number_format(0, 2)?>
                            </td>
                            <td class="text-right">
                              <a href="<?=site_url('finished-loans/view-finished-loan/'.$finished_loan['loan_id'])?>" class="btn btn-icon btn-trigger">
                                <em class="icon ni ni-more-h"></em>
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; endif;?>
                      </tbody>
                    </table>
                  </div>
                </div><!-- .card-preview -->
              </div> <!-- nk-block -->
            </div><!-- .components-preview -->
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
