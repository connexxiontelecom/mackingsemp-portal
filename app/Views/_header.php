<?php

$session = session();
?>
<div class="nk-header nk-header-fluid nk-header-fixed is-light">
  <div class="container-fluid">
    <div class="nk-header-wrap">
      <div class="nk-menu-trigger d-xl-none ml-n1">
        <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
            class="icon ni ni-menu"></em></a>
      </div>
      <div class="nk-sidebar-brand d-xl-none">
        <a href="/dashboard" class="logo-link text-center">
          <img class="logo-light logo-img" src="/assets/images/logo.png" alt="logo">
          <img class="logo-dark logo-img" src="/assets/images/logo.png" alt="logo-dark">
        </a>
      </div>
      <div class="nk-header-news d-none d-xl-block">
        <div class="nk-news-list">
          <a class="nk-news-item" href="https://mackingsemp.com" target="_blank">
            <div class="nk-news-icon">
              <em class="icon ni ni-card-view"></em>
            </div>
            <div class="nk-news-text">
              <p>Mackings Empowerment Initiative
              </p>
              <em class="icon ni ni-external ml-1"></em>
            </div>
          </a>
        </div>
      </div>
      <div class="nk-header-tools">
        <ul class="nk-quick-nav">
          <li class="dropdown user-dropdown">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
              <div class="user-toggle">
                  <?php
                  if (!$cooperator['cooperator_passport']): ?>
                    <div class="user-avatar sm">
                    <span>
                      <?= mb_substr($session->get('firstname'), 0,
                        1) . '' . mb_substr($session->get('lastname'), 0, 1) ?>
                    </span>
                    </div>
                  <?php
                  else: ?>
                    <div class="nk-block-text mr-2">
                      <img src="<?= getenv('S3_BASE_URL') . $cooperator['cooperator_passport'] ?>"
                           style="width: 2em; object-fit: fill"
                           alt="display picture" class="img-fluid circle">
                    </div>
                  <?php
                  endif ?>
                <div class="user-info d-none d-md-block">
                    <?php
                    if ($session->get('status') == 2): ?>
                      <div class="user-status user-status-verified">Active</div>
                    <?php
                    endif; ?>
                  <div class="user-name dropdown-indicator">
                      <?= $session->get('firstname') . ' ' . $session->get('lastname') ?>
                  </div>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
              <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                <div class="user-card">
                    <?php
                    if (!$cooperator['cooperator_passport']): ?>
                      <div class="user-avatar">
                        <span>
                          <?= mb_substr($session->get('firstname'), 0,
                            1) . '' . mb_substr($session->get('lastname'), 0, 1) ?>
                        </span>
                      </div>
                    <?php
                    else: ?>
                      <div class="nk-block-text mr-2">
                        <img src="<?= getenv('S3_BASE_URL') . $cooperator['cooperator_passport'] ?>"
                             style="width: 3em"
                             alt="display picture" class="img-fluid circle">
                      </div>
                    <?php
                    endif ?>
                  <div class="user-info">
                    <span class="lead-text"><?= $session->get('firstname') . ' ' . $session->get('lastname') ?></span>
                    <span class="sub-text"><?= $session->get('email') ?></span>
                  </div>
                </div>
              </div>
              <div class="dropdown-inner user-account-info">
                <h6 class="overline-title-alt"><?= $session->get('regular_savings')['name'] ?></h6>
                <div class="user-balance"><em
                    class="icon ni ni-sign-kobo"></em> <?= number_format($session->get('regular_savings')['amount'],
                      2) ?>
                </div>
                <a href="/withdrawal-application" class="link mt-2"><span>Withdraw Funds</span> <em
                    class="icon ni ni-wallet-out"></em></a>
              </div>
              <div class="dropdown-inner">
                <ul class="link-list">
                  <li><a href="/account"><em class="icon ni ni-user-alt"></em><span>My Account</span></a>
                  </li>
                </ul>
              </div>
              <div class="dropdown-inner">
                <ul class="link-list">
                  <li><a href="<?= site_url('logout') ?>"><em class="icon ni ni-signout"></em><span>Logout</span></a>
                  </li>
                </ul>
              </div>
            </div>
          </li>
          <li class="dropdown notification-dropdown mr-n1">
            <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
              <div id="notification-icon" class="icon-status"><em class="icon ni ni-bell"></em></div>
            </a>
            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1">
              <div class="dropdown-head">
                <span class="sub-title nk-dropdown-title font-weight-bolder">Notifications</span>
              </div>
              <div class="dropdown-body">
                <div class="nk-notification">
                </div>
              </div>
              <div class="dropdown-foot center">
                <a href="/notifications">View All</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
