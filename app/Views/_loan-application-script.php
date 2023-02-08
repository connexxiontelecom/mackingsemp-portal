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
    let group
    let equity
    let management
    let insurance
    let durationType
    let intDuration

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
                        console.log({loanSetupDetails})
                        loanDuration = loanSetupDetails.max_repayment_periods
                        loanMaxCreditLimit = loanSetupDetails.max_credit_limit
                        loanMinCreditLimit = loanSetupDetails.min_credit_limit
                        loanAgeQualification = loanSetupDetails.age_qualification
                        loanPSR = loanSetupDetails.psr
                        loanPSRValue = loanSetupDetails.psr_value
                        loanInterestRate = loanSetupDetails.ls_interest_rate
                        group = loanSetupDetails.group
                        equity = loanSetupDetails.equity
                        management = loanSetupDetails.management
                        insurance = loanSetupDetails.insurance
                        intDuration = loanSetupDetails.int_duration
                        durationType = loanSetupDetails.duration_type

                        if (durationType === 'M') {
                            $('#loan-duration-m').show()
                            $('#loan-duration-d').hide()
                            intDuration.forEach(item => {
                                let text
                                text = `${item.lid_duration} months - `
                                item.lid_rate_type === '1' ? text = `${text}${item.lid_rate}% interest rate` : text = `${text}N${item.lid_rate} flat interest`
                                $('#loan-duration').append(`
                                    <option value="${item.lid_id}">
                                        ${text}
                                    </option>
                                `)
                            })
                            durationType = 'Months'
                        } else if (durationType === 'D') {
                            $('#loan-duration-m').hide()
                            $('#loan-duration-d').show()
                            durationType = 'Days'
                        }

                        $('#loan-details-list').html(`
                          <li>Cooperator Group: ${group ? group.gs_name : '_'} (${group ? group.gs_code : ''})</li>
                          <li>Qualification Period: ${loanAgeQualification} month(s)</li>
                          <li>Interest Duration Type: ${durationType}</li>
                        `)

                        if (equity && equity.length) {
                            $('#loan-details-list').append(`
                                <li id="equity-req">
                                    Equity Fee Requirement <br>
                                </li>
                            `)
                            equity.forEach(eq => {
                                let rate, from, to
                                const {lf_from, lf_to, lf_rate, lf_rate_type} = eq
                                if (lf_rate_type === '1') rate = `${lf_rate}%`
                                else rate = `N${lf_rate}`
                                from = parseInt(lf_from).toLocaleString()
                                to = parseInt(lf_to).toLocaleString()

                                $('#equity-req').append(`
                                    <small class="">N${from} to N${to} - ${rate}</small>
                                    <br>
                                `)
                            })
                        }

                        if (management && management.length) {
                            $('#loan-details-list').append(`
                                <li id="management-req">
                                    Management Fee Requirement <br>
                                </li>
                            `)
                            management.forEach(eq => {
                                let rate, from, to
                                const {lf_from, lf_to, lf_rate, lf_rate_type} = eq
                                if (lf_rate_type === '1') rate = `${lf_rate}%`
                                else rate = `N${lf_rate}`
                                from = parseInt(lf_from).toLocaleString()
                                to = parseInt(lf_to).toLocaleString()

                                $('#management-req').append(`
                                    <small class="">N${from} to N${to} - ${rate}</small>
                                    <br>
                                `)
                            })
                        }

                        if (insurance && insurance.length) {
                            $('#loan-details-list').append(`
                                <li id="insurance-req">
                                    Insurance Fee Requirement <br>
                                </li>
                            `)
                            insurance.forEach(eq => {
                                let rate, from, to
                                const {lf_from, lf_to, lf_rate, lf_rate_type} = eq
                                if (lf_rate_type === '1') rate = `${lf_rate}%`
                                else rate = `N${lf_rate}`
                                from = parseInt(lf_from).toLocaleString()
                                to = parseInt(lf_to).toLocaleString()

                                $('#insurance-req').append(`
                                    <small class="">N${from} to N${to} - ${rate}</small>
                                    <br>
                                `)
                            })
                        }


                        // $('#loan-type-note').html(`Loan Qualification Period <span class="text-primary">${loanAgeQualification} month(s)</span>`)
                        // $('#loan-duration-note').html(`Maximum Repayment Period <span class="text-primary">${loanDuration} month(s)</span>`)
                        // $('#loan-amount-note').html(`
                        //   Minimum Credit Limit <span class="text-primary">${loanMinCreditLimit.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")} </span>
                        //   ---
                        //   Maximum Credit Limit <span class="text-primary">${loanMaxCreditLimit.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")} </span>
                        //   ---
                        //   Loan PSR <span class="text-primary">${loanPSRValue} % </span>
                        // `)
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
                $('#loan-duration').html(` <option value="default">Default Option</option>`)
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
        // $(document).on('keyup', '#loan-duration', function (e) {
        //     e.preventDefault()
        //     let selectedLoanDuration = $(this).val()
        //     if (selectedLoanDuration) {
        //         if (parseInt(selectedLoanDuration) <= loanDuration) {
        //             $('#loan-duration-passed').attr('hidden', false)
        //             $('#loan-duration-failed').attr('hidden', true)
        //         } else {
        //             $('#loan-duration-failed').attr('hidden', false)
        //             $('#loan-duration-passed').attr('hidden', true)
        //         }
        //     } else {
        //         $('#loan-duration-failed').attr('hidden', true)
        //         $('#loan-duration-passed').attr('hidden', true)
        //     }
        // })

        // perform these when user enters an amount
        // $(document).on('keyup', '#loan-amount', function (e) {
        //     e.preventDefault()
        //     let selectedLoanAmount = $(this).val()
        //     selectedLoanAmount = +(selectedLoanAmount.replace(/,/g, ''))
        //     if (selectedLoanAmount) {
        //         if (selectedLoanAmount >= loanMinCreditLimit && selectedLoanAmount <= loanMaxCreditLimit) {
        //             $('#loan-amount-passed').attr('hidden', false)
        //             $('#loan-amount-failed').attr('hidden', true)
        //         } else {
        //             $('#loan-amount-failed').attr('hidden', false)
        //             $('#loan-amount-passed').attr('hidden', true)
        //
        //         }
        //         if (parseInt(loanPSR) > 0) {
        //             freeSavingsBalance = savingsAmount - encumbranceAmount
        //             let loanPSRAmount = (parseInt(loanPSRValue) / 100) * selectedLoanAmount
        //             if (loanPSRAmount <= freeSavingsBalance) {
        //                 $('#loan-psr-passed').attr('hidden', false)
        //                 $('#loan-psr-failed').attr('hidden', true)
        //             } else {
        //                 let allowedLoanAmount = freeSavingsBalance / (parseInt(loanPSRValue) / 100)
        //                 waiverCharge = (selectedLoanAmount - allowedLoanAmount) * 0.10
        //                 $('#loan-psr-passed').attr('hidden', true)
        //                 $('#loan-psr-failed').attr('hidden', false)
        //                 $('#loan-psr-failed').html(`<em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">Warning</span>. You do not have enough PSR for this loan. You will be charged a waiver charge of ${(waiverCharge).toLocaleString()}`)
        //             }
        //         }
        //     } else {
        //         $('#loan-amount-failed').attr('hidden', true)
        //         $('#loan-amount-passed').attr('hidden', true)
        //         $('#loan-psr-passed').attr('hidden', true)
        //         $('#loan-psr-failed').attr('hidden', true)
        //     }
        // })

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

        $('#guarantor-2').autocomplete({
            source: '<?= site_url('loan-application/check-external-guarantor')?>'
        })

        //$(document).on('blur', '#guarantor-2', function (e) {
        //    e.preventDefault()
        //    $('#guarantor-2-note').html(``)
        //    let guarantor1 = $('#guarantor-1').val()
        //    let guarantor2 = $(this).val()
        //    $.ajax({
        //        url: '<?php //=site_url('loan-application/check-guarantor')?>//',
        //        type: 'post',
        //        dataType: 'json',
        //        data: {
        //            guarantor1,
        //            guarantor2,
        //            type: 'guarantor2'
        //        },
        //        success: function (data) {
        //            if (data.success) {
        //                $('#guarantor-2-note').html(`
        //      2nd Guarantor: <span class="font-weight-bold text-primary"> ${data.guarantor.cooperator_first_name} ${data.guarantor.cooperator_last_name} </span> will be notified
        //    `)
        //            } else {
        //                $('#guarantor-2-note').html(`
        //      <span class="font-weight-bold text-danger">We didn't find a cooperator with that Staff ID (or you've already chosen this cooperator)</span>
        //    `)
        //            }
        //        }
        //    })
        //})

        // loan application form submission
        $('form#loan-application').submit(function (e) {
            e.preventDefault()
            let loanType = $('#loan-type').val()
            let loanDuration = $('#loan-duration').val()
            let loanAmount = $('#loan-amount').val()
            let guarantor1 = $('#guarantor-1').val()
            let guarantor2 = $('#guarantor-2').val()

            if (!loanType || loanType === 'default') {
                Swal.fire("Invalid Submission", "Please select a valid loan type!", "error");
            } else if (!loanDuration || !loanAmount || !guarantor1 || !guarantor2) {
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
