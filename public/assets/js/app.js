const baseUrl = `${window.location.protocol}//${window.location.host}/`;
const filters =
  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
// ======================================================================== //
// ============================== GLOBAL ================================== //
// ======================================================================== //

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

  $(".profile-input").attr("disabled", "disabled");

  // called function
  let userAll = userList();

  var countTotal = countUser();
});

const togglePassword = (id, inputParent) => {
  let inputId;

  switch (inputParent) {
    case "loginEye":
      inputId = "pwd";
      break;
    case "insertEye":
      inputId = "inputPassword";
      break;
    case "changePassEye":
      inputId = "newPassword";
      break;
    case "confirmChangePassEye":
      inputId = "confirmNewPassword";
      break;
    default:
      console.log("No input parent");
      break;
  }

  $(`#${inputId}`).attr(
    "type",
    $(`#${inputId}`).is(":password") ? "text" : "password",
  );

  if ($(`#${inputId}`).attr("type") === "password") {
    $(`#${id}`).removeClass("fas fa-eye").addClass("fas fa-eye-slash");
  } else {
    $(`#${id}`).removeClass("fas fa-eye-slash").addClass("fas fa-eye");
  }
};

// ======================================================================== //

// ------------------------------------------------------------------------ //

// ======================================================================== //
// ==========================  Authentication ============================= //
// ======================================================================== //
const login = () => {
  let email = $("#email").val();
  let password = $("#pwd").val();

  if (!filters.test(String(email).toLowerCase())) {
    Swal.fire({
      icon: "warning",
      title: "รูปแบบอีเมล์ไม่ถูกต้อง",
    }).then((result) => {
      $("input[type=email]").focus();
      return;
    });
  }

  if (filters.test(String(email).toLowerCase()) && password) {
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
              text: response.message,
              showConfirmButton: false,
              timer: 1500,
            }).then((result) => {
              window.location.href = `${response.redirectUrl}`;
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

const logout = () => {
  Swal.fire({
    title: "คุณต้องการออกจากระบบ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "ใช่!",
    cancelButtonText: "ยังก่อน",
  }).then((result) => {
    if (result.isConfirmed) {
      $(".preloader").show();
      $.ajax({
        url: `${baseUrl}auth/logout`,
        type: "GET",
        success: function (response) {
          if (response.status == 200) {
            setTimeout(() => {
              $(".preloader").hide();
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              }).then((result) => {
                window.location.reload();
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
                showConfirmButton: false,
                timer: 1500,
              });
            }, 1000);
          }
        },

        error: function (error) {
          console.log(error);
        },
      });
    }
  });
};

// ======================================================================== //
// ========================= Authentication end =========================== //
// ======================================================================== //

// ------------------------------------------------------------------------ //

// ======================================================================== //
// ==========================  Role ADMIN ================================= //
// ======================================================================== //

// ============================= User page ================================ //
// user list page
const userList = () => {
  // table user in admin user page
  var tableUsers = $("#tableUser").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    ajax: `${baseUrl}admin/users/list`,
    columns: [
      {
        targets: 0,
        data: null,
        className: "text-center",
        searchable: true,
        orderable: true,
        render: function (data, type, full, meta) {
          return `<div>  
                      <a href="#" data-toggle="tooltip" title="แก้ไขข้อมูล" onclick="editUser(${data.id})" class="btn btn-primary btn-sm"> 
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="#" data-toggle="tooltip" title="ลบข้อมูลผู้ใช้" onclick="deleteUser(${data.id})" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </a>
                       <a href="#" data-toggle="tooltip" title="รีเซ็ตรหัสผ่าน" onclick="resetPassword(${data.id})" class="btn btn-info btn-sm">
                        <i class="fas fa-key"></i>
                      </a>
                  </div>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.status == 0) {
            return `<span class="badge badge-danger"> ปิดใช้งาน </span>`;
          }
          if (data.status == 1) {
            return `<span class="badge badge-success"> เปิดใช้งาน </span>`;
          }
          if (data.status == 3) {
            return `<span class="badge badge-warning"> ล็อค </span>`;
          }
        },
      },
      {
        data: "empId",
      },
      {
        data: "email",
      },

      {
        data: null,
        render: function (data, type, full, meta) {
          return `<span> ${data.prefix} ${data.fullname} </span>`;
        },
      },
      {
        data: "nickname",
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return `<span> ${data.tel.replace(
            /(\d{3})(\d{3})(\d{4})/,
            "$1-$2-$3",
          )} </span>`;
        },
      },
      {
        data: "department",
      },
      {
        data: "position",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.class == "admin") {
            return `<span class="badge badge-purple"> ${data.class} </span>`;
          }

          if (data.class == "user") {
            return `<span class="badge badge-cyan"> ${data.class} </span>`;
          }
        },
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          if (data.lastLogin == 0) {
            return `<span class="text-danger"> <b> ไม่พบการเข้าสู่ระบบ </b> </span>`;
          }

          if (data.lastLogin != 0) {
            return `<span class="text-success"> <b> ${moment
              .unix(data.lastLogin)
              .format("DD/MM/YYYY HH:mm")} </b> </span>`;
          }
        },
      },
    ],
  });
};

const userListByStatus = (status) => {
  var tableUsers = $("#tableUser").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    ajax: {
      url: `${baseUrl}admin/users/list/byStatus`,
      type: "GET",
      data: {
        status: status,
      },
    },
    columns: [
      {
        targets: 0,
        data: null,
        className: "text-center",
        searchable: true,
        orderable: true,
        render: function (data, type, full, meta) {
          return `<div>  
                      <a href="#" data-toggle="tooltip" title="แก้ไขข้อมูล" onclick="editUser(${data.id})" class="btn btn-primary btn-sm"> 
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="#" data-toggle="tooltip" title="ลบข้อมูลผู้ใช้" onclick="deleteUser(${data.id})" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </a>
                       <a href="#" data-toggle="tooltip" title="รีเซ็ตรหัสผ่าน" onclick="resetPassword(${data.id})" class="btn btn-info btn-sm">
                        <i class="fas fa-key"></i>
                      </a>
                  </div>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.status == 0) {
            return `<span class="badge badge-danger"> ปิดใช้งาน </span>`;
          }
          if (data.status == 1) {
            return `<span class="badge badge-success"> เปิดใช้งาน </span>`;
          }
          if (data.status == 3) {
            return `<span class="badge badge-warning"> ล็อค </span>`;
          }
        },
      },
      {
        data: "empId",
      },
      {
        data: "email",
      },

      {
        data: null,
        render: function (data, type, full, meta) {
          return `<span> ${data.prefix} ${data.fullname} </span>`;
        },
      },
      {
        data: "nickname",
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          return `<span> ${data.tel.replace(
            /(\d{3})(\d{3})(\d{4})/,
            "$1-$2-$3",
          )} </span>`;
        },
      },
      {
        data: "department",
      },
      {
        data: "position",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.class == "admin") {
            return `<span class="badge badge-purple"> ${data.class} </span>`;
          }

          if (data.class == "user") {
            return `<span class="badge badge-cyan"> ${data.class} </span>`;
          }
        },
      },
      {
        data: null,
        render: function (data, type, full, meta) {
          if (data.lastLogin == 0) {
            return `<span class="text-danger"> <b> ไม่พบการเข้าสู่ระบบ </b> </span>`;
          }

          if (data.lastLogin != 0) {
            return `<span class="text-success"> <b> ${moment
              .unix(data.lastLogin)
              .format("DD/MM/YYYY HH:mm")} </b> </span>`;
          }
        },
      },
    ],
  });
};

const countUser = () => {
  $.ajax({
    url: `${baseUrl}admin/users/count`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        $("#countTotalUser").html(response.data.total[0].total);
        $("#countOnlineUser").html(response.data.on[0].online);
        $("#countLockedUser").html(response.data.lock[0].locked);
        $("#countSuspendUser").html(response.data.off[0].suspended);
      }

      if (response.status == 404) {
        $("#countUser").html(0);
      }
    },

    error: function (error) {
      console.log(error);
    },
  });
};

const editUser = (id) => {
  $("#btnUpdateUser").show();
  $("#btnSaveUser").hide();
  $("#select-status").css("display", "block");
  $("#div-inputPassword").css("display", "none");

  $.ajax({
    url: `${baseUrl}admin/users/byId`,
    type: "POST",
    data: {
      id: id,
    },
    success: function (response) {
      if (response.status == 200) {
        $("#userModal").modal("show");
        let splitFullname = response.data[0].fullname.split(" ");
        let name = splitFullname[0];
        let lastName = splitFullname[1];
        let resultDepartmentList = getDepartments();

        $("#inputEmpId").val(response.data[0].empId);
        $("#inputName").val(name);
        $("#inputLastname").val(lastName);
        $("#inputNickname").val(response.data[0].nickname);
        $("#inputEmail").val(response.data[0].email);
        $("#inputPhone").val(response.data[0].tel);

        $("#selectPrefix").val(response.data[0].prefix);
        $("#classUser").val(response.data[0].class);
        $("#selectStatus").val(response.data[0].status);

        $("#selectDepartment").val(response.data[0].depId);
        $("#selectPosition").val(response.data[0].posId);

        $(".selectpicker").selectpicker("refresh");

        $("#btnUpdateUser").click(function () {
          // update user
          let name = $("#inputName").val();
          let lastName = $("#inputLastname").val();

          $(".preloader").show();
          $.ajax({
            url: `${baseUrl}admin/users/update`,
            type: "POST",
            data: {
              id: response.data[0].id,
              empId: $("#inputEmpId").val(),
              prefix: $("#selectPrefix").val(),
              fullname: `${name} ${lastName}`,
              nickname: $("#inputNickname").val(),
              email: $("#inputEmail").val(),
              tel: $("#inputPhone").val(),
              class: $("#classUser :selected").val(),
              status: $("#selectStatus :selected").val(),
              departmentId: $("#selectDepartment :selected").val(),
              positionId: $("#selectPosition :selected").val(),
            },
            success: function (response) {
              if (response.status == 200) {
                setTimeout(() => {
                  $(".preloader").hide();
                  Swal.fire({
                    icon: "success",
                    title: response.title,
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500,
                  });
                  $("#userModal").modal("hide");
                  $("#tableUser").DataTable().ajax.reload();
                }, 1000);
              }

              if (response.status == 404) {
                setTimeout(() => {
                  $(".preloader").hide();
                  Swal.fire({
                    icon: "error",
                    title: response.title,
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500,
                  });
                }, 1000);
              }
            },

            error: function (error) {
              console.log(error);
            },
          });
        });
      }

      if (response.status == 404) {
        setTimeout(() => {
          $(".preloader").hide();
          Swal.fire({
            icon: "error",
            title: response.title,
            text: response.message,
            showConfirmButton: false,
            timer: 1500,
          });
        }, 1000);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
};

const addUser = () => {
  $("#select-status").css("display", "none");
  $("#div-inputPassword").css("display", "block");
  $("#btnUpdateUser").hide();
  $("#btnSaveUser").show();

  let resultDepartmentList = getDepartments();
};

const getDepartments = () => {
  $.ajax({
    url: `${baseUrl}admin/department/list`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].nameDepart}</option>`;
        }
        $("#selectDepartment").html(html);
        $("#selectDepartment").selectpicker("refresh");

        $("#selectDepartment").change(function () {
          let depId = $("#selectDepartment :selected").val();
          let resultPositionList = getPositions(depId);
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
};

const getPositions = (id) => {
  $.ajax({
    url: `${baseUrl}admin/position/list`,
    type: "POST",
    data: {
      depId: id,
    },
    success: function (response) {
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].namePosition}</option>`;
        }
        $("#selectPosition").html(html);
        $("#selectPosition").selectpicker("refresh");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
};

const saveUser = () => {
  let empId = $("#inputEmpId").val();
  let prefix = $("#selectPrefix :selected").val();
  let name = $("#inputName").val();
  let lastName = $("#inputLastname").val();
  let nickname = $("#inputNickname").val();
  let email = $("#inputEmail").val();
  let password = $("#inputPassword").val();
  let phone = $("#inputPhone").val();
  let department = $("#selectDepartment :selected").val();
  let position = $("#selectPosition :selected").val();
  let role = $("#classUser :selected").val();
  let status = $("#selectStatus :selected").val();

  if (
    !empId ||
    !prefix ||
    !name ||
    !lastName ||
    !nickname ||
    !email ||
    !password ||
    !phone ||
    !department ||
    !position ||
    !role
  ) {
    Swal.fire("กรุณากรอกข้อมูลให้ครบถ้วน!", "", "error");
    return false;
  }

  $(".preloader").show();
  $.ajax({
    url: `${baseUrl}admin/users/new`,
    type: "POST",
    data: {
      empId: empId,
      prefix: prefix,
      fullname: `${name} ${lastName}`,
      nickname: nickname,
      email: email,
      password: password,
      tel: phone,
      departmentId: department,
      positionId: position,
      class: role,
      status: status,
    },
    success: function (response) {
      if (response.status == 200) {
        setTimeout(() => {
          $(".preloader").hide();
          Swal.fire({
            icon: "success",
            title: response.title,
            title: response.message,
            showConfirmButton: false,
            timer: 1500,
          }).then(() => {
            $("#userModal").modal("hide");
            $("#tableUser").DataTable().ajax.reload();
            countUser();
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
            showConfirmButton: false,
            timer: 1500,
          });
        }, 1000);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
};

const deleteUser = (id) => {
  Swal.fire({
    title: "คุณแน่ใจว่าจะลบใช่ไหม?",
    text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "ไม่",
    confirmButtonText: "ใช่, ลบข้อมูลผู้ใช้นี้!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `${baseUrl}admin/users/delete`,
        type: "POST",
        data: {
          id: id,
        },
        success: function (response) {
          if (response.status == 200) {
            Swal.fire({
              icon: "success",
              title: "Deleted!",
              text: response.message,
              showConfirmButton: false,
              timer: 1500,
            }).then(() => {
              $("#tableUser").DataTable().ajax.reload();
              countUser();
            });
          }

          if (response.status == 404) {
            setTimeout(() => {
              $(".preloader").hide();
              Swal.fire({
                icon: "error",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              });
            }, 1000);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  });
};

const resetPassword = (id) => {
  Swal.fire({
    title: "คุณแน่ใจใช่ไหม?",
    text: "คุณต้องการรีเซตรหัสผ่านผู้ใช้คนนี้ใช่หรือไม่!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "ใช่, รีเซต!",
    cancelButtonText: "ไม่",
  }).then((result) => {
    if (result.isConfirmed) {
      $(".preloader").show();
      $.ajax({
        url: `${baseUrl}admin/users/reset/password`,
        type: "POST",
        data: {
          id: id,
        },
        success: function (response) {
          if (response.status == 200) {
            setTimeout(() => {
              $(".preloader").hide();
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1000,
              });

              $("#tableUser").DataTable().ajax.reload();
            }, 1000);
          }

          if (response.status == 404) {
            setTimeout(() => {
              $(".preloader").hide();
              Swal.fire({
                icon: "error",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1000,
              });
            }, 1000);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  });
};

// =========================== end User page ============================== //

// ============================ profile page ============================== //

// update profile
const showProfile = () => {
  $.ajax({
    url: `${baseUrl}profile/show`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        $("#profileModal").modal("show");

        $("#profileEmpId").val(response.data.empId);
        $("#profileEmail").val(response.data.email);
        $("#profileName").val(response.data.fullname);
        $("#profileNickname").val(response.data.nickname);
        $("#profileTel").val(response.data.tel);
      }

      if (response.status == 404 || response.status == 400) {
        Swal.fire({
          icon: "error",
          title: res.title,
          text: res.message,
          showConfirmButton: false,
          timer: 1000,
        });
      }
    },
  });
};

const checkModal = () => {
  if (!$("#profileModal").hasClass("in")) {
    $(".profile-input").attr("disabled", "disabled");
    $("#btnUpdateProfile").hide();
    $("#btnProfile").show();
  }
};

const toggleEditProfile = () => {
  $(".profile-input").removeAttr("disabled");
  $("#btnProfile").hide();
  $("#btnUpdateProfile").show();
};

const updateProfile = () => {
  $(".preloader").show();
  $.ajax({
    url: `${baseUrl}profile/update`,
    type: "POST",
    data: {
      fullname: $("#profileName").val(),
      nickname: $("#profileNickname").val(),
      tel: $("#profileTel").val(),
    },
    success: function (response) {
      if (response.status == 200) {
        setTimeout(() => {
          $(".preloader").hide();
          Swal.fire({
            icon: "success",
            title: response.title,
            text: response.message,
            showConfirmButton: false,
            timer: 1500,
          }).then((result) => {
            if (result.isDismissed) {
              location.reload();
            }
          });
        }, 1000);
      }

      if (response.status == 404 || response.status == 400) {
        setTimeout(() => {
          $(".preloader").hide();
          Swal.fire({
            icon: "error",
            title: res.title,
            text: res.message,
            showConfirmButton: false,
            timer: 1000,
          });
        }, 1000);
      }
    },
  });
};

// change password
$("#btnShowChangePass").click(function () {
  $("#changePassModal").modal("show");
});

$("#btnCancelOtp").click(function () {
  $("#confirmOtpModal").modal("hide");
});

$("#btnCancelChangePass").click(function () {
  $("#changePassModal").modal("hide");
});

const getOTP = () => {
  let newPass = $("#newPassword").val();
  let confirmPass = $("#confirmNewPassword").val();

  if (!newPass && !confirmPass) {
    Swal.fire({
      icon: "warning",
      title: "กรุณากรอกข้อมูลให้ครบถ้วน!",
    }).then((result) => {
      return false;
    });
  }

  if (newPass == confirmPass) {
    $.ajax({
      url: `${baseUrl}profile/send/otp`,
      type: "POST",
      success: function (response) {
        if (response.status == 200 || response.status == 201) {
          let refOtp = response.ref;

          Swal.fire({
            icon: "success",
            title: response.title,
            text: response.message,
            showConfirmButton: false,
            timer: 1500,
          }).then((result) => {
            if (result.isDismissed) {
              $("#changePassModal").modal("hide");
              $("#confirmOtpModal").modal("show");
              $("#textRefOtp").text(refOtp);

              $("#btnChangePassword").click(function () {
                if (!$("#otp").val()) {
                  Swal.fire({
                    icon: "error",
                    title: "กรุณากรอก otp!",
                    showConfirmButton: false,
                    timer: 1500,
                  }).then((result) => {
                    return;
                  });
                }

                if ($("#otp").val()) {
                  $(".preloader").show();
                  $.ajax({
                    url: `${baseUrl}profile/change/password`,
                    type: "POST",
                    data: {
                      ref: refOtp,
                      otp: $("#otp").val(),
                      newPass: newPass,
                    },
                    success: function (res) {
                      if (res.status == 200 || res.status == 201) {
                        setTimeout(() => {
                          $(".preloader").hide();
                          Swal.fire({
                            icon: "success",
                            title: res.title,
                            text: res.message,
                            showConfirmButton: false,
                            timer: 1500,
                          }).then((result) => {
                            if (result.isDismissed) {
                              $("#confirmOtpModal").modal("hide");
                              location.reload();
                            }
                          });
                        }, 1000);
                      }

                      if (res.status == 404 || res.status == 400) {
                        Swal.fire({
                          icon: "error",
                          title: res.title,
                          text: res.message,
                          showConfirmButton: false,
                          timer: 1000,
                        });
                      }
                    },
                  });
                }
              });
            }
          });
        }

        if (response.status == 404 || response.status == 400) {
          Swal.fire({
            icon: "error",
            title: response.title,
            text: response.message,
            showConfirmButton: false,
            timer: 1000,
          });
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    Swal.fire({
      icon: "error",
      title: "รหัสผ่านไม่ตรงกัน!",
    }).then((result) => {
      return false;
    });
  }
};

// =========================== profile page end =========================== //

// ======================================================================== //
// ========================== Role ADMIN end ============================== //
// ======================================================================== //

// ------------------------------------------------------------------------ //

// ======================================================================== //
// ===========================  Role USER ================================= //
// ======================================================================== //

// ======================================================================== //
// ========================== Role USER end =============================== //
// ======================================================================== //

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
