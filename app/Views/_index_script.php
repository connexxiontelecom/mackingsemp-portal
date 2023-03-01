<script>
  $(document).ready(function () {
    $('form#new-savings-account-form').submit(function (e) {
      e.preventDefault()
      let accountType = $('#sa_account_type').val()
      let reason = $('#sa_reason').val()
      let agree = $('#account-agree').is(":checked")
      if (!accountType || !reason) {
        Swal.fire("Invalid Submission", 'Please fill in all required fields!', "error")
      } else if (!agree) {
        Swal.fire("Invalid Submission", 'You must agree to the account creation fee to create this account!', "error")
      } else {
        const fd = new FormData(this)
        Swal.fire({
          title: 'Are you sure?',
          text: 'Create a new savings account',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Confirm Account'
        }).then(function (result) {
          if (result.value) {
            $.ajax({
              url: '<?= site_url('savings-account')?>',
              type: 'post',
              data: fd,
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

    $('#sa_account_type').on('change', function (e) {
      let ctId = $(this).val()
      $.ajax({
        url: `savings-account/fee/${ctId}`,
        type: 'get',
        success: function (data) {
          const contributionType = JSON.parse(data)
          $('#savings-account-fee').html(`
            <em class="icon ni ni-alert-circle"></em>
            Please note, you will be charged a one-time fee of NGN ${contributionType.fee} to open this account type.
          `)
          $('#savings-account-fee').attr('hidden', false)
        },
      })
    })
  })
</script>