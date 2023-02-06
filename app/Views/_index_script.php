<script>
    $(document).ready(function () {
        $('form#new-savings-account-form').submit(function (e) {
            e.preventDefault()
            let accountType = $('#sa_account_type').val()
            let reason = $('#sa_reason').val()
            if (!accountType || !reason) {
                Swal.fire("Invalid Submission", 'Please fill in all required fields!', "error")
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
    })
</script>