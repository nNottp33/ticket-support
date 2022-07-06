 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">

     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">ข้อมูลผู้ใช้</h4>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item"><a
                                     href="<?=  base_url('admin/users/list') ?>"
                                     class="text-muted">User</a></li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">User list</li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <div class="col-5 align-self-center">
                 <div class="customize-input float-right">
                     <a href="#" onclick="addUser()" class="btn btn-primary" data-bs-toggle="modal"
                         data-bs-target="#userModal">
                         <i class="fas fa-plus"></i>
                         <span class="hide-menu">
                             เพิ่มผู้ใช้
                         </span>
                     </a>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid">
         <div class="row">
             <div class="col-12">
                 <div class="card">
                     <div class="card-body">
                         <div class="row">

                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover card-counter" onclick="userList()">
                                     <div class="p-2 bg-primary text-center">
                                         <h1 id="countTotalUser" class="font-light text-white counter-user"></h1>
                                         <h6 class="text-white">Total Users</h6>
                                     </div>
                                 </div>
                             </div>

                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover card-counter" onclick="userListByStatus(1)">
                                     <div class="p-2 bg-success text-center">
                                         <h1 id="countOnlineUser" class="font-light text-white counter-user"></h1>
                                         <h6 class="text-white">Online</h6>
                                     </div>
                                 </div>
                             </div>


                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover card-counter" onclick="userListByStatus(0)">
                                     <div class="p-2 bg-warning text-center">
                                         <h1 id="countSuspendedUser" class="font-light text-white counter-user"></h1>
                                         <h6 class="text-white">Suspended</h6>
                                     </div>
                                 </div>
                             </div>


                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover card-counter">
                                     <div class="p-2 bg-danger text-center">
                                         <h1 id="countDeniedUser" class="font-light text-white counter-user"></h1>
                                         <h6 class="text-white">Denied / Deleted</h6>
                                     </div>
                                 </div>
                             </div>

                         </div>
                         <div class="table-responsive">
                             <table id="tableUser" class="table table-striped table-bordered no-wrap">
                                 <thead>
                                     <tr>
                                         <th>จัดการข้อมูล</th>
                                         <th>สถานะ</th>
                                         <th>รหัสพนักงาน</th>
                                         <th>Email</th>
                                         <th>ชื่อ - สกุล</th>
                                         <th>ชื่อเล่น</th>
                                         <th>เบอร์ติดต่อ</th>
                                         <th>แผนก</th>
                                         <th>ตำแหน่ง</th>
                                         <th>Role</th>
                                         <th>เข้าสู่ระบบล่าสุด</th>
                                     </tr>
                                 </thead>
                                 <tbody></tbody>
                                 <tfoot>
                                     <tr>
                                         <th>จัดการข้อมูล</th>
                                         <th>สถานะ</th>
                                         <th>รหัสพนักงาน</th>
                                         <th>Email</th>
                                         <th>ชื่อ - สกุล</th>
                                         <th>ชื่อเล่น</th>
                                         <th>เบอร์ติดต่อ</th>
                                         <th>แผนก</th>
                                         <th>ตำแหน่ง</th>
                                         <th>Role</th>
                                         <th>เข้าสู่ระบบล่าสุด</th>
                                     </tr>
                                 </tfoot>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>


 <!--  add user Modal-->
 <div class="modal fade clear-modal" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="userModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="staticBackdropLabel">ข้อมูลผู้ใช้</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="px-5 py-4">

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">รหัสพนักงาน</small>
                             <input type="text" class="form-control" required id="inputEmpId" placeholder="รหัสพนักงาน">
                         </div>
                     </div>


                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">คำนำหน้าชื่อ</small>
                             <select class="form-control selectpicker" title="คำนำหน้าชื่อ" required id="selectPrefix">
                                 <option value="นาย">นาย</option>
                                 <option value="นางสาว">นางสาว</option>
                                 <option value="นาง">นาง</option>
                             </select>
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">ชื่อ</small>
                             <input type="text" class="form-control" required id="inputName" placeholder="ชื่อ">
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">นามสกุล</small>
                             <input type="text" class="form-control" required id="inputLastname" placeholder="นามสกุล">
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">ชื่อเล่น</small>
                             <input type="text" class="form-control" required id="inputNickname" placeholder="ชื่อเล่น">
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">Email</small>
                             <input type="email" class="form-control" required id="inputEmail" placeholder="Email">
                         </div>
                     </div>

                     <div class="input-group mb-3" id="div-inputPassword">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">รหัสผ่าน</small>
                             <div class="input-group">
                                 <input type="passoword" class="form-control" required id="inputPassword"
                                     placeholder="รหัสผ่าน">
                                 <span class="input-group-text cursor-pointer">
                                     <i onclick="togglePassword(this.id, 'insertEye')" id="togglePassInsertUser"
                                         class="fas fa-eye-slash"></i>
                                 </span>
                             </div>
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">เบอร์ติดต่อ</small>
                             <input type="tel" maxlength="10" class="form-control" required id="inputPhone"
                                 placeholder="เบอร์ติดต่อ">
                         </div>
                     </div>

                     <div class="input-group mb-3" id="div-selectDepartment">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">แผนก</small>
                             <select class="form-control selectpicker" data-live-search="true" name="selectDepartment"
                                 title="แผนก" id="selectDepartment">

                             </select>
                         </div>
                     </div>

                     <div class="input-group mb-3" id="div-selectPosition">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">ตำแหน่ง</small>
                             <select class="form-control selectpicker" data-live-search="true" title="ตำแหน่ง"
                                 id="selectPosition">
                             </select>
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">ระดับผู้ใช้งาน</small>
                             <select class="form-control selectpicker" required title="ระดับผู้ใช้งาน" id="classUser">
                                 <option value="admin">Admin</option>
                                 <option value="user">User</option>
                             </select>
                         </div>
                     </div>



                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                     <button type="button" id="btnSaveUser" onclick="saveUser()"
                         class="btn btn-primary">บันทึกข้อมูล</button>
                     <button type="button" id="btnUpdateUser" class="btn btn-primary">บันทึกข้อมูล</button>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- end add user modal-->

 <script>
     $(document).ready(function() {
         let userAll = userList();
         var countTotal = countUser();
     });
 </script>
 <?= $this->endSection();
