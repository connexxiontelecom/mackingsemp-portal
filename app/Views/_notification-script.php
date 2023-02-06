<?php $session = session();?>
<script>
  $(document).ready(function () {
    function loadUnseenNotifications () {
      $.ajax({
        type: 'GET',
        url: '/get-user-notifications',
        dataType: 'json',
        success: function (data) {
          if (data) {
            $('.nk-notification').empty()
            if (data.length) {
              $('#notification-icon').addClass('icon-status-success')
              $.each(data, function (index, notification) {
                let notificationTime = new Date(notification.created_at).toLocaleString()
                $('.nk-notification').append(`
                <a href="/notifications/view-notification/${notification.notification_id}" class="nk-notification-item dropdown-inner">
                  <div class="nk-notification-icon">
                    <em class="icon icon-circle bg-warning-dim ni ni-clipboad-check"></em>
                  </div>
                  <div class="nk-notification-content">
                    <div class="nk-notification-text">${notification.topic}</div>
                    <div class="nk-notification-time">${notificationTime}</div>
                  </div>
                </a>
              `)
              })
            } else {
              $('#notification-icon').removeClass('icon-status-success')
              $('.nk-notification').append(`
                <div class="nk-notification-item dropdown-inner text-center">
                  <div class="nk-notification-content">
                    <div class="nk-notification-text">No Unread Notifications</div>
                  </div>
                </div>
              `)
            }
          }
        }
      })
    }
    loadUnseenNotifications()
    setInterval(function () {
      loadUnseenNotifications()
    }, 5000)

    $(document).on('click', '#confirm-guarantor', function(e) {
      e.preventDefault()
      let guarantorID = $('#loan-guarantor-id').val()
      Swal.fire({
        title: 'Are you sure?',
        text: 'Confirming as a guarantor is irreversible ',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirm Guarantor'
      }).then(function (result) {
        if (result.value) {
          $.ajax({
            url: '<?=site_url('loan-application/confirm-guarantor')?>',
            type: 'post',
            data: { loan_guarantor_id: guarantorID },
            success: function (data) {
              if (data.success) {
                Swal.fire('Confirmed!', data.msg, 'success').then(() => location.reload())
              } else {
                Swal.fire('Sorry!', data.msg, 'error')
              }
            }
          })
        }
      })
    })
    $(document).on('click', '#reject-guarantor', function(e) {
      e.preventDefault()
      let guarantorID = $('#loan-guarantor-id').val()
      Swal.fire({
        title: 'Are you sure?',
        text: 'Rejecting as a guarantor is irreversible',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Reject Guarantor'
      }).then(function (result) {
        if (result.value) {
          $.ajax({
            url: '<?=site_url('loan-application/reject-guarantor')?>',
            type: 'post',
            data: { loan_guarantor_id: guarantorID },
            success: function (data) {
              if (data.success) {
                Swal.fire('Confirmed!', data.msg, 'success').then(() => location.reload())
              } else {
                Swal.fire('Sorry!', data.msg, 'error')
              }
            }
          })
        }
      })
    })
  })
</script>
