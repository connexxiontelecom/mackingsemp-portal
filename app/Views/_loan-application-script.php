<?php $session = session() ?>

<script>
    let loanDuration = 0
    let loanMaxCreditLimit = 0
    let loanMinCreditLimit = 0
    let loanAgeQualification = 0
    let loanPSR = 0
    let loanPSRValue = 0
    let loanInterestRate = 0
    let loanInterestMethod = ''
    let loanInterestChargeType = ''
    let userApprovedDate = moment('<?= $session->get('approved_date')?>')
    //let savingsAmount = '<?php //= $session->get('regular_savings')?>//'
    let today = moment()
    let monthsDifference = today.diff(userApprovedDate, 'months', true)
    let encumbranceAmount = <?=$encumbrance_amount?>;
    let freeSavingsBalance = 0
    let waiverCharge = 0

    $(document).ready(function () {
        // Perform all these actions when user selects the loan type
        $(document).on('change', '#loan-type', function (e) {
            e.preventDefault()
            let loanType = $(this).val()
            if (loanType !== '' && loanType !== 'default') {
                $.ajax({
                    type: 'GET',
                    url: 'loan-application/get-loan-setup-details/' + loanType,
                    success: function (response) {
                        let loanSetupDetails = JSON.parse(response)
                        loanDuration = loanSetupDetails.max_repayment_periods
                        loanMaxCreditLimit = loanSetupDetails.max_credit_limit
                        loanMinCreditLimit = loanSetupDetails.min_credit_limit
                        loanAgeQualification = loanSetupDetails.age_qualification
                        loanPSR = loanSetupDetails.psr
                        loanPSRValue = loanSetupDetails.psr_value
                        loanInterestRate = loanSetupDetails.ls_interest_rate
                        if (loanSetupDetails.interest_method == 1) loanInterestMethod = 'Upfront'
                        else if (loanSetupDetails.interest_method == 2) loanInterestMethod = 'Reducing Balance'
                        else if (loanSetupDetails.interest_method == 3) loanInterestMethod = 'Targeted'

                        if (loanSetupDetails.interest_charge_type == 1) loanInterestChargeType = 'Flat'
                        else if (loanSetupDetails.interest_charge_type == 2) loanInterestChargeType = 'Monthly'
                        else if (loanSetupDetails.interest_charge_type == 3) loanInterestChargeType = 'Yearly'


                        $('#loan-details-list').html(`
              <li>${loanInterestRate}% Interest Rate</li>
              <li>${loanInterestMethod} Interest Method</li>
              <li>${loanInterestChargeType} Interest Charge Type</li>
            `)
                        $('#loan-type-note').html(`Loan Qualification Period <span class="text-primary">${loanAgeQualification} month(s)</span>`)
                        $('#loan-duration-note').html(`Maximum Repayment Period <span class="text-primary">${loanDuration} month(s)</span>`)
                        $('#loan-amount-note').html(`
              Minimum Credit Limit <span class="text-primary">${loanMinCreditLimit.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")} </span>
              ---
              Maximum Credit Limit <span class="text-primary">${loanMaxCreditLimit.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")} </span>
              ---
              Loan PSR <span class="text-primary">${loanPSRValue} % </span>
            `)
                        $('#get-started').attr('hidden', true)
                        $('#loan-details').attr('hidden', false)
                        if (monthsDifference > loanAgeQualification) {
                            // The user has been approved long enough to qualify for the loan type
                            $('#qualification-age-passed').attr('hidden', false)
                            $('#qualification-age-failed').attr('hidden', true)
                            $('#loan-duration').attr('disabled', false)
                            $('#loan-amount').attr('disabled', false)
                            $('#loan-attachment').attr('disabled', false)
                            $('#guarantor-1').attr('disabled', false)
                            $('#guarantor-2').attr('disabled', false)
                        } else {
                            $('#qualification-age-failed').attr('hidden', false)
                            $('#qualification-age-passed').attr('hidden', true)
                            $('#loan-duration').val('')
                            $('#loan-duration-passed').attr('hidden', true)
                            $('#loan-duration-failed').attr('hidden', true)
                            $('#loan-duration').attr('disabled', true)
                            $('#loan-amount').attr('disabled', true)
                            $('#loan-amount-passed').attr('hidden', true)
                            $('#loan-amount-failed').attr('hidden', true)
                            $('#loan-psr-passed').attr('hidden', true)
                            $('#loan-psr-failed').attr('hidden', true)
                            $('#loan-attachment').attr('disabled', true)
                            $('#guarantor-1').attr('disabled', true)
                            $('#guarantor-2').attr('disabled', true)
                        }
                    }
                })
            } else if (loanType === 'default') {
                $('#loan-type-note').html(``)
                $('#loan-duration-note').html(``)
                $('#loan-amount-note').html(``)
                $('#loan-details-list').html(``)
                $('#get-started').attr('hidden', false)
                $('#loan-duration').attr('disabled', true)
                $('#loan-amount').attr('disabled', true)
                $('#qualification-age-passed').attr('hidden', true)
                $('#qualification-age-failed').attr('hidden', true)
                $('#loan-duration').val('')
                $('#loan-duration-passed').attr('hidden', true)
                $('#loan-duration-failed').attr('hidden', true)
                $('#loan-amount').val('')
                $('#loan-amount-passed').attr('hidden', true)
                $('#loan-amount-failed').attr('hidden', true)
                $('#loan-psr-passed').attr('hidden', true)
                $('#loan-psr-failed').attr('hidden', true)
                $('#loan-attachment').attr('disabled', true)
                $('#guarantor-1').val('')
                $('#guarantor-2').val('')
                $('#guarantor-1').attr('disabled', true)
                $('#guarantor-2').attr('disabled', true)
                $('#loan-details').attr('hidden', true)
            }
        })

        // perform these when user enters loan duration
        $(document).on('keyup', '#loan-duration', function (e) {
            e.preventDefault()
            let selectedLoanDuration = $(this).val()
            if (selectedLoanDuration) {
                if (parseInt(selectedLoanDuration) <= loanDuration) {
                    $('#loan-duration-passed').attr('hidden', false)
                    $('#loan-duration-failed').attr('hidden', true)
                } else {
                    $('#loan-duration-failed').attr('hidden', false)
                    $('#loan-duration-passed').attr('hidden', true)
                }
            } else {
                $('#loan-duration-failed').attr('hidden', true)
                $('#loan-duration-passed').attr('hidden', true)
            }
        })

        // perform these when user enters an amount
        $(document).on('keyup', '#loan-amount', function (e) {
            e.preventDefault()
            let selectedLoanAmount = $(this).val()
            selectedLoanAmount = +(selectedLoanAmount.replace(/,/g, ''))
            if (selectedLoanAmount) {
                if (selectedLoanAmount >= loanMinCreditLimit && selectedLoanAmount <= loanMaxCreditLimit) {
                    $('#loan-amount-passed').attr('hidden', false)
                    $('#loan-amount-failed').attr('hidden', true)
                } else {
                    $('#loan-amount-failed').attr('hidden', false)
                    $('#loan-amount-passed').attr('hidden', true)

                }
                if (parseInt(loanPSR) > 0) {
                    freeSavingsBalance = savingsAmount - encumbranceAmount
                    let loanPSRAmount = (parseInt(loanPSRValue) / 100) * selectedLoanAmount
                    if (loanPSRAmount <= freeSavingsBalance) {
                        $('#loan-psr-passed').attr('hidden', false)
                        $('#loan-psr-failed').attr('hidden', true)
                    } else {
                        let allowedLoanAmount = freeSavingsBalance / (parseInt(loanPSRValue) / 100)
                        waiverCharge = (selectedLoanAmount - allowedLoanAmount) * 0.10
                        $('#loan-psr-passed').attr('hidden', true)
                        $('#loan-psr-failed').attr('hidden', false)
                        $('#loan-psr-failed').html(`<em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">Warning</span>. You do not have enough PSR for this loan. You will be charged a waiver charge of ${(waiverCharge).toLocaleString()}`)
                    }
                }
            } else {
                $('#loan-amount-failed').attr('hidden', true)
                $('#loan-amount-passed').attr('hidden', true)
                $('#loan-psr-passed').attr('hidden', true)
                $('#loan-psr-failed').attr('hidden', true)
            }
        })

        $(document).on('blur', '#guarantor-1', function (e) {
            e.preventDefault()
            $('#guarantor-1-note').html(``)
            let guarantor1 = $(this).val()
            let guarantor2 = $('#guarantor-2').val()
            $.ajax({
                url: '<?=site_url('loan-application/check-guarantor')?>',
                type: 'post',
                dataType: 'json',
                data: {
                    guarantor1,
                    guarantor2,
                    type: 'guarantor1'
                },
                success: function (data) {
                    if (data.success) {
                        $('#guarantor-1-note').html(`
              1st Guarantor: <span class="font-weight-bold text-primary"> ${data.guarantor.cooperator_first_name} ${data.guarantor.cooperator_last_name} </span> will be notified
            `)
                    } else {
                        $('#guarantor-1-note').html(`
              <span class="font-weight-bold text-danger">We didn't find a cooperator with that Staff ID (or you've already chosen this cooperator)</span>
            `)
                    }
                }
            })
        })

        $(document).on('blur', '#guarantor-2', function (e) {
            e.preventDefault()
            $('#guarantor-2-note').html(``)
            let guarantor1 = $('#guarantor-1').val()
            let guarantor2 = $(this).val()
            $.ajax({
                url: '<?=site_url('loan-application/check-guarantor')?>',
                type: 'post',
                dataType: 'json',
                data: {
                    guarantor1,
                    guarantor2,
                    type: 'guarantor2'
                },
                success: function (data) {
                    if (data.success) {
                        $('#guarantor-2-note').html(`
              2nd Guarantor: <span class="font-weight-bold text-primary"> ${data.guarantor.cooperator_first_name} ${data.guarantor.cooperator_last_name} </span> will be notified
            `)
                    } else {
                        $('#guarantor-2-note').html(`
              <span class="font-weight-bold text-danger">We didn't find a cooperator with that Staff ID (or you've already chosen this cooperator)</span>
            `)
                    }
                }
            })
        })

        // loan application form submission
        $('form#loan-application').submit(function (e) {
            e.preventDefault()
            let loanType = $('#loan-type').val()
            let loanDuration = $('#loan-duration').val()
            let loanAmount = $('#loan-amount').val()
            let loanAttachment = $('#loan-attachment').val()
            let guarantor1 = $('#guarantor-1').val()
            let guarantor2 = $('#guarantor-2').val()

            if (!loanType || loanType === 'default') {
                Swal.fire("Invalid Submission", "Please select a valid loan type!", "error");
            } else if (!loanDuration || !loanAmount || !loanAttachment || !guarantor1 || !guarantor2) {
                Swal.fire("Invalid Submission", "Please fill in all required fields!", "error");
            } else {
                const formData = new FormData(this)
                formData.set('loan_amount', formData.get('loan_amount').replace(/,/g, ''))
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Application for loan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm Loan'
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '<?=site_url('loan-application/submit-application')?>',
                            type: 'post',
                            data: formData,
                            success: function (data) {
                                if (data.success) {
                                    Swal.fire('Confirmed!', data.msg, 'success').then(() => location.reload())
                                } else {
                                    Swal.fire('Sorry!', data.msg, 'error')
                                }
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        })
                    }
                });
            }
        })
    })
</script>
