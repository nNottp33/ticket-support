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
    $(".timeline__items").timeline().empty();
    $(".collapse").collapse("hide");
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

const count = ($this) => {
  var current = parseInt($this.html(), 10);
  $this.html(++current);
  if (current <= 10) {
    setTimeout(function () {
      count($this);
    }, 50);
  } else {
    reduce($this, current);
  }
};

const reduce = ($this, current) => {
  $this.html(--current);
  if (current != 0) {
    setTimeout(function () {
      reduce($this, current);
    }, 80);
  } else {
    count($this);
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
  var tableUsers = $("#tableUser")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
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
  var tableUsers = $("#tableUser")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
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

        $("#selectDepartment :selected").val(response.data[0].depId);
        $("#selectPosition :selected").val(response.data[0].posId);

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
        let $dep = $("#selectDepartment");
        $dep.empty();

        for (let count = 0; count < response.data.length; count++) {
          $dep.append(
            `<option value="${response.data[count].id}">${response.data[count].nameDepart}</option>`,
          );
        }
        $dep.change();

        if ($("#selectDepartment").val()) {
          let depId = $("#selectDepartment :selected").val();
          let resultPositionList = getPositions(depId);
        }

        $("#selectDepartment").change(function () {
          let depId = $("#selectDepartment").val();
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
        let $pos = $("#selectPosition");
        $pos.empty();
        for (let count = 0; count < response.data.length; count++) {
          $pos.append(
            `<option value="${response.data[count].id}">${response.data[count].namePosition}</option>`,
          );
        }
        $pos.change();
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
  var tableCat = $("#tableCat")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
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
  var tableListOwner = $("#tableListOwner")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
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

  var tableSubCat = $("#tableSubCat")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
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
                setTimeout(() => {
                  $(".preloader").hide();
                  Swal.fire({
                    icon: "error",
                    title: response.title,
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1000,
                  });
                }, 1500);
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
  $("#tableTicketAdmin")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
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

            if (data.task_status == 5) {
              return `<div>  
                       <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Confirm Ticket" onclick="updateTicketStatus('${data.taskId}', 'approve', 1)" class="btn btn-success btn-sm">
                          <i class="fas fa-check"></i>
                      </a>
                    </div>`;
            }

            if (data.task_status == 6) {
              return `<div>  
                       <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Confirm Ticket" onclick="updateTicketStatus('${data.taskId}', 'acceptedReturn', 1)" class="btn btn-success btn-sm">
                         <i class="fas fa-reply"></i>
                      </a>
                    </div>`;
            }
          },
        },
        {
          data: "ticket_no",
          className: "text-center",
        },
        {
          data: "task_topic",
          className: "text-center",
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            return `<a href="#" onclick="getMoreTicketDetail(${data.taskId})" data-bs-toggle="tooltip"
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
          updateTicket(id, status, action);
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
          updateTicket(id, status, "duplicate");
        }

        if (result.isDenied) {
          $("#rejectTicketModal").modal("show");
          getCategoryTicket();

          $("#btnChangeTicket").click(function (e) {
            if (
              !$("#changeTicketCategory").val() ||
              !$("#changeTicketSubCategory").val() ||
              !$("#changeTicketOwner").val()
            ) {
              Swal.fire("คำเตือน!", "กรุณาเลือกข้อมูลให้ครบถ้วน", "warning");
            }

            if (
              $("#changeTicketCategory").val() &&
              $("#changeTicketSubCategory").val() &&
              $("#changeTicketOwner").val()
            ) {
              $(".preloader").show();
              $.ajax({
                url: `${baseUrl}admin/ticket/reject/change`,
                type: "POST",
                data: {
                  taskId: id,
                  catId: $("#changeTicketCategory").val(),
                  subCatId: $("#changeTicketSubCategory").val(),
                  ownerId: $("#changeTicketOwner").val(),
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
                        $("#rejectTicketModal").modal("hide");
                        $("#tableTicketAdmin").DataTable().ajax.reload();
                        countTicket();
                      });
                    }, 1500);
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
                    }, 1500);
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
                  }, 1500);
                },
              });
            }
          });
        }
      });

      break;
    case "completed":
      Swal.fire({
        icon: "question",
        title: "ปรับสถานะเป็นสำเร็จ?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ยืนยัน!",
        cancelButtonText: "ยังก่อน",
      }).then((result) => {
        if (result.isConfirmed) {
          $("#ticketTaskDialog").modal("show");

          $("#ticketFormSuccess").on("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            formData.append("id", id);
            formData.append("status", status);

            if (!$("#cause").val()) {
              Swal.fire("คำเตือน?", "กรุณาระบุสาเหตุของ Ticket", "warning");
            }

            if (!$("#solution").val()) {
              Swal.fire("คำเตือน?", "กรุณาระบุวิธีแก้ไข", "warning");
            }

            if ($("#cause").val() && $("#solution").val()) {
              $(".preloader").show();
              $.ajax({
                url: `${baseUrl}admin/ticket/update/status`,
                type: "POST",
                data: formData,
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
                        timer: 1500,
                      }).then(() => {
                        $("#ticketTaskDialog").modal("hide");
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
            }
          });
        }
      });
      break;
    case "acceptedReturn":
      Swal.fire({
        icon: "question",
        title: "ตอบรับการตีกลับ Ticket?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ยืนยัน!",
        cancelButtonText: "ยังก่อน",
      }).then((result) => {
        if (result.isConfirmed) {
          updateTicket(id, status, "replyReturn");
        }
      });

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

const updateTicket = (id, status, action) => {
  $(".preloader").show();
  $.ajax({
    url: `${baseUrl}admin/ticket/update/status`,
    type: "POST",
    data: {
      id: id,
      status: status,
      action: action,
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

const getCategoryTicket = () => {
  $.ajax({
    url: `${baseUrl}admin/catagories/list`,
    type: "GET",
    success: function (response) {
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].nameCatTh}</option>`;
        }
        $("#changeTicketCategory").html(html);
        $("#changeTicketCategory").selectpicker("refresh");
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

$("#changeTicketCategory").change(function () {
  getSubCategoryTicket();
  getOwnerTicket();
});

const getSubCategoryTicket = () => {
  let catId = $("#changeTicketCategory").val();
  $.ajax({
    url: `${baseUrl}admin/catagories/sub`,
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
        $("#changeTicketSubCategory").html(html);
        $("#changeTicketSubCategory").selectpicker("refresh");
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

const getOwnerTicket = () => {
  let groupId = $("#changeTicketCategory").val();

  $.ajax({
    url: `${baseUrl}admin/ticket/owner/change/get`,
    type: "GET",
    data: {
      groupId: groupId,
    },

    success: function (response) {
      console.log(response);
      if (response.status == 200) {
        let html = "";
        for (let count = 0; count < response.data.length; count++) {
          html += `<option value="${response.data[count].id}">${response.data[count].fullname}</option>`;
        }
        $("#changeTicketOwner").html(html);
        $("#changeTicketOwner").selectpicker("refresh");
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

const getAdminTicketByStatus = (status) => {
  $("#tableTicketAdmin")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
      processing: true,
      stateSave: true,
      searching: true,
      responsive: true,
      bDestroy: true,
      colReorder: {
        realtime: true,
      },
      ajax: {
        url: `${baseUrl}admin/ticket/show/list/by/status`,
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

            if (data.task_status == 5) {
              return `<div>  
                       <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Confirm Ticket" onclick="updateTicketStatus('${data.taskId}', 'approve', 1)" class="btn btn-success btn-sm">
                          <i class="fas fa-check"></i>
                      </a>
                    </div>`;
            }

            if (data.task_status == 6) {
              return `<div>  
                       <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Confirm Ticket" onclick="updateTicketStatus('${data.taskId}', 'acceptedReturn', 1)" class="btn btn-success btn-sm">
                         <i class="fas fa-reply"></i>
                      </a>
                    </div>`;
            }
          },
        },
        {
          data: "ticket_no",
          className: "text-center",
        },
        {
          data: "task_topic",
          className: "text-center",
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            return `<a href="#" onclick="getMoreTicketDetail(${data.taskId})" data-bs-toggle="tooltip"
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

const getMoreTicketDetail = (taskId) => {
  $.ajax({
    url: `${baseUrl}admin/ticket/more/detail`,
    type: "POST",
    data: {
      taskId: taskId,
    },

    success: function (response) {
      if (response.status == 200) {
        // show modal when load data success
        $("#ticketTaskDetailModal").modal("show");

        // and set data in modal with js

        $("#text-ticket-no").html(
          `Ticket no. ${response.data.task[0].ticket_no}`,
        );

        $("#text-topicTask").html(`${response.data.task[0].task_topic}`);

        switch (parseInt(response.data.task[0].task_status)) {
          case 1:
            $("#text-StatusTask").html("รับคำร้อง").css("color", "#6c757d");
            break;

          case 2:
            $("#text-StatusTask")
              .html("เสร็จสิ้น รอการยืนยันจากผู้ใช้")
              .css("color", "#22ca80");
            break;

          case 3:
            $("#text-StatusTask").html("ปฎิเสธ").css("color", "#ff0332");
            break;

          case 4:
            $("#text-StatusTask").html("ปิดงาน").css("color", "#01caf1");
            break;

          default:
            $("#text-StatusTask").html("รอดำเนินการ").css("color", "#fdc16a");
            break;
        }

        $("#task-DetailTask").html(response.data.task[0].task_remark);
        $("#text-CatTask").html(response.data.task[0].catName);
        $("#text-SubCatTask").html(response.data.task[0].subCatName);

        let attach = response.data.task[0].task_attach.split(".");

        if (attach[1] == "jpg" || attach[1] == "png" || attach[1] == "gif") {
          $("#imgTask").attr(
            "src",
            `${baseUrl}store_files_uploaded/${response.data.task[0].task_attach}`,
          );
        }

        let sla;

        let day = moment().format("ddd");

        if (day == "Fri") {
          response.data.task[0].periodTime = 2880;
        }

        if (response.data.task[0].periodTime == 0) {
          sla = "ไม่มีข้อมูล";
        }

        if (
          response.data.task[0].periodTime < 60 &&
          response.data.task[0].periodTime > 0
        ) {
          sla = `${response.data.task[0].periodTime} นาที`;
        }

        if (
          response.data.task[0].periodTime >= 60 &&
          response.data.task[0].periodTime < 1440
        ) {
          sla = `${response.data.task[0].periodTime / 60} ชั่วโมง`;
        }

        if (response.data.task[0].periodTime > 1440) {
          sla = "มากกว่า 1 วัน";
        }

        if (response.data.task[0].periodTime >= 2880) {
          sla = "มากกว่า 2 วัน";
        }

        $("#text-PeriodTask").html(sla);
        $("#text-DetailTask").html(response.data.task[0].task_remark);

        if (response.data.detail.length > 0) {
          response.data.detail.forEach((timeline, index, array) => {
            let html = "";

            html += `<div class="timeline__item">`;
            html += `  <div class="timeline__content">`;
            html += `  <span class="badge badge-danger mb-1">  ${moment
              .unix(array[index].detail_created)
              .format("DD/MM/YYYY HH:mm")} </span>`;
            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> สาเหตุ </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_cause} </p>`;
            html += `  </div>`;
            html += ` </div>`;
            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> การแก้ไข </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_solution} </p>`;
            html += `  </div>`;
            html += ` </div>`;
            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> รายละเอียด </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_remark} </p>`;
            html += `  </div>`;
            html += ` </div>`;
            html += `   </div>`;
            html += `</div>`;

            $(".timeline__items").append(html);
          });

          $(".timeline").timeline();
        }
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

// dashboard
const oftenTicketDashboard = () => {
  $("#oftenTicketTable")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
      processing: true,
      stateSave: true,
      searching: false,
      responsive: true,
      paging: false,
      order: false,
      bDestroy: true,
      bInfo: false,
      colReorder: {
        realtime: true,
      },
      ajax: {
        url: `${baseUrl}admin/dashboard/ticket/often/list`,
        type: "GET",
      },
      columns: [
        {
          targets: 0,
          data: null,
          searchable: true,
          orderable: true,
          render: function (data, type, full, meta) {
            return `<div class="d-flex no-block">
                         <div class="text-left px-5">
                            <h5 class="text-dark mb-0 font-16 font-weight-medium">${data.catName}</h5>
                         <span class="text-muted font-14">${data.subCatName}</span>
                    </div>
                 </div>`;
          },
        },
        {
          data: "ownerTicket",
          className: "text-center",
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            return `<div class="popover-icon">
                     <a class="btn btn-warning rounded-circle btn-sm" href="javascript:void(0)">
                        ${data.countTicket}
                     </a>
                  </div>`;
          },
        },
      ],
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
  let file = $(`#${input.id}`).get(0).files[0];

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
  var tableUserTicket = $("#tableUserTicket")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
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
            if (data.status == 0 || data.status == 5 || data.status == 6) {
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
              return `<div>
                      <a data-toggle="tooltip" title="Success" onclick="updateStatusUserTicket('close', ${data.id})" href="#" class="btn btn-success btn-sm">  <li class="fas fa-check-circle"></li> </a> 
                      <a data-toggle="tooltip" title="Return" onclick="updateStatusUserTicket('return', ${data.id})" href="#" class="btn btn-danger btn-sm">  <li class="fas fa-redo"></li> </a> 
                    </div>`;
            }

            if (data.status == 3) {
              return `<div data-toggle="tooltip" title="Rejected">
                       <a  href="#" class="btn btn-danger btn-sm">  <li class="fas fa-times"></li> </a> 
                    </div>`;
            }

            if (data.status == 4) {
              return `<div data-toggle="tooltip" title="Closes">
                       <a  href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-check-circle"></li> </a> 
                    </div>`;
            }
          },
        },
        {
          data: "ticket_no",
          className: "text-center",
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

        switch (parseInt(response.data[0].task_status)) {
          case 1:
            $("#textStatus").html("รับคำร้อง").css("color", "#6c757d");
            break;

          case 2:
            $("#textStatus")
              .html("เสร็จสิ้น รอการยืนยันจากผู้ใช้")
              .css("color", "#22ca80");
            break;

          case 3:
            $("#textStatus").html("ปฎิเสธ").css("color", "#ff0332");
            break;

          case 4:
            $("#textStatus").html("ปิดงาน").css("color", "#01caf1");
            break;

          default:
            $("#textStatus").html("รอดำเนินการ").css("color", "#fdc16a");
            break;
        }

        $("#titleTicketDetail").html(
          `Ticket no. ${response.data[0].ticket_no}`,
        );
        $("#taskTopic").html(response.data[0].task_topic);
        $("#taskDetail").html(response.data[0].task_remark);
        $("#textCat").html(response.data[0].catName);
        $("#textSubCat").html(response.data[0].subCatName);

        let attach = response.data[0].task_attach.split(".");

        if (attach[1] == "jpg" || attach[1] == "png" || attach[1] == "gif") {
          $("#imgTask").attr(
            "src",
            `${baseUrl}store_files_uploaded/${response.data[0].task_attach}`,
          );
        }

        let sla;

        let day = moment().format("ddd");

        if (day == "Fri") {
          response.data.task[0].periodTime = 2880;
        }

        if (response.data[0].periodTime == 0) {
          sla = "ไม่มีข้อมูล";
        }

        if (
          response.data[0].periodTime < 60 &&
          response.data[0].periodTime > 0
        ) {
          sla = `${response.data[0].periodTime} นาที`;
        }

        if (
          response.data[0].periodTime >= 60 &&
          response.data[0].periodTime < 1440
        ) {
          sla = `${response.data[0].periodTime / 60} ชั่วโมง`;
        }

        if (response.data[0].periodTime > 1440) {
          sla = "มากกว่า 1 วัน";
        }

        if (response.data[0].periodTime >= 2880) {
          sla = "มากกว่า 2 วัน";
        }

        $("#textPeriod").html(sla);
        if (response.data[0].timeline.length > 0) {
          response.data[0].timeline.forEach((timeline, index, array) => {
            let html = "";

            html += `<div class="timeline__item">`;
            html += `  <div class="timeline__content">`;
            html += `  <span class="badge badge-danger mb-1">  ${moment
              .unix(array[index].detail_created)
              .format("DD/MM/YYYY HH:mm")} </span>`;
            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> สาเหตุ </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_cause} </p>`;
            html += `  </div>`;
            html += ` </div>`;

            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> การแก้ไข </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_solution} </p>`;
            html += `  </div>`;
            html += ` </div>`;

            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> รายละเอียด </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_remark} </p>`;
            html += `  </div>`;
            html += ` </div>`;
            html += `   </div>`;
            html += `</div>`;

            $(".timeline__items").append(html);
          });

          $(".timeline").timeline();
        }
      }

      if (response.status == 404 || response.status == 400) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 1000,
        }).then((result) => {
          console.log(response);
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

$("#imgTask").click(function () {
  toggleFullscreen(this);
});

const toggleFullscreen = (elem) => {
  elem = elem || document.documentElement;
  if (
    !document.fullscreenElement &&
    !document.mozFullScreenElement &&
    !document.webkitFullscreenElement &&
    !document.msFullscreenElement
  ) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
      elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
      elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
};

const updateStatusUserTicket = (action, taskId) => {
  if (action == "close") {
    Swal.fire({
      title: "ยืนยันการตรวจสอบ Ticket?",
      text: "Ticket ได้รับการแก้ไขและสามารถใช้งานได้ปกติ",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ตกลง!",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $(".preloader").show();
        $.ajax({
          url: `${baseUrl}user/ticket/update/status`,
          type: "POST",
          data: {
            taskId: taskId,
            status: 4,
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
  }

  if (action == "return") {
    $("#userTicketReturnModal").modal("show");

    $("#ticketFormReturn").on("submit", function (e) {
      e.preventDefault();

      if (!$("#ticketDetailReturn").val()) {
        Swal.fire({
          icon: "warning",
          title: "คำเตือน!",
          text: "กรุณากรอกข้อมูลให้ครบถ้วน!",
        }).then((result) => {
          return false;
        });
      }

      let formData = new FormData(this);

      formData.append("taskId", taskId);

      if ($("#ticketDetailReturn").val()) {
        $.ajax({
          url: `${baseUrl}user/ticket/return`,
          type: "POST",
          data: formData,
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
                  $("#userTicketReturnModal").modal("hide");
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
  }
};

// ========================== end ticket page ============================= //

// ========================= history ticket page ========================== //

const searchHistory = () => {
  let startDate = $("#searchStartDate").val();
  let endDate = $("#searchEndDate").val();
  let status = $("#searchStatus").val();

  $("#tableResultHistory")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
      processing: true,
      stateSave: true,
      searching: true,
      responsive: true,
      bDestroy: true,
      colReorder: {
        realtime: true,
      },
      ajax: {
        url: `${baseUrl}user/history/ticket/search`,
        type: "GET",
        data: {
          startDate: startDate,
          endDate: endDate,
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
            if (
              data.ticket_status == 0 ||
              data.ticket_status == 5 ||
              data.ticket_status == 6
            ) {
              return `<div data-toggle="tooltip" title="Pending...">
                       <a href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-clock"></li> </a>
                    </div>`;
            }

            if (data.ticket_status == 1) {
              return `<div data-toggle="tooltip" title="Accepted">
                       <a  href="#" class="btn btn-warning btn-sm">  <li class="fas fa-clock"></li> </a>
                    </div>`;
            }
            if (data.ticket_status == 2) {
              return `<div>
                      <a data-toggle="tooltip" title="Success" href="#" class="btn btn-success btn-sm">  <li class="fas fa-check-circle"></li> </a>
                    </div>`;
            }

            if (data.ticket_status == 3) {
              return `<div data-toggle="tooltip" title="Rejected">
                       <a  href="#" class="btn btn-danger btn-sm">  <li class="fas fa-times"></li> </a>
                    </div>`;
            }

            if (data.ticket_status == 4) {
              return `<div data-toggle="tooltip" title="Closes">
                       <a  href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-check-circle"></li> </a>
                    </div>`;
            }
          },
        },
        {
          data: "ticket_no",
          className: "text-center",
        },
        {
          data: "ticket_topic",
          className: "text-center",
        },
        {
          data: "catName",
          className: "text-center",
        },
        {
          data: "subCatName",
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            return `<a href="#" onclick="getMoreDetailTicketHistory(${data.ticket_id})" data-bs-toggle="tooltip"
                      data-bs-placement="bottom" title="more detail" class="btn btn-sm btn-cyan">
                      <i class="fas fa-list"></i>
                  </a>`;
          },
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            if (data.ticket_created == 0) {
              return ` <span> ไม่มีข้อมูล </span>`;
            }

            if (data.ticket_created != 0) {
              return `<span class="text-success"> <b> ${moment
                .unix(data.ticket_created)
                .format("DD/MM/YYYY HH:mm")} </b> </span>`;
            }
          },
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            if (data.ticket_updated == 0) {
              return ` <span> ไม่มีข้อมูล </span>`;
            }

            if (data.ticket_updated != 0) {
              return `<span class="text-success"> <b> ${moment
                .unix(data.ticket_updated)
                .format("DD/MM/YYYY HH:mm")} </b> </span>`;
            }
          },
        },
      ],
    });
};

const getMoreDetailTicketHistory = (ticketId) => {
  $.ajax({
    url: `${baseUrl}user/ticket/detail`,
    type: "POST",
    data: {
      ticketId: ticketId,
    },
    success: function (response) {
      if (response.status == 200) {
        $("#ีuserTicketDetailModal").modal("show");

        switch (parseInt(response.data[0].task_status)) {
          case 1:
            $("#textStatusDetail").html("รับคำร้อง").css("color", "#6c757d");
            break;

          case 2:
            $("#textStatusDetail")
              .html("เสร็จสิ้น รอการยืนยันจากผู้ใช้")
              .css("color", "#22ca80");
            break;

          case 3:
            $("#textStatusDetail").html("ปฎิเสธ").css("color", "#ff0332");
            break;

          case 4:
            $("#textStatusDetail").html("ปิดงาน").css("color", "#01caf1");
            break;

          default:
            $("#textStatusDetail").html("รอดำเนินการ").css("color", "#fdc16a");
            break;
        }

        $("#titleHistoryTicketDetail").html(
          `Ticket no. ${response.data[0].ticket_no}`,
        );
        $("#taskTopicDetail").html(response.data[0].task_topic);
        $("#taskRemarkDetail").html(response.data[0].task_remark);
        $("#textCatDetail").html(response.data[0].catName);
        $("#textSubCatDetail").html(response.data[0].subCatName);

        let attach = response.data[0].task_attach.split(".");

        if (attach[1] == "jpg" || attach[1] == "png" || attach[1] == "gif") {
          $("#imgTask").attr(
            "src",
            `${baseUrl}store_files_uploaded/${response.data[0].task_attach}`,
          );
        }

        let sla;

        let day = moment().format("ddd");

        if (day == "Fri") {
          response.data.task[0].periodTime = 2880;
        }

        if (response.data[0].periodTime == 0) {
          sla = "ไม่มีข้อมูล";
        }

        if (
          response.data[0].periodTime < 60 &&
          response.data[0].periodTime > 0
        ) {
          sla = `${response.data[0].periodTime} นาที`;
        }

        if (
          response.data[0].periodTime >= 60 &&
          response.data[0].periodTime < 1440
        ) {
          sla = `${response.data[0].periodTime / 60} ชั่วโมง`;
        }

        if (response.data[0].periodTime > 1440) {
          sla = "มากกว่า 1 วัน";
        }

        if (response.data[0].periodTime >= 2880) {
          sla = "มากกว่า 2 วัน";
        }

        $("#textPeriodDetail").html(sla);
        if (response.data[0].timeline.length > 0) {
          response.data[0].timeline.forEach((timeline, index, array) => {
            let html = "";

            html += `<div class="timeline__item">`;
            html += `  <div class="timeline__content">`;
            html += `  <span class="badge badge-danger mb-1">  ${moment
              .unix(array[index].detail_created)
              .format("DD/MM/YYYY HH:mm")} </span>`;
            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> สาเหตุ </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_cause} </p>`;
            html += `  </div>`;
            html += ` </div>`;

            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> การแก้ไข </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_solution} </p>`;
            html += `  </div>`;
            html += ` </div>`;

            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> รายละเอียด </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_remark} </p>`;
            html += `  </div>`;
            html += ` </div>`;
            html += `   </div>`;
            html += `</div>`;

            $(".timeline__items").append(html);
          });

          $(".timeline").timeline();
        }
      }

      if (response.status == 404 || response.status == 400) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 1000,
        }).then((result) => {
          console.log(response);
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

// ======================= end history ticket page ======================== //

// ======================= report ticket all page ======================== //

$(
  "#main-wrapper > div > div.container-fluid > div.row > div.col-sm-12.col-md-6.col-lg-3.mb-0.mt-4.justify-content-center.text-center > button",
).click(function () {
  let startDate = $("#searchReportAllStartDate").val();
  let endDate = $("#searchReportAllEndDate").val();
  let ticketNo = $("#inputTicketNo").val();

  $("#tableReportTicketAll")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
      processing: true,
      stateSave: true,
      searching: true,
      responsive: true,
      bDestroy: true,
      colReorder: {
        realtime: true,
      },
      ajax: {
        url: `${baseUrl}user/report/ticket/display`,
        type: "GET",
        data: {
          startDate: startDate,
          endDate: endDate,
          ticket_no: ticketNo,
          type: "all",
        },
      },
      columns: [
        {
          // targets: 0,
          // data: null,
          // className: "text-center",
          // searchable: true,
          // orderable: true,
          // render: function (data, type, full, meta) {
          //   return `0`;
          // },
          data: "task_id",
          className: "text-center",
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            if (
              data.task_status == 0 ||
              data.task_status == 5 ||
              data.task_status == 6
            ) {
              return `<div data-toggle="tooltip" title="Pending...">
                       <a href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-clock"></li> </a>
                    </div>`;
            }

            if (data.task_status == 1) {
              return `<div data-toggle="tooltip" title="Accepted">
                       <a  href="#" class="btn btn-warning btn-sm">  <li class="fas fa-clock"></li> </a>
                    </div>`;
            }
            if (data.task_status == 2) {
              return `<div>
                      <a data-toggle="tooltip" title="Success" href="#" class="btn btn-success btn-sm">  <li class="fas fa-check-circle"></li> </a>
                    </div>`;
            }

            if (data.task_status == 3) {
              return `<div data-toggle="tooltip" title="Rejected">
                       <a  href="#" class="btn btn-danger btn-sm">  <li class="fas fa-times"></li> </a>
                    </div>`;
            }

            if (data.task_status == 4) {
              return `<div data-toggle="tooltip" title="Closes">
                       <a  href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-check-circle"></li> </a>
                    </div>`;
            }
          },
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            return `<button onclick="moreDetailTicket('${data.task_id}', 'reportTicketAllDetailModal')" type="button" class="btn waves-effect waves-light btn-outline-dark btn-sm">${data.ticket_no}</button>`;
          },
        },
        {
          data: "task_topic",
          className: "text-center",
        },
        {
          data: "catName",
          className: "text-center",
        },
        {
          data: "ownerName",
          className: "text-center",
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            return `<span class="text-success"> <b> ${moment
              .unix(data.task_created)
              .format("DD/MM/YYYY HH:mm")} </b> </span>`;
          },
        },
      ],
    });
});

// ====================== end report ticket all page ====================== //

// ===================== report ticket status page ======================== //
$(
  "#main-wrapper > div > div.container-fluid > div.row > div.col-sm-12.col-md-6.col-lg-3.mb-0.mt-4.justify-content-center.text-center > button",
).click(function () {
  let startDate = $("#searchReportStatusStartDate").val();
  let endDate = $("#searchReportStatusEndDate").val();
  let statusVal = $(
    "#main-wrapper > div > div.container-fluid > div.row > div:nth-child(3) > form > div > div > select",
  ).val();

  let status = statusVal.length > 0 ? statusVal : [1, 2, 3, 4, 5];

  $("#tableReportTicketStatus")
    .on("xhr.dt", function (e, settings, json, xhr) {
      if (json.status === 404) {
        $(this).DataTable({
          processing: true,
          stateSave: true,
          searching: true,
          responsive: true,
          bDestroy: true,
          colReorder: {
            realtime: true,
          },
        });
      }
    })
    .dataTable({
      processing: true,
      stateSave: true,
      searching: true,
      responsive: true,
      bDestroy: true,
      colReorder: {
        realtime: true,
      },
      ajax: {
        url: `${baseUrl}user/report/ticket/display`,
        type: "GET",
        data: {
          startDate: startDate,
          endDate: endDate,
          status: status,
          type: "status",
        },
      },
      columns: [
        {
          // targets: 0,
          // data: null,
          // className: "text-center",
          // searchable: true,
          // orderable: true,
          // render: function (data, type, full, meta) {
          //   return `0`;
          // },
          data: "task_id",
          className: "text-center",
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            if (
              data.task_status == 0 ||
              data.task_status == 5 ||
              data.task_status == 6
            ) {
              return `<div data-toggle="tooltip" title="Pending...">
                       <a href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-clock"></li> </a>
                    </div>`;
            }

            if (data.task_status == 1) {
              return `<div data-toggle="tooltip" title="Accepted">
                       <a  href="#" class="btn btn-warning btn-sm">  <li class="fas fa-clock"></li> </a>
                    </div>`;
            }
            if (data.task_status == 2) {
              return `<div>
                      <a data-toggle="tooltip" title="Success" href="#" class="btn btn-success btn-sm">  <li class="fas fa-check-circle"></li> </a>
                    </div>`;
            }

            if (data.task_status == 3) {
              return `<div data-toggle="tooltip" title="Rejected">
                       <a  href="#" class="btn btn-danger btn-sm">  <li class="fas fa-times"></li> </a>
                    </div>`;
            }

            if (data.task_status == 4) {
              return `<div data-toggle="tooltip" title="Closes">
                       <a  href="#" class="btn btn-secondary btn-sm">  <li class="fas fa-check-circle"></li> </a>
                    </div>`;
            }
          },
        },
        {
          data: null,
          className: "text-center",
          render: function (data, type, full, meta) {
            return `<button onclick="moreDetailTicket('${data.task_id}', 'reportTicketStatusDetailModal')" type="button" class="btn waves-effect waves-light btn-outline-dark btn-sm">${data.ticket_no}</button>`;
          },
        },
        {
          data: "task_topic",
          className: "text-center",
        },
        {
          data: "catName",
          className: "text-center",
        },
        {
          data: "ownerName",
          className: "text-center",
        },
        {
          data: "task_created",
          className: "text-center",
        },
      ],
    });
});
// =================== end report ticket status page ====================== //

const moreDetailTicket = (taskId, modalId) => {
  $.ajax({
    url: `${baseUrl}user/ticket/detail`,
    type: "POST",
    data: {
      ticketId: taskId,
    },
    success: function (response) {
      if (response.status == 200) {
        $(`#${modalId}`).modal("show");

        switch (parseInt(response.data[0].task_status)) {
          case 1:
            $(".text-report-detail-status")
              .html("รับคำร้อง")
              .css("color", "#6c757d");
            break;

          case 2:
            $(".text-report-detail-status")
              .html("เสร็จสิ้น รอการยืนยันจากผู้ใช้")
              .css("color", "#22ca80");
            break;

          case 3:
            $(".text-report-detail-status")
              .html("ปฎิเสธ")
              .css("color", "#ff0332");
            break;

          case 4:
            $(".text-report-detail-status")
              .html("ปิดงาน")
              .css("color", "#01caf1");
            break;

          default:
            $(".text-report-detail-status")
              .html("รอดำเนินการ")
              .css("color", "#fdc16a");
            break;
        }

        $(".title-modal-report-more-detail").html(
          `Ticket no. ${response.data[0].ticket_no}`,
        );
        $(".text-report-detail-topic").html(response.data[0].task_topic);
        $(".text-report-detail-remark").html(response.data[0].task_remark);
        $(".text-report-detail-catagory").html(response.data[0].catName);
        $(".text-report-detail-catagory-sub").html(response.data[0].subCatName);

        // let attach = response.data[0].task_attach.split(".");

        // if (attach[1] == "jpg" || attach[1] == "png" || attach[1] == "gif") {
        //   $("#imgTask").attr(
        //     "src",
        //     `${baseUrl}store_files_uploaded/${response.data[0].task_attach}`,
        //   );
        // }

        let sla;

        let day = moment().format("ddd");

        if (day == "Fri") {
          response.data.task[0].periodTime = 2880;
        }

        if (response.data[0].periodTime == 0) {
          sla = "ไม่มีข้อมูล";
        }

        if (
          response.data[0].periodTime < 60 &&
          response.data[0].periodTime > 0
        ) {
          sla = `${response.data[0].periodTime} นาที`;
        }

        if (
          response.data[0].periodTime >= 60 &&
          response.data[0].periodTime < 1440
        ) {
          sla = `${response.data[0].periodTime / 60} ชั่วโมง`;
        }

        if (response.data[0].periodTime > 1440) {
          sla = "มากกว่า 1 วัน";
        }

        if (response.data[0].periodTime >= 2880) {
          sla = "มากกว่า 2 วัน";
        }

        $(".text-report-detail-sla").html(sla);
        if (response.data[0].timeline.length > 0) {
          response.data[0].timeline.forEach((timeline, index, array) => {
            let html = "";

            html += `<div class="timeline__item">`;
            html += `  <div class="timeline__content">`;
            html += `  <span class="badge badge-danger mb-1">  ${moment
              .unix(array[index].detail_created)
              .format("DD/MM/YYYY HH:mm")} </span>`;
            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> สาเหตุ </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_cause} </p>`;
            html += `  </div>`;
            html += ` </div>`;

            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> การแก้ไข </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_solution} </p>`;
            html += `  </div>`;
            html += ` </div>`;

            html += `<div class="row">`;
            html += `  <div class="col-md-12">`;
            html += `<small class="text-muted"> รายละเอียด </small>`;
            html += `</div>`;
            html += `  <div class="col-md-12">`;
            html += `    <p class="paragraph-timeline"> ${array[index].detail_remark} </p>`;
            html += `  </div>`;
            html += ` </div>`;
            html += `   </div>`;
            html += `</div>`;

            $(".timeline__items").append(html);
          });

          $(".timeline").timeline();
        }
      }

      if (response.status == 404 || response.status == 400) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 1000,
        }).then((result) => {
          console.log(response);
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

// ======================================================================== //
// ========================== Role USER end =============================== //
// ======================================================================== //
