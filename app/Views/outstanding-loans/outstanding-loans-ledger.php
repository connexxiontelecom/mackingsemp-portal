<?php
$session = session();
$loan_details = $outstanding_loan_details['loan_details'];
$no_activity = $outstanding_loan_details['no_activity'];
$loan_detail = $loan_details[0];
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
                  <div class="nk-block-head-sub"><a class="back-to" href="<?= site_url('outstanding-loans') ?>"><em
                        class="icon ni ni-arrow-left"></em><span>Outstanding Loans</span></a></div>
                  <h2 class="nk-block-title fw-normal">View Outstanding Loan</h2>
                  <div class="nk-block-des"></div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-block-head-content">
                    <h4 class="nk-block-title"><?= $loan_detail->loan_description ?> Ledger</h4>
                    <div class="nk-block-des">
                      <p>
                        This is the periodic summary for this loan as at today
                        <em>
                          <?php
                          $date = date_create();
                          echo date_format($date, 'd M Y');
                          ?>
                        </em>
                        .
                      </p>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-8">
                    <div class="card card-bordered  card-full">
                      <div class="card-inner">
                        <div class="card-title-group align-start mb-0">
                          <div class="card-title w-100 d-flex justify-content-between">
                            <h6 class="title">Loan Details</h6>
                            <button class="btn btn-secondary btn-sm"
                                    onclick="exportPdf('slbcoop_outstanding_loan_<?php
                                    $date = date_create();
                                    echo date_format($date, 'd_M_Y');
                                    ?>.pdf')">PDF
                            </button>
                          </div>
                        </div>
                        <div class="nk-block mt-2">
                          <div class="profile-ud-list">
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Loan Principal</span>
                                <span class="profile-ud-value"><?= number_format($loan_detail->amount, 2) ?></span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Interest Rate</span>
                                <span class="profile-ud-value"><?= number_format($loan_detail->ls_interest_rate, 2); ?> %</span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Expected Interest</span>
                                <span class="profile-ud-value"><?= number_format(0, 2); ?></span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Payment Duration</span>
                                <span class="profile-ud-value"><?= $loan_detail->duration ?> Month(s)</span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">New Duration</span>
                                <span class="profile-ud-value"><?= 0; ?></span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Application Date</span>
                                <span class="profile-ud-value">
                                  <?php
                                  $date = date_create($loan_detail->applied_date);
                                  echo date_format($date, 'd M Y')
                                  ?>
                                </span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Verified Date</span>
                                <span class="profile-ud-value">
                                   <?php
                                   $date = date_create($loan_detail->verify_date);
                                   echo date_format($date, 'd M Y')
                                   ?>
                                </span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Approved Date</span>
                                <span class="profile-ud-value">
                                  <?php
                                  $date = date_create($loan_detail->approve_date);
                                  echo date_format($date, 'd M Y')
                                  ?>
                                </span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Disbursed Date</span>
                                <span class="profile-ud-value">
                                   <?php
                                   $date = date_create($loan_detail->disburse_date);
                                   echo date_format($date, 'd M Y')
                                   ?>
                                </span>
                              </div>
                            </div>
                            <div class="profile-ud-item">
                              <div class="profile-ud wider">
                                <span class="profile-ud-label">Loan Start Date</span>
                                <span class="profile-ud-value">
                                  <?php
                                  $date = date_create($loan_detail->disburse_date);
                                  echo date_format($date, 'd M Y')
                                  ?>
                                </span>
                              </div>
                            </div>
                          </div><!-- .profile-ud-list -->
                        </div><!-- .nk-block -->
                      </div>
                    </div><!-- .card -->
                  </div><!-- .col -->
                </div>
                <div class="card card-bordered card-preview">
                  <table class="table table-tranx">
                    <thead>
                    <tr class="tb-tnx-head">
                      <th>#</th>
                      <th>Date</th>
                      <th>Narration</th>
                      <th class="text-right">DR</th>
                      <th class="text-right">CR</th>
                      <th class="text-right">Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($loan_details)): ?>
                      <tr class="tb-tnx-item">
                        <td colspan="6" class="text-center title">No Data Available</td>
                      </tr>
                    <?php else:
                      $sn = 1;
                      $total_cr = 0;
                      $total_dr = 0;
                      $disbursed_date = null;
                      if ($no_activity) {
                        // no activity on the disbursed loan yet
                        $total_dr = $loan_detail->amount;
                        $date = date_create($loan_detail->disburse_date);
                        $disbursed_date = date_format($date, 'd M Y');
                      } else {
                        foreach ($loan_details as $loan_detail) {
                          $total_dr = $loan_detail->amount;
                          $date = date_create($loan_detail->disburse_date);
                          $disbursed_date = date_format($date, 'd M Y');
                          $brought_forward = $loan_detail->amount;
                        }
                      }
                      ?>
                      <tr class="tb-tnx-item">
                        <td class="font-weight-bolder"><?= $sn ?></td>
                        <td class="font-weight-bolder"><?= $disbursed_date ?></td>
                        <td class="font-weight-bolder">Loan Disbursed</td>
                        <td class="text-right font-weight-bolder"><?= number_format($total_dr, 2); ?></td>
                        <td class="text-right font-weight-bolder"><?= number_format(0, 2); ?></td>
                        <td class="text-right font-weight-bolder"><?= number_format($total_dr, 2); ?></td>
                      </tr>
                      <?php if (!$no_activity): $sn++;
                      foreach ($loan_details as $loan_detail): ?>
                        <tr class="tb-tnx-item">
                          <td><?= $sn ?></td>
                          <td>
                            <?php
                            $date = date_create($loan_detail->lr_date);
                            echo date_format($date, 'd M Y');
                            ?>
                          </td>
                          <td>
                            <?= $loan_detail->lr_narration; ?>
                          </td>
                          <td class="text-right">
                            <?php
                            if ($loan_detail->lr_dctype == 2):
                              $dr = $loan_detail->lr_amount;
                              $cr = 0;
                              $total_dr += $dr;
                              echo number_format($loan_detail->lr_amount, 2);
                            else:
                              echo number_format(0, 2);
                            endif;
                            ?>
                          </td>
                          <td class="text-right">
                            <?php
                            if ($loan_detail->lr_dctype == 1):
                              $cr = $loan_detail->lr_amount;
                              $dr = 0;
                              $total_cr += $cr;
                              echo number_format($loan_detail->lr_amount, 2);
                            else:
                              echo number_format(0, 2);
                            endif;
                            ?>
                          </td>
                          <td class="text-right">
                            <?php
                            $brought_forward = ($brought_forward + $dr) - $cr;
                            echo number_format($brought_forward, 2);
                            ?>
                          </td>
                        </tr>
                        <?php $sn++; endforeach; endif; ?>
                      <tr class="tb-tnx-item font-weight-bolder">
                        <td>-</td>
                        <td></td>
                        <td>Total</td>
                        <td class="text-right">
                          <?= number_format($total_dr, 2); ?>
                        </td>
                        <td class="text-right">
                          <?= number_format($total_cr, 2); ?>
                        </td>
                        <td class="text-right">
                          <?= number_format($total_dr - $total_cr, 2); ?>
                        </td>
                      </tr>
                    <?php endif; ?>
                    </tbody>
                  </table>

                </div><!-- .card-preview -->
                <div class="d-none w-100">
                  <div id="print" class="w-100 pl-2 pr-4 pt-3">
                    <img class="logo-dark logo-img mb-3" src="/assets/images/logo.png" alt="logo-dark">
                    <h4 class="nk-block-title"><?= $loan_detail->loan_description ?> Ledger</h4>
                    <div class="nk-block-des">
                      <p>
                        This is the periodic summary for this loan as at today
                        <em>
                          <?php
                          $date = date_create();
                          echo date_format($date, 'd M Y');
                          ?>
                        </em>
                        .
                      </p>
                    </div>
                    <div class="mt-3">
                      <div class="card-title-group align-start mb-0">
                        <div class="card-title w-100 d-flex justify-content-between">
                          <h6 class="title">Loan Details</h6>
                        </div>
                      </div>
                      <div class="nk-block mt-2">
                        <div class="profile-ud-list">
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Loan Principal</span>
                              <span class="profile-ud-value"><?= number_format($loan_detail->amount, 2) ?></span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Interest Rate</span>
                              <span
                                class="profile-ud-value"><?= number_format($loan_detail->ls_interest_rate, 2); ?> %</span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Expected Interest</span>
                              <span class="profile-ud-value"><?= number_format(0, 2); ?></span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Payment Duration</span>
                              <span class="profile-ud-value"><?= $loan_detail->duration ?> Month(s)</span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">New Duration</span>
                              <span class="profile-ud-value"><?= 0; ?></span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Application Date</span>
                              <span class="profile-ud-value">
                                  <?php
                                  $date = date_create($loan_detail->applied_date);
                                  echo date_format($date, 'd M Y')
                                  ?>
                                </span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Verified Date</span>
                              <span class="profile-ud-value">
                                   <?php
                                   $date = date_create($loan_detail->verify_date);
                                   echo date_format($date, 'd M Y')
                                   ?>
                                </span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Approved Date</span>
                              <span class="profile-ud-value">
                                  <?php
                                  $date = date_create($loan_detail->approve_date);
                                  echo date_format($date, 'd M Y')
                                  ?>
                                </span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Disbursed Date</span>
                              <span class="profile-ud-value">
                                   <?php
                                   $date = date_create($loan_detail->disburse_date);
                                   echo date_format($date, 'd M Y')
                                   ?>
                                </span>
                            </div>
                          </div>
                          <div class="profile-ud-item">
                            <div class="profile-ud wider">
                              <span class="profile-ud-label">Loan Start Date</span>
                              <span class="profile-ud-value">
                                  <?php
                                  $date = date_create($loan_detail->disburse_date);
                                  echo date_format($date, 'd M Y')
                                  ?>
                                </span>
                            </div>
                          </div>
                        </div><!-- .profile-ud-list -->
                      </div><!-- .nk-block -->
                    </div>
                    <table class="mt-3 w-100">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Narration</th>
                        <th class="text-right">DR</th>
                        <th class="text-right">CR</th>
                        <th class="text-right">Balance</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php if (empty($loan_details)): ?>
                        <tr>
                          <td colspan="6" class="text-center title">No Data Available</td>
                        </tr>
                      <?php else:
                        $sn = 1;
                        $total_cr = 0;
                        $total_dr = 0;
                        $disbursed_date = null;
                        if ($no_activity) {
                          // no activity on the disbursed loan yet
                          $total_dr = $loan_detail->amount;
                          $date = date_create($loan_detail->disburse_date);
                          $disbursed_date = date_format($date, 'd M Y');
                        } else {
                          foreach ($loan_details as $loan_detail) {
                            $total_dr = $loan_detail->amount;
                            $date = date_create($loan_detail->disburse_date);
                            $disbursed_date = date_format($date, 'd M Y');
                            $brought_forward = $loan_detail->amount;
                          }
                        }
                        ?>
                        <tr>
                          <td class="font-weight-bolder"><?= $sn ?></td>
                          <td class="font-weight-bolder"><?= $disbursed_date ?></td>
                          <td class="font-weight-bolder">Loan Disbursed</td>
                          <td class="text-right font-weight-bolder"><?= number_format($total_dr, 2); ?></td>
                          <td class="text-right font-weight-bolder"><?= number_format(0, 2); ?></td>
                          <td class="text-right font-weight-bolder"><?= number_format($total_dr, 2); ?></td>
                        </tr>
                        <?php if (!$no_activity): $sn++;
                        foreach ($loan_details as $loan_detail): ?>
                          <tr>
                            <td><?= $sn ?></td>
                            <td>
                              <?php
                              $date = date_create($loan_detail->lr_date);
                              echo date_format($date, 'd M Y');
                              ?>
                            </td>
                            <td>
                              <?= $loan_detail->lr_narration; ?>
                            </td>
                            <td class="text-right">
                              <?php
                              if ($loan_detail->lr_dctype == 2):
                                $dr = $loan_detail->lr_amount;
                                $cr = 0;
                                $total_dr += $dr;
                                echo number_format($loan_detail->lr_amount, 2);
                              else:
                                echo number_format(0, 2);
                              endif;
                              ?>
                            </td>
                            <td class="text-right">
                              <?php
                              if ($loan_detail->lr_dctype == 1):
                                $cr = $loan_detail->lr_amount;
                                $dr = 0;
                                $total_cr += $cr;
                                echo number_format($loan_detail->lr_amount, 2);
                              else:
                                echo number_format(0, 2);
                              endif;
                              ?>
                            </td>
                            <td class="text-right">
                              <?php
                              $brought_forward = ($brought_forward + $dr) - $cr;
                              echo number_format($brought_forward, 2);
                              ?>
                            </td>
                          </tr>
                          <?php $sn++; endforeach; endif; ?>
                        <tr class=" font-weight-bolder">
                          <td>-</td>
                          <td></td>
                          <td>Total</td>
                          <td class="text-right">
                            <?= number_format($total_dr, 2); ?>
                          </td>
                          <td class="text-right">
                            <?= number_format($total_cr, 2); ?>
                          </td>
                          <td class="text-right">
                            <?= number_format($total_dr - $total_cr, 2); ?>
                          </td>
                        </tr>
                      <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div> <!-- nk-block -->
            </div><!-- .components-preview -->
          </div>
        </div>
      </div>
      <?php include(APPPATH . '/Views/_footer.php'); ?>
    </div>
  </div>
</div>
<?php include(APPPATH . '/Views/_scripts.php'); ?>
</body>
</html>