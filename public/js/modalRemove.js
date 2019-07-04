$('#modalConfrim').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('whatever')
  $('#remove_confirm').attr('href',recipient)
})