 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
     style="background:url(<?= base_url() ?>/assets/images/big/auth-bg.jpg) no-repeat center center;">
     <div class="auth-box m-auto justify-content-center row">

         <div class="col-lg-10 col-md-7 bg-white">
             <div class="p-3">
                 <div class="text-center">
                     <img src="<?= base_url() ?>/assets/images/logo-full.png"
                         alt="Successmore logo" width="300">
                 </div>

                 <form class="mt-4">
                     <div class="row">
                         <div class="col-lg-12">
                             <div class="form-group">
                                 <label class="text-dark" for="uname">Email</label>
                                 <input class="form-control input-login" id="email" type="email"
                                     placeholder="กรอก Email" required>
                             </div>
                         </div>

                         <div class="col-lg-12">
                             <div class="form-group">
                                 <label class="text-dark" for="pwd">รหัสผ่าน</label>
                                 <input class="form-control input-login" id="pwd" type="password"
                                     placeholder="กรอกรหัสผ่าน">
                             </div>
                         </div>
                         <div class="col-lg-12 text-center mb-5">
                             <button id="btnLogin" type="button" onclick="login()" disabled
                                 class="btn btn-block btn-primary">Sign
                                 In</button>
                         </div>

                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php $this->endSection();
