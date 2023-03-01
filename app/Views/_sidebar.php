<?php

$session = session();
//  $uri = service('uri');
?>
<div class="nk-sidebar nk-sidebar-fixed " data-content="sidebarMenu">
  <div class="nk-sidebar-element nk-sidebar-head">
    <div class="nk-sidebar-brand">
      <a href="/dashboard" class="logo-link text-center">
        <!--        <img class="logo-light logo-img" src="/assets/images/logo.pngg" alt="logo">-->
        <img class="logo-dark logo-img" src="/assets/images/logo.png" alt="logo-dark">
        <!--        <span class="nio-version text-dark">Portal</span>-->
      </a>
    </div>
    <div class="nk-menu-trigger mr-n2">
      <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em
          class="icon ni ni-arrow-left"></em></a>
    </div>
  </div><!-- .nk-sidebar-element -->
  <div class="nk-sidebar-element">
    <div class="nk-sidebar-body" data-simplebar>
      <div class="nk-sidebar-content">
        <div class="nk-sidebar-widget d-none d-xl-block">
          <div class="user-account-info between-center">
            <div class="user-account-main">
              <h6 class="overline-title-alt"><?= $regular_savings['name'] ?></h6>
              <div class="user-balance"><em
                  class="icon ni ni-sign-kobo"></em> <?= number_format($regular_savings['amount'], 2) ?>
              </div>
            </div>
            <a href="#" class="btn btn-white btn-icon btn-light"><em class="icon ni ni-line-chart"></em></a>
          </div>
          <ul class="user-account-data gy-1">
            <li>
              <div class="user-account-label">
                <span class="sub-text">Free Savings</span>
              </div>
              <div class="user-account-value">
                                <span class="lead-text"><em
                                    class="icon ni ni-sign-kobo"></em> <?= number_format($regular_savings['amount'] - $encumbered_amount,
                                      2, '.', ',') ?></span>
              </div>
            </li>
            <li>
              <div class="user-account-label">
                <span class="sub-text">Encumbered Amount</span>
              </div>
              <div class="user-account-value">
                                <span class="lead-text"><em
                                    class="icon ni ni-sign-kobo"></em> <?= number_format($encumbered_amount, 2, '.',
                                      ',') ?></span>
              </div>
            </li>
          </ul>
          <div class="user-account-actions">
            <ul class="g-3">
              <li><a href="/loan-application" class="btn btn-lg btn-primary"><span>Loan</span></a></li>
              <li><a href="/withdrawal-application"
                     class="btn btn-lg btn-warning"><span>Withdraw</span></a></li>
            </ul>
          </div>
        </div><!-- .nk-sidebar-widget -->
        <div class="nk-sidebar-widget nk-sidebar-widget-full d-xl-none pt-0">
          <a class="nk-profile-toggle toggle-expand" data-target="sidebarProfile" href="#">
            <div class="user-card-wrap">
              <div class="user-card">
                  <?php
                  if (!$cooperator['cooperator_passport']): ?>
                    <div class="user-avatar">
                  <span>
                      <?= mb_substr($session->get('firstname'), 0, 1) . '' . mb_substr($session->get('lastname'), 0,
                        1) ?>
                    </span>
                    </div>
                  <?php
                  else: ?>
                    <div class="nk-block-text mr-2">
                      <img src="/uploads/passports/<?= $cooperator['cooperator_passport'] ?>"
                           style="width: 3em"
                           alt="display picture" class="img-fluid circle">
                    </div>
                  <?php
                  endif ?>
                <div class="user-info">
                  <span class="lead-text"><?= $session->get('firstname') . ' ' . $session->get('lastname') ?></span>
                  <span class="sub-text"><?= $session->get('email') ?></span>
                </div>
                <div class="user-action">
                  <em class="icon ni ni-chevron-down"></em>
                </div>
              </div>
            </div>
          </a>
          <div class="nk-profile-content toggle-expand-content" data-content="sidebarProfile">
            <div class="user-account-info between-center">
              <div class="user-account-main">
                <h6 class="overline-title-alt"><?= $regular_savings['name'] ?></h6>
                <div class="user-balance">
                  <em class="icon ni ni-sign-kobo"></em>
                    <?= number_format($regular_savings['amount'], 2) ?>
                </div>
              </div>
              <a href="#" class="btn btn-icon btn-light"><em class="icon ni ni-line-chart"></em></a>
            </div>
            <ul class="user-account-data">
              <li>
                <div class="user-account-label">
                  <span class="sub-text">Free Savings</span>
                </div>
                <div class="user-account-value">
                  <span class="lead-text"><em
                      class="icon ni ni-sign-kobo"></em> <?= number_format($regular_savings['amount'] - $encumbered_amount,
                        2, '.', ',') ?></span>
                </div>
              </li>
              <li>
                <div class="user-account-label">
                  <span class="sub-text">Encumbered Amount</span>
                </div>
                <div class="user-account-value">
                  <span class="lead-text"><em
                      class="icon ni ni-sign-kobo"></em> <?= number_format($encumbered_amount, 2, '.', ',') ?></span>
                </div>
              </li>
            </ul>
            <ul class="user-account-links">
              <li><a href="/withdrawal-application" class="link"><span>Withdraw Funds</span> <em
                    class="icon ni ni-wallet-out"></em></a></li>
              <li><a href="/loan-application" class="link"><span>Loan Funds</span> <em
                    class="icon ni ni-wallet-in"></em></a></li>
            </ul>
            <ul class="link-list">
              <li><a href="/account"><em class="icon ni ni-user-alt"></em><span>My Account</span></a></li>
            </ul>
            <ul class="link-list">
              <li><a href="<?= site_url('logout') ?>"><em
                    class="icon ni ni-signout"></em><span>Logout</span></a></li>
            </ul>
          </div>
        </div><!-- .nk-sidebar-widget -->
        <div class="nk-sidebar-menu">
          <!-- Menu -->
          <ul class="nk-menu">
            <li class="nk-menu-heading">
              <h6 class="overline-title">Menu</h6>
            </li>
            <li class="nk-menu-item">
              <a href="/dashboard" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                <span class="nk-menu-text">Dashboard</span>
              </a>
            </li>
            <li class="nk-menu-item">
              <a href="/account-statement" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-file-text"></em></span>
                <span class="nk-menu-text">Account Statement</span>
              </a>
            </li>
            <li class="nk-menu-item">
              <a href="/outstanding-loans" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-wallet-alt"></em></span>
                <span class="nk-menu-text">Outstanding Loans</span>
              </a>
            </li>
            <li class="nk-menu-item">
              <a href="/finished-loans" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-coins"></em></span>
                <span class="nk-menu-text">Finished Loans</span>
              </a>
            </li>
            <li class="nk-menu-item has-sub">
              <a href="javascript:void(0)" class="nk-menu-link nk-menu-toggle">
                <span class="nk-menu-icon"><em class="icon ni ni-files"></em></span>
                <span class="nk-menu-text">Service Forms</span>
              </a>
              <ul class="nk-menu-sub">
                <li class="nk-menu-item">
                  <a href="/loan-application" class="nk-menu-link"><span
                      class="nk-menu-text">Loan Application</span></a>
                </li>
                <li class="nk-menu-item">
                  <a href="/withdrawal-application" class="nk-menu-link"><span class="nk-menu-text">Withdrawal Application</span></a>
                </li>
                <!--                                <li class="nk-menu-item">-->
                <!--                                    <a href="/savings-variation" class="nk-menu-link"><span class="nk-menu-text">Savings Variation</span></a>-->
                <!--                                </li>-->
                <li class="nk-menu-item">
                  <a href="/deposit-lodgement" class="nk-menu-link"><span class="nk-menu-text">Deposits/Lodgement</span></a>
                </li>
                <!--                                <li class="nk-menu-item">-->
                <!--                                    <a href="#" class="nk-menu-link"><span class="nk-menu-text">Closure Form</span></a>-->
                <!--                                </li>-->
              </ul><!-- .nk-menu-sub -->
            </li><!-- .nk-menu-item -->
            <li class="nk-menu-item">
              <a href="#" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-calc"></em></span>
                <span class="nk-menu-text">Loan Calculator</span>
              </a>
            </li>
            <li class="nk-menu-item">
              <a href="#" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-notice"></em></span>
                <span class="nk-menu-text">Feedback</span>
              </a>
            </li>
            <li class="nk-menu-item">
              <a href="/account" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
                <span class="nk-menu-text">My Account</span>
              </a>
            </li>
          </ul><!-- .nk-menu -->
        </div><!-- .nk-sidebar-menu -->
        <div class="nk-sidebar-widget">
          <div class="widget-title">
            <h6 class="overline-title">Your Savings Types</h6>
            <!--            <a href="#" class="link">View All</a>-->
          </div>
            <?php
            if (!empty($savings_types_amounts_list)): ?>
              <ul class="wallet-list">
                  <?php
                  foreach ($savings_types_amounts_list as $savings_type => $amount): ?>
                    <li class="wallet-item">
                      <a href="/account-statement">
                        <div class="wallet-icon"><em class="icon ni ni-sign-kobo"></em></div>
                        <div class="wallet-text">
                          <h6 class="wallet-name"><?= $savings_type ?></h6>
                          <span class="wallet-balance"><?= number_format($amount, 2) ?> </span>
                        </div>
                      </a>
                    </li>
                  <?php
                  endforeach; ?>
              </ul>
            <?php
            endif; ?>
        </div><!-- .nk-sidebar-widget -->
        <div class="nk-sidebar-footer">
          <ul class="nk-menu nk-menu-footer">
            <li class="nk-menu-item ml-auto">
              <a href="<?= site_url('logout') ?>" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-signout"></em></span>
                <span class="nk-menu-text">Logout</span>
              </a>
            </li>
          </ul><!-- .nk-footer-menu -->
        </div><!-- .nk-sidebar-footer -->
      </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-body -->
  </div><!-- .nk-sidebar-element -->
</div>
