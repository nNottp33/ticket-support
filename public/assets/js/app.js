const baseUrl = `${window.location.protocol}//${window.location.host}/`;

$(document).ready(function () {
  // clear input when modal hide
  $(".clear-modal").on("hidden.bs.modal", function (e) {
    $(this).find("input,textarea,select").val("").end();
  });

  $(".selectpicker").selectpicker();

  // check input email or password is empty or not
  $(".input-login").keypress(function (e) {
    // if not empty then can click on login button
    if ($("#email").val() && $("#pwd").val()) {
      $("#btnLogin").attr("disabled", false);
    } else {
      $("#btnLogin").attr("disabled", true);
    }
  });

  $(".input-login").on("input", function () {
    if ($("#email").val() && $("#pwd").val()) {
      $("#btnLogin").attr("disabled", false);
    } else {
      $("#btnLogin").attr("disabled", true);
    }
  });
});

const login = () => {
  let email = $("#email").val();
  let password = $("#pwd").val();

  if (email && password) {
    $.ajax({
      url: `${baseUrl}auth/login`,
      type: "POST",
      data: {
        email: email,
        password: password,
      },
      success: function (response) {
        $(".preloader").show();
        if (response.status == 200) {
          setTimeout(() => {
            $(".preloader").hide();
            Swal.fire({
              icon: "success",
              title: response.title,
              showConfirmButton: false,
              timer: 1500,
            }).then((result) => {
              window.location.href = `${baseUrl}admin`;
            });
          }, 1000);
        }

        if (response.status == 404) {
          setTimeout(() => {
            $(".preloader").hide();
            Swal.fire({
              icon: "error",
              title: response.title,
              text: response.message,
            }).then((result) => {
              $("#email").val("");
              $("#pwd").val("");
              $("#btnLogin").attr("disabled", true);
              $("#email").focus();
            });
          }, 1000);
        }
      },

      error: function (error) {
        console.log(error);
      },
    });
  }
};

const preloader = () => {};

// const editUser = () => {
//   // ajax somthing

//   // if success then show modal
//   $("#userModal").modal("show");
// };

// const deleteUser = () => {
//   // show sweet alert dialog
// };

// const getDetailTicket = (id) => {
//   $("#getTicketDetailModal").modal("show");

//   $("#aticleDetails").text(id);
// };

// const getUserDetail = (data) => {
//   $("#getUserDetaillModal").modal("show");

//   $("#userDetails").text(data);
// };

// const insertCategory = () => {
//   $("#catModal").modal("show");
// };

// const getSubCatagories = () => {
//   $("#subCatModal").modal("show");
// };

// const getAdminDetail = (data) => {
//   $("#adminDetailModal").modal("show");
// };

// // check catagories is option 0 or not
// const checkCat = () => {
//   let option = $("#selectCategory :selected").val();

//   // when select option add show text input

//   if (option == 0) {
//     $("#inputNewCatagory").show();
//   } else {
//     $("#inputNewCatagory").hide();
//   }
// };

// const showDetailUserTicker = (id) => {
//   $("#userTicketDetailModal").modal("show");
// };
