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
                        <div class="nk-content-head">
                            <div class="nk-block-between-md g-4">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title fw-normal">Account Statement</h2>
                                    <div class="nk-block-des">
                                        <p>View activity on your savings type here.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block nk-block-lg mt-3">
                            <div class="row g-gs">
                                <div class="col-lg-8">
                                    <div class="card card-bordered h-100">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-3">
                                                <div class="card-title">
                                                    <h6 class="title">Savings Types</h6>
                                                    <p class="mt-2">
                                                        Select a Savings Type and a date range to view comprehensive
                                                        account activity for the account during this date range.
                                                    </p>
                                                </div>
                                            </div><!-- .card-title-group -->
                                            <div class="nk-order-ovwg">
                                                <div class="row g-4 align-end">
                                                    <div class="col-xxl-8">
                                                        <form action="<?= site_url('account-statement/view-account-statement') ?>"
                                                              method="post" class="form-validate">
                                                            <div class="form-group mt-2">
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select form-control form-control-xl"
                                                                            data-ui="xl" id="savings-type"
                                                                            name="savings_type" required>
                                                                        <option value="default" class="bold">Default
                                                                            Option
                                                                        </option>
                                                                        <?php if (!empty($savings_accounts)): foreach ($savings_accounts as $savings_account): if ($savings_account): ?>
                                                                            <option value="<?= $savings_account['savings_type']['contribution_type_id'] ?>">
                                                                                <?= $savings_account['savings_type']['contribution_type_name'] ?>
                                                                            </option>
                                                                        <?php endif; endforeach; endif; ?>
                                                                    </select>
                                                                    <label class="form-label-outlined"
                                                                           for="savings-type">Select Savings
                                                                        Type</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-2">
                                                                <div class="form-control-wrap">
                                                                    <div class="form-icon form-icon-right">
                                                                        <em class="icon ni ni-calendar-alt"></em>
                                                                    </div>
                                                                    <input autocomplete="off" type="text"
                                                                           class="form-control form-control-xl form-control-outlined date-picker"
                                                                           id="start-date" name="start_date" required>
                                                                    <label class="form-label-outlined" for="start-date">Start
                                                                        Date</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <div class="form-icon form-icon-right">
                                                                        <em class="icon ni ni-calendar-alt"></em>
                                                                    </div>
                                                                    <input autocomplete="off" type="text"
                                                                           class="form-control form-control-xl form-control-outlined date-picker"
                                                                           id="end-date" name="end_date" required>
                                                                    <label class="form-label-outlined" for="end-date">End
                                                                        Date</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                           id="view-all" name="view_all" value="1">
                                                                    <label class="custom-control-label" for="view-all">Click
                                                                        to view all account activities</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary">Generate
                                                                    Statement
                                                                </button>
                                                                <button id="toast-btn" type="button" hidden
                                                                        class="no-payment-details"></button>
                                                            </div>
                                                        </form>
                                                    </div><!-- .col -->
                                                </div>
                                            </div><!-- .nk-order-ovwg -->
                                        </div><!-- .card-inner -->
                                    </div><!-- .card -->
                                </div><!-- .col -->
                            </div><!-- .row -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
            <?php include(APPPATH . '/Views/_footer.php'); ?>
        </div>
    </div>
</div>
<?php include(APPPATH . '/Views/_scripts.php'); ?>
<?php include(APPPATH . '/Views/_account-statement-script.php'); ?>
<?php if ($session->getFlashdata('no_payment_details')): ?>
    <script>
        $(document).ready(function () {
            $('.no-payment-details').click();
        })
    </script>
<?php endif; ?>
</body>
</html>
