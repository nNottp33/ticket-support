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
                             <li class="breadcrumb-item"><a href="index.html" class="text-muted">User</a></li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">User list</li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <div class="col-5 align-self-center">
                 <div class="customize-input float-right">
                     <a data-bs-toggle="modal" data-bs-target="#addUserModal" href="#" class="btn btn-primary">
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
                                 <div class="card card-hover">
                                     <div class="p-2 bg-primary text-center">
                                         <h1 class="font-light text-white">3</h1>
                                         <h6 class="text-white">Total Users</h6>
                                     </div>
                                 </div>
                             </div>

                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover">
                                     <div class="p-2 bg-success text-center">
                                         <h1 class="font-light text-white">1</h1>
                                         <h6 class="text-white">Online</h6>
                                     </div>
                                 </div>
                             </div>


                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover">
                                     <div class="p-2 bg-warning text-center">
                                         <h1 class="font-light text-white">1</h1>
                                         <h6 class="text-white">Locked</h6>
                                     </div>
                                 </div>
                             </div>


                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover">
                                     <div class="p-2 bg-danger text-center">
                                         <h1 class="font-light text-white">1</h1>
                                         <h6 class="text-white">Denied</h6>
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
                                         <th>ชื่อ - สกุล</th>
                                         <th>ชื่อเล่น</th>
                                         <th>Email</th>
                                         <th>เบอร์ติดต่อ</th>
                                         <th>แผนก</th>
                                         <th>ตำแหน่ง</th>
                                         <th>เข้าสู่ระบบล่าสุด</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td class="text-center">
                                             <a href="#" class="btn btn-primary btn-sm">
                                                 <i class="fas fa-edit"></i>
                                             </a>
                                             <a href="#" class="btn btn-danger btn-sm">
                                                 <i class="fas fa-trash"></i>
                                             </a>
                                         </td>
                                         <td class="status-badge">
                                             <span class="badge badge-success"> เปิดใช้งาน </span>
                                         </td>
                                         <td>
                                             <span>SC0001</span>
                                         </td>
                                         <td>
                                             <span>นาย ศุกร์ หรรษา</span>
                                         </td>
                                         <td>
                                             <span>ศุกร์</span>
                                         </td>
                                         <td>Suk@successmore.com</td>
                                         <td>085-123-4567</td>
                                         <td>
                                             <span>ฝ่าย ​IT</span>
                                         </td>
                                         <td>
                                             <span>ผู้ดูแลระบบ</span>
                                         </td>
                                         <td>07/06/2565</td>
                                     </tr>


                                     <tr>
                                         <td class="text-center">
                                             <a href="#" class="btn btn-primary btn-sm">
                                                 <i class="fas fa-edit"></i>
                                             </a>
                                             <a href="#" class="btn btn-danger btn-sm">
                                                 <i class="fas fa-trash"></i>
                                             </a>
                                         </td>
                                         <td class="status-badge">
                                             <span class="badge badge-danger"> ปิดใช้งาน </span>
                                         </td>
                                         <td>
                                             <span>SC0002</span>
                                         </td>
                                         <td>
                                             <span>นาย ประยวย ตวยเร็ว</span>
                                         </td>
                                         <td>
                                             <span>ตูบ</span>
                                         </td>
                                         <td>​Toop@successmore.com</td>
                                         <td>085-123-4567</td>
                                         <td>
                                             <span>ฝ่ายรักษาความปลอดภัย</span>
                                         </td>
                                         <td>
                                             <span>รปภ.</span>
                                         </td>
                                         <td>07/06/2565</td>
                                     </tr>

                                     <tr>
                                         <td class="text-center">
                                             <a href="#" class="btn btn-primary btn-sm">
                                                 <i class="fas fa-edit"></i>
                                             </a>
                                             <a href="#" class="btn btn-danger btn-sm">
                                                 <i class="fas fa-trash"></i>
                                             </a>
                                         </td>
                                         <td class="status-badge">
                                             <span class="badge badge-warning"> ล็อค </span>
                                         </td>
                                         <td>
                                             <span>SC0003</span>
                                         </td>
                                         <td>
                                             <span>นาง ปารี นาโง</span>
                                         </td>
                                         <td>
                                             <span>เอ๋</span>
                                         </td>
                                         <td>Ae@successmore.com</td>
                                         <td>085-123-4567</td>
                                         <td>
                                             <span>ฝ่ายประชาสัมพันธ์</span>
                                         </td>
                                         <td>
                                             <span>PR</span>
                                         </td>
                                         <td>07/06/2565</td>
                                     </tr>

                                 </tbody>
                                 <tfoot>
                                     <tr>
                                         <th>จัดการข้อมูล</th>
                                         <th>สถานะ</th>
                                         <th>รหัสพนักงาน</th>
                                         <th>ชื่อ - สกุล</th>
                                         <th>ชื่อเล่น</th>
                                         <th>Email</th>
                                         <th>เบอร์ติดต่อ</th>
                                         <th>แผนก</th>
                                         <th>ตำแหน่ง</th>
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

 <script>
     $(document).ready(function() {
         var table = $("#tableUser").dataTable({
             "processing": true,
             "stateSave": true,
             "searching": true,
             "responsive": true,
             "bDestroy": true,
         });
     });
 </script>
 <?= $this->endSection();
