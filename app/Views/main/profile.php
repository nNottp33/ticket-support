 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>

 <h1><?= session()->get('email') ?>
 </h1>
 <div class="page-wrapper">
     <div class="container-fluid">
         <div class="row">
             <div class="col-md-6 col-12 text-center mb-4">
                 <div class="card card-profile">
                     <div class="card-body">
                         <div class="row my-3">
                             <div class="col-md-12 col-12 text-center">
                                 <div style="border-radius: 50% !important;"
                                     class="porfileImageDefault btn btn-primary rounded-circle btn-circle">
                                     <img>
                                     <div class="centered"><?php echo mb_strimwidth(session()->get('email'), 0, 1); ?>
                                     </div>
                                     </img>
                                 </div>
                             </div>
                         </div>
                         <div class="row my-3 text-center m-auto justify-content-center">
                             <div onclick="showProfile()" class="col-md-6 col-6 btn btn-danger cursor-pointer"
                                 id="btnShowProfile">
                                 แก้ไขโปรไฟล์
                             </div>
                             <div class="col-md-6 col-6 btn btn-cyan cursor-pointer" id="btnShowChangePass">
                                 เปลี่ยนรหัสผ่าน
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>


 <!-- Modal change password -->
 <div class="modal fade" id="changePassModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="changePassModalLabel">เปลี่ยนรหัสผ่าน</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal"></a>
             </div>
             <div class="modal-body">
                 <div class="col-sm-12">
                     <div class="form-group">
                         <label for="newPass">รหัสผ่านใหม่</label>
                         <div class="input-group">
                             <input type="password" class="form-control" id="newPassword" placeholder="รหัสผ่านใหม่">
                             <span class="input-group-text cursor-pointer">
                                 <i onclick="togglePassword(this.id, 'changePassEye')" id="toggleChangePassword"
                                     class="fas fa-eye-slash"></i>
                             </span>
                         </div>
                     </div>
                     <div class="form-group">
                         <label for="confirmPass">ยืนยันรหัสผ่านใหม่</label>
                         <div class="input-group">
                             <input type="password" class="form-control" id="confirmNewPassword"
                                 placeholder="รหัสผ่านใหม่">
                             <span class="input-group-text cursor-pointer">
                                 <i onclick="togglePassword(this.id, 'confirmChangePassEye')"
                                     id="tooggleConfirmPassword" class="fas fa-eye-slash"></i>
                             </span>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" id="btnCancelChangePass">ยกเลิก</button>
                 <button type="button" onclick="getOTP()" class="btn btn-primary">ขอรหัสยืนยัน</button>
             </div>
         </div>
     </div>
 </div>
 <!-- Modal change password end -->

 <!-- Modal confirm otp -->
 <div class="modal fade clear-modal" id="confirmOtpModal" tabindex="-1" role="dialog"
     aria-labelledby="confirmOtpModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="confirmOtpModalLabel">ยืนยัน OTP</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal"></a>
             </div>
             <div class="modal-body">
                 <div class="col-sm-12">
                     <div class="text-center m-auto">
                         <span> Ref : </span> <q>
                             <span id="textRefOtp"></span>
                         </q>
                     </div>
                     <div class="form-group">
                         <label for="newPassword">รหัสยืนยัน 6 หลัก</label>
                         <input type="number" class="form-control" id="otp" placeholder="กรอกรหัสยืนยัน 6 หลัก">
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" id="btnCancelOtp">ยกเลิก</button>
                 <button type="button" class="btn btn-primary" id="btnChangePassword">ยืนยัน</button>
             </div>
         </div>
     </div>
 </div>
 <!-- Modal confirm otp end -->


 <!-- Modal profile -->
 <div class="modal fade" id="profileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="profileModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="profileModalLabel">ข้อมูลส่วนตัว</h5>
                 <a href="#" class="icon-close" onclick="checkModal()" data-bs-dismiss="modal"></a>
             </div>
             <div class="modal-body">
                 <div>
                     <div class="form-group">
                         <input type="text" class="form-control profile-input-empId" disabled id="profileEmpId"
                             placeholder="รหัสพนักงาน">
                     </div>

                     <div class="form-group">
                         <input type="text" class="form-control profile-input-email" disabled id="profileEmail"
                             placeholder="Email">
                     </div>

                     <div class="form-group">
                         <input type="text" class="form-control profile-input" id="profileName" placeholder="ชื่อ">
                     </div>

                     <div class="form-group">
                         <input type="text" class="form-control profile-input" id="profileNickname"
                             placeholder="ชื่อเล่น">
                     </div>

                     <div class="form-group">
                         <input type="tel" maxlength="10" class="form-control profile-input" id="profileTel"
                             placeholder="เบอร์ติดต่อ">
                     </div>

                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" onclick="checkModal()" class="btn btn-secondary"
                     data-bs-dismiss="modal">ปิด</button>
                 <button type="button" onclick="toggleEditProfile()" id="btnProfile"
                     class="btn btn-primary">แก้ไข</button>
                 <button type="button" onclick="updateProfile()" id="btnUpdateProfile"
                     class="btn btn-primary">อัพเดทข้อมูล</button>
             </div>
         </div>
     </div>
 </div>


 <!-- Modal profile end -->
 <?= $this->endSection();
