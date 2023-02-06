<script>
  let schemes = []
  let schemeTable = []
  let schemeCount = 0
  let schemeTotal = 0

  $(document).ready(function () {

    // select scheme type
    $(document).on('change', '#scheme-type', function (e) {
      let schemeType = $(this).val()
      let schemeAmount = $('#scheme-amount').val()
      let scheme = $('#scheme').val()
      let addScheme = $('#add-scheme')
      if (schemeType && schemeType !== 'default' && scheme && scheme !== 'default' && schemeAmount) {
        addScheme.attr('disabled', false)
      } else {
        addScheme.attr('disabled', true)
      }
      switch (schemeType) {
        case '1':
          $.ajax({
            type: 'GET',
            url: 'deposit-lodgement/get-active-loans',
            success: function(responseData) {
              const { ledgers } = responseData
              let scheme = $('#scheme')
              scheme.empty()
              scheme.append(`<option value="default">Default Value</option>`)
              ledgers.forEach(ledger => {
                scheme.append(
                  `<option value="${ledger.loan_id}">
                     ${ledger.loan_type.loan_description} - Principal: ${ledger.loan_principal}, Outstanding: ${ledger.loan_balance}
                  </option>`
                )
              })
            }
          })
          break;
        case '2':
          $.ajax({
            type: 'GET',
            url: 'deposit-lodgement/get-savings-types',
            success: function(responseData) {
              const { savings_types } = responseData
              let scheme = $('#scheme')
              scheme.empty()
              scheme.append(`<option value="default">Default Value</option>`)
              savings_types.forEach(type => {
                scheme.append(
                  `<option value="${type.contribution_type_id}">
                     ${type.contribution_type_name}
                  </option>`
                )
              })
            }
          })
          break;
        default:
          let scheme = $('#scheme')
          scheme.empty()
          scheme.append(`<option value="default">Default Value</option>`)
          break
      }
    })

    // enter scheme amount
    $(document).on('keyup', '#scheme-amount', function () {
      let schemeAmount = $(this).val()
      let addScheme = $('#add-scheme')

      if (schemeAmount) {
        let schemeType = $('#scheme-type').val()
        let scheme = $('#scheme').val()
        if (schemeType && schemeType !== 'default' && scheme && scheme !== 'default') {
          addScheme.attr('disabled', false)
        }
      } else {
        addScheme.attr('disabled', true)
      }
    })

    // select value of scheme
    $(document).on('change', '#scheme', function () {
      let scheme = $(this).val()
      let addScheme = $('#add-scheme')

      if (scheme && scheme !== 'default') {
        let schemeAmount = $('#scheme-amount').val()
        let schemeType = $('#scheme-type').val()
        if (schemeAmount && schemeType && schemeType !== 'default') {
          addScheme.attr('disabled', false)
        }
      } else {
        addScheme.attr('disabled', true)
      }
    })

    // add scheme to table
    $(document).on('click', '#add-scheme', function () {
      let schemeType = $('#scheme-type').val()
      let schemeAmount = $('#scheme-amount').val()
      let scheme = $('#scheme').val()
      $('#scheme-table tbody').empty()

      schemeTable = [
        ...schemeTable,
        {
          sn: ++schemeCount,
          schemeLabel: $('#scheme option:selected').prop('label'),
          schemeType,
          scheme,
          schemeAmount
        }
      ]
      schemeTable.forEach((entry, index)=> {
        let sn = index
        const rowContent = `
          <tr>
            <th>${++sn}</th>
            <th>${entry.schemeLabel}</th>
            <th>${entry.schemeAmount}</th>
            <th>
              <span onclick="delPurpose(${index})" style="cursor: pointer">
                <em class="icon ni ni-trash" style="font-size: 1.5em"></em>
              </span>
            </th>
          </tr>
        `
        $('#scheme-table tbody').append(rowContent)
      })
      schemeTotal += parseFloat(schemeAmount.replace(/,/g, ''))

      schemeType = $('#scheme-type')
      schemeType.empty()
      schemeType.append(
        `
          <option value="default">Default Value</option>
          <option value="1">Loan</option>
          <option value="2">Savings</option>
        `
      )
      scheme = $('#scheme')
      scheme.empty()
      scheme.append(
        `
          <option value="default">Default Value</option>
        `
      )
      $('#scheme-amount').val('')
      $('#add-scheme').attr('disabled', true)
      checkPaymentIsEqual()
    })

    // is payment amount == breakdown sum?
    $(document).on('keyup', '#payment-amount', checkPaymentIsEqual)

    // submit deposit/lodgement
    $('form#deposit-lodgement').submit(function (e) {
      e.preventDefault()
      let paymentType = $('#payment-type').val()
      let coopBank = $('#coop-bank').val()
      let paymentDate = $('#payment-date').val()
      let paymentAmount = $('#payment-amount').val()
      let paymentProof = $('#payment-proof').val()

      if (!paymentType || paymentType === 'default') {
        Swal.fire('Invalid Submission', 'Please select a valid payment type!', 'error')
      } else if (!coopBank || coopBank === 'default') {
        Swal.fire('Invalid Submission', 'Please select a valid coop bank!', 'error')
      } else if (!paymentDate || !paymentAmount || !paymentProof) {
        Swal.fire('Invalid Submission', 'Please fill in all required fields!!', 'error')
      } else {
        const formData = new FormData(this)
        formData.set('payment_amount', formData.get('payment_amount').replace(/,/g, ''))
        formData.set('purpose_breakdown', JSON.stringify(schemeTable))
        Swal.fire({
          title: 'Are you sure?',
          text: 'Submission of deposit/lodgement',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Confirm Deposit'
        }).then(function (result) {
          if (result.value) {
            $.ajax({
              url: '<?=site_url('deposit-lodgement/submit-deposit')?>',
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

  function delPurpose(index) {
    schemeTotal -=  parseFloat(schemeTable[index].schemeAmount.replace(/,/g, ''))
    schemeTable.splice(index, 1)
    $('#scheme-table tbody').empty()
    schemeTable.forEach((entry, index)=> {
      let sn = index
      const rowContent = `
          <tr>
            <th>${++sn}</th>
            <th>${entry.schemeLabel}</th>
            <th>${entry.schemeAmount}</th>
            <th>
              <span onclick="delPurpose(${index})" style="cursor: pointer">
                <em class="icon ni ni-trash" style="font-size: 1.5em"></em>
              </span>
            </th>
          </tr>
        `
      $('#scheme-table tbody').append(rowContent)
    })
    if (schemeTable.length === 0) {
      const rowContent = `
        <tr>
          <th colspan="4">Enter scheme details</th>
        </tr>
      `
      $('#scheme-table tbody').append(rowContent)
    }
    checkPaymentIsEqual()
  }

  function checkPaymentIsEqual() {
    let paymentAmount = $('#payment-amount').val()
    paymentAmount = parseFloat(paymentAmount.replace(/,/g, ''))
    console.log({paymentAmount})
    console.log({schemeTotal})
    if (paymentAmount === schemeTotal) {
      $('#amounts-unequal').attr('hidden', true)
      $('#submit-deposit').attr('disabled', false)
    } else {
      $('#amounts-unequal').attr('hidden', false)
      $('#submit-deposit').attr('disabled', true)
    }
  }
</script>