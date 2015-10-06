$(function () {
  var showReorderError = function () {
    alert('There was a problem re-ordering the statuses. Please refresh the page and try again');
  }

  var reorderSuccess = function (data) {
    if (data != 'OK') {
      showReorderError();
    }
    deselLine();
  }

  $.ajaxSetup({ timeout: 3000 });
  $(document).ajaxError(function (event, jqXHR, ajaxSettings, thrownError) {
    showReorderError();
  });

  $('#statuses').sortable({
    axis: 'y',
    update: function(event, ui) {
      $.post("../ajax/admin/update_status_order.php", { statuses: $('#statuses').sortable('serialize') })
      .done(reorderSuccess);
    }
  });
});