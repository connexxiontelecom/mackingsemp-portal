<!DOCTYPE html>
<html lang="zxx" class="js">
<?php include(APPPATH . '/Views/_head.php'); ?>
<body class="nk-body npc-crypto bg-white pg-auth">
<!-- app body @s -->
<div class="nk-app-root">
    <div class="nk-main">
        <!-- wrap @s -->
        <div class="nk-wrap nk-wrap-nosidebar">
            <!-- content @s -->
            <div class="nk-content">
                <div class="nk-block nk-block-middle nk-auth-body wide-xl">
                    <div class="brand-logo pb-4 text-center">
                        <a href="/" class="logo-link ml-n5">
                            <img class="logo-light logo-img logo-img-lg" src="/assets/images/logo.png" alt="logo">
                            <img class="logo-dark ml-n4" style="width: 5em !important;"
                                 src="/assets/images/logo.png" alt="logo-dark">
                        </a>
                    </div>
                    <div class="kyc-app wide-sm m-auto">
                        <div class="nk-block-head nk-block-head-lg wide-xl mx-auto">
                            <div class="nk-block-head-content text-center">
                                <h2 class="nk-block-title fw-normal">Account Opening Form</h2>
                                <div class="nk-block-des">
                                    <p>To complete registration on the platform, each coop member will have to go
                                        through identity
                                        verification. Our team will get back to you with a response once the process is
                                        completed.</p>
                                </div>
                            </div>
                        </div><!-- nk-block-head -->
                        <div class="nk-block">
                            <form class="card form-validate" id="membership-form">
                                <div class="nk-kycfm">
                                    <div class="nk-kycfm-head">
                                        <div class="nk-kycfm-count">01</div>
                                        <div class="nk-kycfm-title">
                                            <h5 class="title">Personal Information</h5>
                                            <p class="sub-title">Your basic personal information required for
                                                identification</p>
                                        </div>
                                    </div><!-- .nk-kycfm-head -->
                                    <div class="nk-kycfm-content">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_first_name">First
                                                            Name <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_first_name"
                                                               id="application_first_name" required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_last_name">Last Name
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_last_name"
                                                               id="application_last_name" required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_other_name">Other
                                                            Name</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_other_name"
                                                               id="application_other_name">
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_email">Email Address
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="email" class="form-control"
                                                               name="application_email" id="application_email"
                                                               required>
                                                        <div class="form-note">Please enter a valid email we will use to
                                                            get back to you.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_gender">Gender <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <select class="form-control form-select" data-search="on"
                                                                name="application_gender"
                                                                id="application_gender" required>
                                                            <option value="male">Male</option>
                                                            <option value="female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_group">Group <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <select class="form-control form-select" data-search="on"
                                                                name="application_group"
                                                                id="application_group" required>
                                                            <?php foreach ($groups as $group): ?>
                                                                <option
                                                                        value="<?= $group['gs_id']; ?>"><?= $group['gs_name']; ?>
                                                                    (<?= $group['gs_code'] ?>)
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_location_id">State of
                                                            Origin
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <select class="form-control form-select" data-search="on"
                                                                name="application_location_id"
                                                                id="application_location_id" required>
                                                            <?php foreach ($states as $state): ?>
                                                                <option
                                                                        value="<?= $state['state_id']; ?>"><?= $state['state_name']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_nationality">
                                                            Nationality
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_nationality"
                                                               id="application_nationality"
                                                               required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_dob">Date of Birth
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control date-picker-alt"
                                                               name="application_dob"
                                                               id="application_dob">
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                    </div><!-- .nk-kycfm-content -->
                                    <div class="nk-kycfm-head">
                                        <div class="nk-kycfm-count">02</div>
                                        <div class="nk-kycfm-title">
                                            <h5 class="title">Contact Information</h5>
                                            <p class="sub-title">Your basic contact information required for
                                                identification</p>
                                        </div>
                                    </div><!-- .nk-kycfm-head -->
                                    <div class="nk-kycfm-content">
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_address">Address
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <textarea class="form-control no-resize"
                                                                  name="application_address"
                                                                  id="application_address" rows="1"
                                                                  required></textarea>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_city">City <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control" name="application_city"
                                                               id="application_city"
                                                               required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_state_id">State <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <select class="form-control form-select" data-search="on"
                                                                name="application_state_id"
                                                                id="application_state_id" required>
                                                            <?php foreach ($states as $state): ?>
                                                                <option
                                                                        value="<?= $state['state_id']; ?>"><?= $state['state_name']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_telephone">Phone
                                                            Number <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_telephone"
                                                               id="application_telephone"
                                                               required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_alternate_telephone">Alternate
                                                            Phone Number</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_alternate_telephone"
                                                               id="application_alternate_telephone"
                                                        >
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                    </div><!-- .nk-kycfm-content -->
                                    <div class="nk-kycfm-head">
                                        <div class="nk-kycfm-count">03</div>
                                        <div class="nk-kycfm-title">
                                            <h5 class="title">Next of Kin</h5>
                                            <p class="sub-title">Your next of kin information required for
                                                verification.</p>
                                        </div>
                                    </div><!-- .nk-kycfm-head -->
                                    <div class="nk-kycfm-content">
                                        <div class="nk-kycfm-note">
                                            <em class="icon ni ni-info-fill" data-toggle="tooltip" data-placement="top"
                                                title="First next of kin"></em>
                                            <p>1st Next of Kin.</p>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin_fullname">Full
                                                            Name </label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_kin_fullname"
                                                               id="application_kin_fullname">
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin_relationship">Relationship</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <select class="form-control form-select" data-search="on"
                                                                name="application_kin_relationship"
                                                                id="application_kin_relationship">
                                                            <option
                                                                    value="sibling">Sibling
                                                            </option>
                                                            <option
                                                                    value="spouse">Spouse
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin_email">Email
                                                            Address</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="email" class="form-control"
                                                               name="application_kin_email"
                                                               id="application_kin_email"
                                                        >
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin_phone">Phone
                                                            Number</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_kin_phone"
                                                               id="application_kin_phone"
                                                        >
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label"
                                                               for="application_kin_address">Address</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <textarea class="form-control no-resize"
                                                                  name="application_kin_address"
                                                                  id="application_kin_address" rows="2"
                                                        ></textarea>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                        <div class="nk-kycfm-note mt-4">
                                            <em class="icon ni ni-info-fill" data-toggle="tooltip" data-placement="top"
                                                title="Second next of kin"></em>
                                            <p>2nd Next of Kin.</p>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin2_fullname">Full
                                                            Name </label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_kin2_fullname"
                                                               id="application_kin2_fullname">
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin2_relationship">Relationship</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <select class="form-control form-select" data-search="on"
                                                                name="application_kin2_relationship"
                                                                id="application_kin2_relationship">
                                                            <option
                                                                    value="sibling">Sibling
                                                            </option>
                                                            <option
                                                                    value="spouse">Spouse
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin2_email">Email
                                                            Address</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="email" class="form-control"
                                                               name="application_kin2_email"
                                                               id="application_kin2_email"
                                                        >
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_kin2_phone">Phone
                                                            Number</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_kin2_phone"
                                                               id="application_kin2_phone"
                                                        >
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label"
                                                               for="application_kin2_address">Address</label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <textarea class="form-control no-resize"
                                                                  name="application_kin2_address"
                                                                  id="application_kin2_address" rows="2"
                                                        ></textarea>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                    </div><!-- .nk-kycfm-content -->
                                    <div class="nk-kycfm-head">
                                        <div class="nk-kycfm-count">04</div>
                                        <div class="nk-kycfm-title">
                                            <h5 class="title">Bank Information</h5>
                                            <p class="sub-title">Your basic bank information required for
                                                identification</p>
                                        </div>
                                    </div><!-- .nk-kycfm-head -->
                                    <div class="nk-kycfm-content">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_bank_id">Bank <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <select class="form-control form-select" data-search="on"
                                                                name="application_bank_id"
                                                                id="application_bank_id" required>
                                                            <?php foreach ($banks as $bank): ?>
                                                                <option
                                                                        value="<?= $bank['bank_id']; ?>"><?= $bank['bank_name']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_bank_branch">Bank
                                                            Branch <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_bank_branch"
                                                               id="application_bank_branch" required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_account_number">Account
                                                            Number <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_account_number"
                                                               id="application_account_number" required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_sort_code">Sort Code
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_sort_code"
                                                               id="application_sort_code" required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-label-group">
                                                        <label class="form-label" for="application_bvn">BVN
                                                            <span
                                                                    class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="form-control-group">
                                                        <input type="text" class="form-control"
                                                               name="application_bvn"
                                                               id="application_bvn" required>
                                                    </div>
                                                </div>
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                    </div><!-- .nk-kycfm-content -->
                                    <div class="nk-kycfm-head">
                                        <div class="nk-kycfm-count">05</div>
                                        <div class="nk-kycfm-title">
                                            <h5 class="title">File Attachments</h5>
                                            <p class="sub-title">
                                                To further verify your identity, please upload any of your documents.
                                            </p>
                                        </div>
                                    </div><!-- .nk-kycfm-head -->
                                    <div class="nk-kycfm-content">
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bolder">Upload
                                                        Passport </label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input"
                                                                   id="upload-passport"
                                                                   name="application_upload_passport">
                                                            <label class="custom-file-label" for="upload-passport">Choose
                                                                file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bolder">Upload
                                                        ID Card </label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input"
                                                                   id="upload-card"
                                                                   name="application_upload_card">
                                                            <label class="custom-file-label" for="upload-card">Choose
                                                                file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bolder">Authorized
                                                        Signature </label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input"
                                                                   id="authorized-signature"
                                                                   name="application_authorized_signature">
                                                            <label class="custom-file-label" for="authorized-signature">Choose
                                                                file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .row -->
                                    </div><!-- .nk-kycfm-content -->

                                    <div class="nk-kycfm-footer">
                                        <div class="form-note-alt mb-2 mt-n2">
                                            <em class="icon ni ni-info-fill"></em>
                                            Please review all your information and ensure they are
                                            accurate before submitting.
                                        </div>
                                        <div class="form-note-alt mb-2"><em class="icon ni ni-info-fill"></em> Already a
                                            member? <a
                                                    href="/auth/login">Login to your account
                                                here</a>
                                        </div>
                                        <div class="nk-kycfm-action pt-2">
                                            <button type="submit" class="btn btn-primary">Submit Membership Form
                                            </button>
                                        </div>
                                    </div><!-- .nk-kycfm-footer -->
                                </div><!-- .nk-kycfm -->
                            </form><!-- .card -->
                        </div><!-- nk-block -->
                    </div><!-- kyc-app -->

                </div>
                <?php include(APPPATH . '/Views/_footer.php'); ?>
            </div>
            <!-- wrap @e -->
        </div>
        <!-- content @e -->
    </div>
</div><!-- app body @e -->
<!-- JavaScript -->
<?php include(APPPATH . '/Views/_scripts.php'); ?>
<?php include(APPPATH . '/Views/_auth_script.php'); ?>
</body>

</html>