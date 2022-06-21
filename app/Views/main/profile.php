 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">

     <div class="container-fluid">
         <div class="row">
             <div class="col-md-6 col-12 text-center mb-4">
                 <div class="card card-profile">
                     <div class="card-body">
                         <div class="row my-3">
                             <div class="col-md-12 col-12 text-center">
                                 <img src="<?= base_url() ?>/assets/images/users/5.jpg"
                                     alt="image" class="rounded-circle" width="200">
                             </div>
                         </div>
                         <div class="row my-3 text-center m-auto justify-content-center">
                             <div class="col-md-6 col-6 btn btn-danger cursor-pointer">
                                 แก้ไขโปรไฟล์
                             </div>
                             <div class="col-md-6 col-6 btn btn-cyan cursor-pointer">
                                 เปลี่ยนรหัสผ่าน
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>



 <?= $this->endSection();
