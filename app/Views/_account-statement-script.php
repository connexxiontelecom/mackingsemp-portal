<script>
  $(document).ready(function () {

    $(document).on('change', '#view-all', function(e) {
      e.preventDefault()
      if (this.checked) {
        $('#start-date').attr('disabled', true)
        $('#end-date').attr('disabled', true)
      } else {
        $('#start-date').attr('disabled', false)
        $('#end-date').attr('disabled', false)
      }
    })
  })
</script>