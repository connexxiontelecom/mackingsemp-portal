<?php $session = session(); ?>

<script>
  let encumberedAmount = 0
  let savingsAmount = 0
  let withdrawableAmount = 0
  let withdrawalCharge = 0

  $(document).ready(function () {
    // perform all these actions when a user selects a withdrawal type
    $(document).on('change', '#savings-type', function (e) {
      e.preventDefault()
      let savingsType = $(this).val()
      if (savingsType !== '' && savingsType !== 'default') {
        $.ajax({
          type: 'get',
          url: `withdrawal-application/compute-balance/${savingsType}`,
          success: function (response) {
            if (response.success) {
              console.log(response)
              encumberedAmount = response.encumbered_amount
              savingsAmount = response.savings_amount
              withdrawableAmount = response.withdrawable_amount
              withdrawalCharge = +(response.withdrawal_charge)

              $('#savings-details-list').html(`
                <li>Withdrawable Amount <strong class="float-right font-weight-bold">${withdrawableAmount.toLocaleString()}</strong></li>
                <li>Encumbered Amount <strong class="float-right font-weight-bold">${encumberedAmount.toLocaleString()}</strong></li>
                <li>Savings Amount <strong class="float-right font-weight-bold">${savingsAmount.toLocaleString()}</strong> </li>
              `)
            }
            $('#get-started').attr('hidden', true)
            $('#withdraw-details').attr('hidden', false)
            $('#withdrawal-amount').attr('disabled', false)
            $('#withdrawal-attachment').attr('disabled', false)
          }
        })
      } else if (savingsType === 'default') {
        $('#savings-details-list').html(``)
        $('#get-started').attr('hidden', false)
        $('#withdraw-details').attr('hidden', true)
        $('#withdrawal-amount').attr('disabled', true)
        $('#withdrawal-attachment').attr('disabled', true)
      }
    })

    // perform all these actions when a user enters a withdrawal amount
    $(document).on('keyup', '#withdrawal-amount', function (e) {
      e.preventDefault()
      let selectedWithdrawalAmount = $(this).val()
      selectedWithdrawalAmount = +(selectedWithdrawalAmount.replace(/,/g, ''))
      if (selectedWithdrawalAmount) {
        let currentCharge = (withdrawalCharge / 100) * selectedWithdrawalAmount
        currentCharge = currentCharge.toLocaleString()
        if (selectedWithdrawalAmount <= withdrawableAmount) {
          $('#withdrawal-amount-passed').attr('hidden', false)
          $('#withdrawal-amount-failed').attr('hidden', true)
          // $('#withdrawal-amount-details').attr('hidden', false)
          $('#withdrawal-amount-details').html(`
            Withdrawal Charge <strong class="float-right font-weight-bold">${currentCharge}</strong>
          `)
        } else if (selectedWithdrawalAmount > withdrawableAmount) {
          $('#withdrawal-amount-passed').attr('hidden', true)
          $('#withdrawal-amount-failed').attr('hidden', false)
          $('#withdrawal-amount-details').html(``)
        }
      } else {
        $('#withdrawal-amount-passed').attr('hidden', true)
        $('#withdrawal-amount-failed').attr('hidden', true)
        $('#withdrawal-amount-details').html(``)
      }
    })

    $('form#withdrawal-application').submit(function (e) {
      e.preventDefault()
      let savingsType = $('#savings-type').val()
      let withdrawalAmount = $('#withdrawal-amount').val()
      let withdrawalAttachment = $('#withdrawal-attachment').val()
      if (!savingsType || savingsType === 'default'){
        Swal.fire("Invalid Submission", "Please select a valid savings type!", "error");
      } else if (!withdrawalAmount || !withdrawalAttachment) {
        Swal.fire("Invalid Submission", "Please fill in all required fields!", "error");
      } else {
        const formData = new FormData(this)
        formData.set('withdrawal_amount', formData.get('withdrawal_amount').replace(/,/g, ''))
        formData.append('withdrawable_amount', withdrawableAmount)
        Swal.fire({
          title: 'Are you sure?',
          text: 'Application for Withdrawal',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Confirm Withdrawal'
        }).then(function (confirm) {
          if (confirm.value) {
            $.ajax({
              url: '<?=site_url('withdrawal-application/submit-application')?>',
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
        })
      }
    })

  })
</script>
