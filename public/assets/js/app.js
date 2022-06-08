$(document).ready(function () {
  // clear input when modal hide
  $(".clear-modal").on("hidden.bs.modal", function (e) {
    $(this).find("input,textarea,select").val("").end();
  });
});

const editUser = () => {
  // ajax somthing

  // if success then show modal
  $("#userModal").modal("show");
};

const deleteUser = () => {
  // show sweet alert dialog
};

const getDetailTicket = (id) => {
  $("#getTicketDetailModal").modal("show");

  $("#aticleDetails").text(id);
};

const getUserDetail = (data) => {
  $("#getUserDetaillModal").modal("show");

  $("#userDetails").text(data);
};

const insertCategory = () => {
  $("#catModal").modal("show");
};

const getSubCatagories = () => {
  $("#subCatModal").modal("show");
};

const getAdminDetail = (data) => {
  $("#adminDetailModal").modal("show");
};
