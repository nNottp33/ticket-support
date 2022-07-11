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
    $(".previewImg").hide();
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
});

// check if user press enter button
$(document).on("keypress", ".input-login", function (e) {
  if (e.which == 13) {
    login();
  }
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
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดผลาด!",
          text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
        }).then((result) => {
          console.log(error);
        });
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
          setTimeout(() => {
            $(".preloader").hide();
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดผลาด!",
              text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
            }).then((result) => {
              console.log(error);
            });
          }, 1000);
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
    colReorder: {
      realtime: true,
    },
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
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input  onchange="changeStatusUser(this, '${data.id}', '${data.email}', 'on')" type="checkbox" class="custom-control-input" id="switchStatus${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="switchStatus${data.id}"></label>
            </div>
          </div>`;
          }
          if (data.status == 1) {
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input onchange="changeStatusUser(this, '${data.id}', '${data.email}', 'off')" type="checkbox" checked="true" class="custom-control-input" id="switchStatus${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="switchStatus${data.id}"></label>
            </div>
          </div>`;
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

const changeStatusUser = (status, id, email, action) => {
  if (status.checked) {
    Swal.fire({
      title: "คุณแน่ใจใช่ไหม?",
      text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ใช่, เปิดใช้งาน!",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${baseUrl}admin/users/update/status`,
          type: "POST",
          data: {
            status: 1,
            id: id,
            email: email,
          },
          success: function (response) {
            if (response.status == 200) {
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              }).then(() => {
                $(`#${status.id}`).prop("checked", true);
                countUser();
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
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดผลาด!",
              text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
            }).then((result) => {
              console.log(error);
            });
          },
        });
      }
      if (result.isDismissed) {
        $(`#${status.id}`).prop("checked", false);
      }
    });
  } else {
    Swal.fire({
      title: "คุณแน่ใจใช่ไหม?",
      text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ใช่, ปิดใช้งาน!",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${baseUrl}admin/users/update/status`,
          type: "POST",
          data: {
            status: 0,
            id: id,
            email: email,
          },
          success: function (response) {
            if (response.status == 200) {
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              }).then(() => {
                $(`#${status.id}`).prop("checked", false);
                countUser();
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
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดผลาด!",
              text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
            }).then((result) => {
              console.log(error);
            });
          },
        });
      }
      if (result.isDismissed) {
        $(`#${status.id}`).prop("checked", true);
      }
    });
  }
};

const userListByStatus = (status) => {
  var tableUsers = $("#tableUser").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    colReorder: {
      realtime: true,
    },
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
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input  onchange="changeStatusUser(this, '${data.id}', '${data.email}', 'on')" type="checkbox" class="custom-control-input" id="switchStatus${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="switchStatus${data.id}"></label>
            </div>
          </div>`;
          }
          if (data.status == 1) {
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input onchange="changeStatusUser(this, '${data.id}', '${data.email}', 'off')" type="checkbox" checked="true" class="custom-control-input" id="switchStatus${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="switchStatus${data.id}"></label>
            </div>
          </div>`;
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
        $("#countTotalUser").html(
          response.data.total[0].total ? response.data.total[0].total : 0,
        );
        $("#countOnlineUser").html(
          response.data.on[0].online ? response.data.on[0].online : 0,
        );
        $("#countSuspendedUser").html(
          response.data.off[0].suspended ? response.data.off[0].suspended : 0,
        );
        $("#countDeniedUser").html(
          response.data.terminate[0].terminate
            ? response.data.terminate[0].terminate
            : 0,
        );
      }

      if (response.status == 404) {
        $("#countUser").html(0);
      }
    },

    error: function (error) {
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

const editUser = (id) => {
  $("#btnUpdateUser").show();
  $("#btnSaveUser").hide();
  $("#select-status").css("display", "block");
  $("#div-inputPassword").css("display", "none");

  // $("#div-selectDepartment").css("display", "none");
  // $("#div-selectPosition").css("display", "none");

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

        // $("#selectDepartment").val(response.data[0].depId);
        // $("#selectPosition :selected").val(response.data[0].posId);

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
              // departmentId: $("#selectDepartment :selected").val(),
              // positionId: $("#selectPosition :selected").val(),
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
              setTimeout(() => {
                $(".preloader").hide();
                Swal.fire({
                  icon: "error",
                  title: "เกิดข้อผิดผลาด!",
                  text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
                }).then((result) => {
                  console.log(error);
                });
              }, 1000);
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
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
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

        if ($("#selectDepartment :selected").val()) {
          let depId = $("#selectDepartment :selected").val();
          let resultPositionList = getPositions(depId);
        }

        $("#selectDepartment").change(function () {
          let depId = $("#selectDepartment :selected").val();
          let resultPositionList = getPositions(depId);
        });
      }
    },
    error: function (error) {
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
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
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
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
      setTimeout(() => {
        $(".preloader").hide();
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดผลาด!",
          text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
        }).then((result) => {
          console.log(error);
        });
      }, 1000);
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
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดผลาด!",
            text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
          }).then((result) => {
            console.log(error);
          });
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
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดผลาด!",
            text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
          }).then((result) => {
            console.log(error);
          });
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
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดผลาด!",
          text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
        }).then((result) => {
          console.log(error);
        });
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

// ============================ Catagory page ============================= //
var categoryId;

const catList = () => {
  var tableCat = $("#tableCat").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    colReorder: {
      realtime: true,
    },
    ajax: `${baseUrl}admin/catagories/list`,
    columns: [
      {
        targets: 0,
        data: null,
        className: "text-center",
        searchable: true,
        orderable: true,
        render: function (data, type, full, meta) {
          return `<div>  
                      <a href="#" data-toggle="tooltip" title="แก้ไขข้อมูล" onclick="editCat(${data.id})" class="btn btn-primary btn-sm"> 
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="#" data-toggle="tooltip" title="ลบข้อมูล" onclick="deleteCat(${data.id}, '${data.nameCatTh}')" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </a>
                  </div>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.status == 0) {
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input  onchange="changeStatusCat(this, '${data.id}', '${data.nameSubCat}')" type="checkbox" class="custom-control-input" id="switchCat${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="switchCat${data.id}"></label>
            </div>
          </div>`;
          }
          if (data.status == 1) {
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input onchange="changeStatusCat(this, '${data.id}', '${data.nameSubCat}')" type="checkbox" checked="true" class="custom-control-input" id="switchCat${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="switchCat${data.id}"></label>
            </div>
          </div>`;
          }
        },
      },
      {
        data: "nameCatTh",
        className: "text-center",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          return `<a href="#" onclick="getOwner(${data.id})" data-bs-toggle="tooltip"
                                 data-bs-placement="bottom" title="owner detail" class="btn btn-sm btn-info">
                    <i class="fas fa-users"></i>
                  </a>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          return `<a href="#" onclick="getSubCatagories(${data.id})" data-bs-toggle="tooltip"
                      data-bs-placement="bottom" title="more sub catagory" class="btn btn-sm btn-dark">
                      <i class="fas fa-list"></i>
                  </a>`;
        },
      },
    ],
  });
};

const getOwner = (groupId) => {
  categoryId = groupId;
  $("#adminDetailModal").modal("show");
  var tableListOwner = $("#tableListOwner").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    colReorder: {
      realtime: true,
    },
    ajax: {
      url: `${baseUrl}admin/catagories/owner`,
      type: "GET",
      data: {
        ownerGroupId: groupId,
      },
    },
    columns: [
      {
        data: "ownerEmpId",
        className: "text-center",
      },
      {
        data: "ownerName",
        className: "text-center",
      },
      {
        data: "ownerEmail",
        className: "text-center",
      },
      {
        data: "ownerPosition",
        className: "text-center",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          return `<div>
             <a href="#" data-toggle="tooltip" title="ลบข้อมูล" onclick="deleteOwner(${data.id}, '${data.ownerName}')" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </a>
          </div>`;
        },
      },
    ],
  });
};

const insertOwner = () => {
  $("#adminDetailModal").modal("hide");
  $("#insertAdminModal").modal("show");

  $.ajax({
    url: `${baseUrl}admin/get/list`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].fullname}</option>`;
        }
        $("#adminListSelect").html(html);
        $("#adminListSelect").selectpicker("refresh");
      }
    },
    error: function (error) {
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

const saveOwner = () => {
  let adminId = $("#adminListSelect").val();
  if (!adminId) {
    Swal.fire({
      icon: "error",
      title: "กรุราเลือกแอดมิน",
      showConfirmButton: false,
      timer: 1500,
    });

    return false;
  }

  if (adminId) {
    $.ajax({
      url: `${baseUrl}admin/catagories/owner/save`,
      type: "POST",
      data: {
        groupId: categoryId,
        ownerId: adminId,
      },
      success: function (response) {
        if (response.status === 200) {
          Swal.fire({
            icon: "success",
            title: response.title,
            text: response.message,
            showConfirmButton: false,
            timer: 1000,
          }).then((result) => {
            if (result.isDismissed) {
              $("#insertAdminModal").modal("hide");
              $("#adminDetailModal").modal("show");
              $("#tableListOwner").DataTable().ajax.reload();
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
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดผลาด!",
          text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
        }).then((result) => {
          console.log(error);
        });
      },
    });
  }
};

$("#insertAdminModal").on("hidden.bs.modal", function (e) {
  $("#adminDetailModal").modal("show");
});

const editCat = (id) => {
  $.ajax({
    url: `${baseUrl}admin/catagories/get/edit`,
    type: "POST",
    data: {
      id: id,
    },
    success: function (response) {
      if (response.status === 200) {
        $("#catModal").modal("show");
        $(".insert-category").hide();
        $("#btnUpdateCat").removeClass("d-none");
        $("#inputCat").val(response.data[0].nameCatTh);

        $("#btnUpdateCat").click(function () {
          if (!$("#inputCat").val()) {
            Swal.fire({
              icon: "warning",
              title: "ไม่มีข้อมูล",
              showConfirmButton: false,
              timer: 1000,
            }).then((result) => {
              return false;
            });
          }

          if ($("#inputCat").val()) {
            $(".preloader").show();
            $.ajax({
              url: `${baseUrl}admin/catagories/update`,
              type: "POST",
              data: {
                id: id,
                nameCatTh: $("#inputCat").val(),
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
                    }).then((result) => {
                      $("#catModal").modal("hide");
                      $("#tableCat").DataTable().ajax.reload();
                    });
                  }, 1000);
                }

                if (response.status == 404 || response.status == 400) {
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

              error: function (err) {
                setTimeout(() => {
                  $(".preloader").hide();
                  Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดผลาด!",
                    text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
                  }).then((result) => {
                    console.log(err);
                  });
                }, 1000);
              },
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
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(err);
      });
    },
  });
};

const deleteOwner = (id, nameOwner) => {
  Swal.fire({
    title: "คุณแน่ใจว่าจะลบใช่ไหม?",
    text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "ไม่",
    confirmButtonText: "ใช่, ลบข้อมูลนี้!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `${baseUrl}admin/catagories/owner/delete`,
        type: "POST",
        data: {
          ownerId: id,
          nameOwner: nameOwner,
          catId: categoryId,
        },
        success: function (response) {
          if (response.status == 200) {
            Swal.fire({
              icon: "success",
              title: response.title,
              text: response.message,
              showConfirmButton: false,
              timer: 1500,
            }).then(() => {
              $("#tableListOwner").DataTable().ajax.reload();
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
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดผลาด!",
            text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
          }).then((result) => {
            console.log(error);
          });
        },
      });
    }
  });
};

const changeStatusCat = (status, id, nameCat) => {
  if (status.checked) {
    Swal.fire({
      title: "คุณแน่ใจใช่ไหม?",
      text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ใช่, เปิดใช้งาน!",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${baseUrl}admin/catagories/update/status`,
          type: "POST",
          data: {
            status: 1,
            id: id,
            nameCat: nameCat,
          },
          success: function (response) {
            if (response.status == 200) {
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              }).then(() => {
                $(`#${status.id}`).prop("checked", true);
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
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดผลาด!",
              text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
            }).then((result) => {
              console.log(error);
            });
          },
        });
      }
      if (result.isDismissed) {
        $(`#${status.id}`).prop("checked", false);
      }
    });
  } else {
    Swal.fire({
      title: "คุณแน่ใจใช่ไหม?",
      text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ใช่, ปิดใช้งาน!",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${baseUrl}admin/catagories/update/status`,
          type: "POST",
          data: {
            status: 0,
            id: id,
            nameCat: nameCat,
          },
          success: function (response) {
            if (response.status == 200) {
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              }).then(() => {
                $(`#${status.id}`).prop("checked", false);
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
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดผลาด!",
              text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
            }).then((result) => {
              console.log(error);
            });
          },
        });
      }
      if (result.isDismissed) {
        $(`#${status.id}`).prop("checked", true);
      }
    });
  }
};

const insertCategory = () => {
  $("#catModal").modal("show");
  $(".insert-category").show();
  $("#btnUpdateCat").addClass("d-none");

  $.ajax({
    url: `${baseUrl}admin/get/list`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].fullname}</option>`;
        }
        $("#adminListInsert").html(html);
        $("#adminListInsert").selectpicker("refresh");
      }
    },
    error: function (error) {
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

const saveCatagory = () => {
  let nameCat = $("#inputCat").val();
  let adminId = $("#adminListInsert").val();

  if (!nameCat) {
    Swal.fire({
      icon: "warning",
      title: "คำเตือน!",
      text: "กรุณากรอกข้อมูลให้ครบถ้วย!",
      showConfirmButton: false,
      timer: 1000,
    }).then((result) => {
      return false;
    });
  }

  if (adminId.length == 0) {
    Swal.fire({
      icon: "warning",
      title: "คำเตือน!",
      text: "กรุณากรอกข้อมูลให้ครบถ้วย!",
      showConfirmButton: false,
      timer: 1000,
    }).then((result) => {
      return false;
    });
  }

  if (nameCat && adminId.length > 0) {
    $(".preloader").show();
    $.ajax({
      url: `${baseUrl}admin/catagories/add`,
      type: "POST",
      data: {
        nameCat: nameCat,
        ownerId: adminId,
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
            }).then((result) => {
              if (result.isDismissed) {
                $("#catModal").modal("hide");
                $("#tableCat").DataTable().ajax.reload();
              }
            });
          }, 1000);
        }

        if (response.status == 404 || response.status == 400) {
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
        setTimeout(() => {
          $(".preloader").hide();
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดผลาด!",
            text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
          }).then((result) => {
            console.log(error);
          });
        }, 1000);
      },
    });
  }
};

const deleteCat = (id, nameCat) => {
  Swal.fire({
    title: "คุณแน่ใจว่าจะลบใช่ไหม?",
    text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "ไม่",
    confirmButtonText: "ใช่, ลบข้อมูลนี้!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `${baseUrl}admin/catagories/delete`,
        type: "POST",
        data: {
          id: id,
          nameCat: nameCat,
          status: 4,
        },
        success: function (response) {
          if (response.status == 200) {
            Swal.fire({
              icon: "success",
              title: response.title,
              text: response.message,
              showConfirmButton: false,
              timer: 1500,
            }).then(() => {
              $("#tableCat").DataTable().ajax.reload();
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
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดผลาด!",
            text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
          }).then((result) => {
            console.log(error);
          });
        },
      });
    }
  });
};

const getSubCatagories = (catId) => {
  $("#subCatModal").modal("show");
  categoryId = catId;

  var tableSubCat = $("#tableSubCat").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    ajax: {
      url: `${baseUrl}admin/catagories/sub`,
      type: "GET",
      data: {
        catId: catId,
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
                      <a href="#" data-toggle="tooltip" title="แก้ไขข้อมูล" onclick="editSubCat(${data.id})" class="btn btn-primary btn-sm"> 
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="#" data-toggle="tooltip" title="ลบข้อมูล" onclick="deleteSubCat(${data.id}, '${data.nameSubCat}')" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </a>
                  </div>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.status == 0) {
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input  onchange="changeStatusSubCat(this, '${data.id}', '${data.nameSubCat}')" type="checkbox" class="custom-control-input" id="customSwitch${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="customSwitch${data.id}"></label>
            </div>
          </div>`;
          }
          if (data.status == 1) {
            return `<div class="form-group">
            <div class="custom-control custom-switch">
              <input onchange="changeStatusSubCat(this, '${data.id}', '${data.nameSubCat}')" type="checkbox" checked="true" class="custom-control-input" id="customSwitch${data.id}" name='machine_state'>
              <label class="custom-control-label" id="statusText" for="customSwitch${data.id}"></label>
            </div>
          </div>`;
          }
        },
      },
      {
        data: "nameSubCat",
      },
      {
        data: "detail",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.period < 60 && data.period > 0) {
            return `<span> ${data.period} นาที</span> `;
          }

          if (data.period >= 60) {
            return `<span> ${data.period / 60} ชั่วโมง</span> `;
          }

          if (data.period == 0) {
            return `<span> ไม่มีข้อมูล </span> `;
          }
        },
      },
    ],
  });
};

const deleteSubCat = (subCatId, nameSubCat) => {
  Swal.fire({
    title: "คุณแน่ใจว่าจะลบใช่ไหม?",
    text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "ไม่",
    confirmButtonText: "ใช่, ลบข้อมูลนี้!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `${baseUrl}admin/catagories/sub/delete`,
        type: "POST",
        data: {
          id: subCatId,
          nameSubCat: nameSubCat,
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
              $("#tableSubCat").DataTable().ajax.reload();
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
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดผลาด!",
            text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
          }).then((result) => {
            console.log(error);
          });
        },
      });
    }
  });
};

const changeStatusSubCat = (status, id, nameSubCat) => {
  if (status.checked) {
    Swal.fire({
      title: "คุณแน่ใจใช่ไหม?",
      text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ใช่, เปิดใช้งาน!",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${baseUrl}admin/catagories/sub/update/status`,
          type: "POST",
          data: {
            status: 1,
            id: id,
            nameSubCat: nameSubCat,
          },
          success: function (response) {
            if (response.status == 200) {
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              }).then(() => {
                $(`#${status.id}`).prop("checked", true);
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
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดผลาด!",
              text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
            }).then((result) => {
              console.log(error);
            });
          },
        });
      }
      if (result.isDismissed) {
        $(`#${status.id}`).prop("checked", false);
      }
    });
  } else {
    Swal.fire({
      title: "คุณแน่ใจใช่ไหม?",
      text: "คุณจะไม่สามารถย้อนกลับมาได้แล้วนะ!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ใช่, ปิดใช้งาน!",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${baseUrl}admin/catagories/sub/update/status`,
          type: "POST",
          data: {
            status: 0,
            id: id,
            nameSubCat: nameSubCat,
          },
          success: function (response) {
            if (response.status == 200) {
              Swal.fire({
                icon: "success",
                title: response.title,
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
              }).then(() => {
                $(`#${status.id}`).prop("checked", false);
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
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดผลาด!",
              text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
            }).then((result) => {
              console.log(error);
            });
          },
        });
      }
      if (result.isDismissed) {
        $(`#${status.id}`).prop("checked", true);
      }
    });
  }
};

const insertSubCat = () => {
  $("#subCatModal").modal("hide");
  $("#subCatSaveModal").modal("show");
  $("#btnUpdateSubCat").hide();
};

$("#subCatSaveModal").on("hidden.bs.modal", function (e) {
  $("#subCatModal").modal("show");
  $("#btnSaveSubCat").show();
  $("#btnUpdateSubCat").hide();
});

const saveSubCat = () => {
  let nameSubCat = $("#inputNameSubCat").val();
  let detail = $("#inputDetailSubCat").val();
  let sla = $("#inputSla").val();

  if (!nameSubCat || !detail || !sla) {
    Swal.fire("กรุณากรอกข้อมูลให้ครบถ้วน!", "", "error");
    return false;
  }

  if (nameSubCat || detail || sla) {
    $(".preloader").show();
    $.ajax({
      url: `${baseUrl}admin/catagories/sub/insert`,
      type: "POST",
      data: {
        nameSubCat: nameSubCat,
        detail: detail,
        period: sla,
        catId: categoryId,
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
            }).then(() => {
              $("#subCatSaveModal").modal("hide");
              $("#tableSubCat").DataTable().ajax.reload();
            });
          }, 1000);
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
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดผลาด!",
          text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
        }).then((result) => {
          console.log(error);
        });
      },
    });
  }
};

const editSubCat = (id) => {
  $.ajax({
    url: `${baseUrl}admin/catagories/sub/get/edit`,
    type: "POST",
    data: {
      id: id,
    },

    success: function (response) {
      if (response.status == 200) {
        $("#subCatModal").modal("hide");
        $("#btnSaveSubCat").hide();
        $("#subCatSaveModal").modal("show");
        $("#btnUpdateSubCat").show();
        $("#inputNameSubCat").val(response.data.nameSubCat);
        $("#inputDetailSubCat").val(response.data.detail);

        let calSla = response.data.period / 60;

        console.log(calSla);
        let sla = calSla > 0 ? calSla : calSla == 0 ? 0 : calSla.toFixed(2);
        console.log(sla);

        $("#inputSla").val(sla);

        $("#btnUpdateSubCat").click(function () {
          $(".preloader").show();
          $.ajax({
            url: `${baseUrl}admin/catagories/sub/update`,
            type: "POST",
            data: {
              id: id,
              nameSubCat: $("#inputNameSubCat").val(),
              detail: $("#inputDetailSubCat").val(),
              period: $("#inputSla").val(),
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
                    $("#subCatModal").modal("show");
                    $("#subCatSaveModal").modal("hide");
                    $("#tableSubCat").DataTable().ajax.reload();
                  });
                }, 1500);
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
              Swal.fire({
                icon: "error",
                title: "เกิดข้อผิดผลาด!",
                text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
              }).then((result) => {
                console.log(error);
              });
            },
          });
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
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

// ======================================================================== //

// ============================= Ticket page ============================== //
const getAdminTicket = () => {
  var tableTicketAdmin = $("#tableTicketAdmin").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    colReorder: {
      realtime: true,
    },
    ajax: `${baseUrl}admin/ticket/show/list`,
    columns: [
      {
        targets: 0,
        data: null,
        className: "text-center",
        searchable: true,
        orderable: true,
        render: function (data, type, full, meta) {
          if (data.task_status == 0) {
            return `<div>  
                       <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Confirm Ticket" onclick="updateTicketStatus('${data.taskId}', 'approve', 1)" class="btn btn-success btn-sm">
                          <i class="fas fa-check"></i>
                      </a>
                      <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Reject Ticket" onclick="updateTicketStatus('${data.taskId}', 'reject', 3)" class="btn btn-danger btn-sm">
                        <i class="fas fa-times"> </i>
                      </a> 
                    </div>`;
          }

          if (data.task_status == 1) {
            return `<div data-toggle="tooltip" title="Accepted pending...">  
                      <a href="#" onclick="updateTicketStatus('${data.taskId}', 'completed', 2)" class="btn btn-warning btn-sm">
                          <li class="fas fa-clock"></li>
                      </a> 
                    </div>`;
          }

          if (data.task_status == 2) {
            // onclick="updateTicketStatus('closed', 4)"
            return `<div data-toggle="tooltip" title="Completed">
                <a href="#" class="btn btn-success btn-sm">
                    <li class="fas fa-check-circle"></li>
                 </a>
             </div>`;
          }

          if (data.task_status == 4) {
            return `<div data-toggle="tooltip" title="Closed">
                <a href="#" class="btn btn-secondary btn-sm">
                    <li class="fas fa-check-circle"></li>
                 </a>
             </div>`;
          }
        },
      },
      {
        data: "task_topic",
        className: "text-center",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          return `<a href="#" onclick="getMoreDetailTicket(${data.taskId})" data-bs-toggle="tooltip"
                      data-bs-placement="bottom" title="more detail" class="btn btn-sm btn-cyan">
                      <i class="fas fa-list"></i>
                  </a>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          return `<a href="#" onclick="getUserDetail('${data.user_email}')" data-bs-toggle="tooltip"
                      data-bs-placement="bottom" title="more detail" class="btn btn-sm btn-light">
                    ${data.user_email}
                  </a>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.task_created == 0) {
            return ` <span> ไม่มีข้อมูล </span>`;
          }

          if (data.task_created != 0) {
            return `<span class="text-success"> <b> ${moment
              .unix(data.task_created)
              .format("DD/MM/YYYY HH:mm")} </b> </span>`;
          }
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.task_updated == 0) {
            return ` <span> ไม่มีข้อมูล </span>`;
          }

          if (data.task_updated != 0) {
            return `<span class="text-success"> <b> ${moment
              .unix(data.task_updated)
              .format("DD/MM/YYYY HH:mm")} </b> </span>`;
          }
        },
      },
    ],
  });
};

const getUserDetail = (email) => {
  $.ajax({
    url: `${baseUrl}admin/ticket/user/detail`,
    type: "POST",
    data: {
      email: email,
    },
    success: function (response) {
      if (response.status === 200) {
        $("#getUserDetaillModal").modal("show");

        if (response.data.prefix == "นางสาว" || response.data.prefix == "นาง") {
          var imageArr = ["avatar3.png", "avatar8.png"];
        } else {
          var imageArr = [
            "avatar1.png",
            "avatar2.png",
            "avatar4.png",
            "avatar5.png",
            "avatar6.png",
            "avatar7.png",
          ];
        }

        let randomNum = Math.floor(Math.random() * imageArr.length);
        $("#ticketAvatar").attr(
          "src",
          `${baseUrl}assets/images/avatar/${imageArr[randomNum]}`,
        );
        $("#text-ticketEmpId").html(response.data.empId);
        $("#text-ticketFullname").html(
          `${response.data.prefix} ${response.data.fullname}`,
        );
        $("#text-ticketPosition").html(response.data.namePosition);
        $("#text-ticketDepartment").html(response.data.nameDepart);
        $("#text-ticketTel").html(
          response.data.tel.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3"),
        );
        $("#text-ticketMail").html(response.data.email);
      }

      if (response.status == 404 || response.status == 400) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 1000,
        }).then((result) => {
          return false;
        });
      }
    },

    error: function (error) {
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

const countTicket = () => {
  $.ajax({
    url: `${baseUrl}admin/ticket/count`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        $("#totalTicket").html(response.data.total ? response.data.total : 0);
        $("#pendingTicket").html(
          response.data.pending ? response.data.pending : 0,
        );
        $("#newTicket").html(response.data.wait ? response.data.wait : 0);

        $("#completeTicket").html(
          response.data.complete ? response.data.complete : 0,
        );
        $("#closeTicket").html(response.data.close ? response.data.close : 0);
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

    error: function () {
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

const updateTicketStatus = (id, action, status) => {
  switch (action) {
    case "approve":
      Swal.fire({
        icon: "question",
        title: "ต้องการรับ Ticket?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ยืนยัน!",
        cancelButtonText: "ยังก่อน",
      }).then((result) => {
        if (result.isConfirmed) {
          updateTicket(id, status);
        }
      });

      break;
    case "reject":
      Swal.fire({
        icon: "question",
        title: "คุณต้องการ Reject Ticket นี้?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "ticket ซ้ำ",
        confirmButtonColor: "#D5A960",
        denyButtonText: `ticket ผิด`,
        denyButtonColor: "#F14C4C",
      }).then((result) => {
        if (result.isConfirmed) {
          $(".preloader").show();
          updateTicket(id, status, "duplicate");
        }

        if (result.isDenied) {
          // reject the ticket ผิด โชว์ Modal ให้เลือก cat subcat owner
          // ดึง cat มา แล้วแสดง subcat owner ภายใต้ cat นั้น จากนั้น เลือกข้อมูลที่ถูก แล้วก็ อัพเดทข้อมูล
          // จากนั้นส่งไปฟังก์ชัน เพื่ออัพเดทข้อมูล catId subCatid updatedAt
          $("#rejectTicketModal").modal("show");
        }
      });

      break;
    case "completed":
      console.log("completed");
      break;
    default:
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
      break;
  }
};

const updateTicket = (id, status, reject) => {
  $(".preloader").show();

  $.ajax({
    url: `${baseUrl}admin/ticket/update/status`,
    type: "POST",
    data: {
      id: id,
      status: status,
      reject: reject,
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
          }).then(() => {
            $("#tableTicketAdmin").DataTable().ajax.reload();
            countTicket();
          });
        }, 1000);
      }

      if (response.status == 404 || response.status == 400) {
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
      setTimeout(() => {
        $(".preloader").hide();
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดผลาด!",
          text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
        }).then((result) => {
          console.log(error);
        });
      }, 1000);
    },
  });
};
// ======================================================================== //

// ======================================================================== //
// ========================== Role ADMIN end ============================== //
// ======================================================================== //

// ------------------------------------------------------------------------ //

// ======================================================================== //
// ===========================  Role USER ================================= //
// ======================================================================== //

// ============================= Ticket page ============================== //

// add ticket
const insertTicket = () => {
  $("#userTicketModal").modal("show");
  let resultCategory = getCategory();
};

const getCategory = () => {
  $.ajax({
    url: `${baseUrl}user/catagories/list`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].nameCatTh}</option>`;
        }
        $("#userSelectCategory").html(html);
        $("#userSelectCategory").selectpicker("refresh");
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
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

const getSubCatagory = () => {
  let catId = $("#userSelectCategory").val();
  $.ajax({
    url: `${baseUrl}user/catagories/sub`,
    type: "GET",
    data: {
      catId: catId,
    },
    success: function (response) {
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].nameSubCat}</option>`;
        }
        $("#userSelectSubCategory").html(html);
        $("#userSelectSubCategory").selectpicker("refresh");
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
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

const previewFile = (input, id) => {
  let file = $("input[type=file]").get(0).files[0];

  if (file) {
    let reader = new FileReader();

    reader.onload = function () {
      $(".previewImg").show();
      $(".previewImg").attr("src", reader.result);
    };

    reader.readAsDataURL(file);
  }
};

$("#ticketForm").on("submit", function (e) {
  e.preventDefault();
  if (
    !$("#ticketTopic").val() ||
    !$("#userSelectCategory").val() ||
    !$("#userSelectSubCategory").val() ||
    !$("#ticketDetail").val()
  ) {
    Swal.fire({
      icon: "warning",
      title: "คำเตือน!",
      text: "กรุณากรอกข้อมูลให้ครบถ้วน!",
    }).then((result) => {
      return false;
    });
  }

  if (
    $("#ticketTopic").val() &&
    $("#userSelectCategory").val() &&
    $("#userSelectSubCategory").val() &&
    $("#ticketDetail").val()
  ) {
    $(".preloader").show();
    $.ajax({
      url: `${baseUrl}user/ticket/save`,
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",
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
            }).then((result) => {
              $("#userTicketModal").modal("hide");
              $("#tableUserTicket").DataTable().ajax.reload();
            });
          }, 1000);
        }

        if (response.status == 404 || response.status == 400) {
          setTimeout(() => {
            $(".preloader").hide();
            Swal.fire({
              icon: "error",
              title: response.title,
              text: response.message,
              showConfirmButton: false,
              timer: 1000,
            }).then((result) => {
              return false;
            });
          }, 1000);
        }
      },

      error: function (error) {
        setTimeout(() => {
          $(".preloader").hide();
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดผลาด!",
            text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
          }).then((result) => {
            console.log(error);
          });
        }, 1000);
      },
    });
  }
});

// show user ticket
const getUserTicket = () => {
  var tableUserTicket = $("#tableUserTicket").dataTable({
    processing: true,
    stateSave: true,
    searching: true,
    responsive: true,
    bDestroy: true,
    colReorder: {
      realtime: true,
    },
    ajax: `${baseUrl}user/ticket/list`,
    columns: [
      {
        targets: 0,
        data: null,
        className: "text-center",
        searchable: true,
        orderable: true,
        render: function (data, type, full, meta) {
          if (data.status == 0) {
            return `<div data-toggle="tooltip" title="Pending...">  
                       <a href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-clock"></li> </a> 
                    </div>`;
          }

          if (data.status == 1) {
            return `<div data-toggle="tooltip" title="Accepted">  
                       <a  href="#" class="btn btn-warning btn-sm">  <li class="fas fa-clock"></li> </a> 
                    </div>`;
          }

          if (data.status == 2) {
            return `<div data-toggle="tooltip" title="Success">
                       <a  href="#" class="btn btn-success btn-sm">  <li class="fas fa-check-circle"></li> </a> 
                    </div>`;
          }

          if (data.status == 3) {
            return `<div data-toggle="tooltip" title="Rejected">
                       <a  href="#" class="btn btn-danger btn-sm">  <li class="fas fa-times"></li> </a> 
                    </div>`;
          }

          if (data.status == 4) {
            return `<div data-toggle="tooltip" title="Closes">
                       <a  href="#" class="btn btn-success btn-sm">  <li class="fas fa-check"></li> </a> 
                    </div>`;
          }
        },
      },
      {
        data: "topic",
        className: "text-center",
      },
      {
        data: "nameCatTh",
        className: "text-center",
      },
      {
        data: "nameSubCat",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          return `<a href="#" onclick="getMoreDetailTicket(${data.id})" data-bs-toggle="tooltip"
                      data-bs-placement="bottom" title="more detail" class="btn btn-sm btn-cyan">
                      <i class="fas fa-list"></i>
                  </a>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.createdAt == 0) {
            return ` <span> ไม่มีข้อมูล </span>`;
          }

          if (data.createdAt != 0) {
            return `<span class="text-success"> <b> ${moment
              .unix(data.createdAt)
              .format("DD/MM/YYYY HH:mm")} </b> </span>`;
          }
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, full, meta) {
          if (data.updatedAt == 0) {
            return ` <span> ไม่มีข้อมูล </span>`;
          }

          if (data.updatedAt != 0) {
            return `<span class="text-success"> <b> ${moment
              .unix(data.updatedAt)
              .format("DD/MM/YYYY HH:mm")} </b> </span>`;
          }
        },
      },
    ],
  });
};

// show ticket more detail
const getMoreDetailTicket = (ticketId) => {
  $.ajax({
    url: `${baseUrl}user/ticket/detail`,
    type: "POST",
    data: {
      ticketId: ticketId,
    },
    success: function (response) {
      if (response.status == 200) {
        $("#userTicketDetailModal").modal("show");

        switch (response.data[0].status) {
          case 1:
            var status = "Approved";
            var textColor = "#6c757d";
            break;

          case 2:
            var status = "Success";
            var textColor = "#22ca80";
            break;

          case 3:
            var status = "Rejected";
            var textColor = "#ff0332";
            break;

          case 4:
            var status = "Close";
            var textColor = "#01caf1";
            break;

          default:
            var status = "Pending";
            var textColor = "#fdc16a";
            break;
        }

        $("#textStatus").html(status).css("color", textColor);
        $("#titleTicketDetail").html(response.data[0].topic);
        $("#taskDetail").html(response.data[0].remark);
        $("#textPeriod").html(response.data[0].period);
        $("#textCat").html(response.data[0].nameCatTh);
        $("#textSubCat").html(response.data[0].nameSubCat);
        $("#imgTask").attr(
          "src",
          `${baseUrl}store_files_uploaded/${response.data[0].attachment}`,
        );
      }
      if (response.status == 404 || response.status == 400) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 1000,
        }).then((result) => {
          return false;
        });
      }
    },

    error: function (error) {
      Swal.fire({
        icon: "error",
        title: "เกิดข้อผิดผลาด!",
        text: "ระบบไม่สามรถทำตามคำขอได้ในขณะนี้",
      }).then((result) => {
        console.log(error);
      });
    },
  });
};

// ========================== end ticket page ============================= //

// ======================================================================== //
// ========================== Role USER end =============================== //
// ======================================================================== //
