 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">
     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Hello, Jason!</h3>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                             </li>
                         </ol>
                     </nav>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid">
         <div class="card-group">
             <div class="card border-right">
                 <div class="card-body">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <div class="d-inline-flex align-items-center">
                                 <h2 class="text-dark mb-1 font-weight-medium">236</h2>
                                 <span
                                     class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">
                                     500</span>
                             </div>
                             <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">New Clients</h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="card border-right">
                 <div class="card-body">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                                 18,306</h2>
                             <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Ticket of Month
                             </h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i class="fas fa-tags"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="card border-right">
                 <div class="card-body">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <div class="d-inline-flex align-items-center">
                                 <h2 class="text-dark mb-1 font-weight-medium">1,538</h2>
                             </div>
                             <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">New Pending tickets</h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i class="far fa-clock"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="card">
                 <div class="card-body">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <h2 class="text-dark mb-1 font-weight-medium">864</h2>
                             <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Ticket complete</h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i class="far fa-flag"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="row">
             <div class="col-md-12 col-lg-6">
                 <div class="card">
                     <div class="card-body">
                         <div class="d-flex align-items-center mb-4">
                             <div>
                                 <h3 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                                     Often tickets</h3>
                                 <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">ปัญหาที่พบบ่อย
                                 </h6>
                             </div>
                         </div>
                         <div class="table-responsive">
                             <table class="table no-wrap v-middle mb-0">
                                 <thead>
                                     <tr class="border-0">
                                         <th class="border-0 font-14 font-weight-medium text-muted">ปัญหา</th>
                                         </th>
                                         <th class="border-0 font-14 font-weight-medium text-muted px-2">
                                             ผู้รับผิดชอบ
                                         </th>
                                         <th class="border-0 font-14 font-weight-medium text-muted">จำนวนครั้ง</th>

                                     </tr>
                                 </thead>
                                 <tbody>

                                     <tr>
                                         <td class="px-2 py-">
                                             <div class="d-flex no-block align-items-center">
                                                 <div class="text-center m-auto">
                                                     <h5 class="text-dark mb-0 font-16 font-weight-medium">ขอร้อง MIS
                                                     </h5>
                                                     <span class="text-muted font-14">ขอสร้างข้อมูลบน Dashboard</span>
                                                 </div>
                                             </div>
                                         </td>
                                         <td class="text-muted text-center px-2 py-4 font-14">นาย A
                                         </td>
                                         <td class="px-2 py-4 text-center">
                                             <div class="popover-icon">
                                                 <a class="btn btn-warning rounded-circle btn-sm"
                                                     href="javascript:void(0)">
                                                     10
                                                 </a>
                                             </div>
                                         </td>
                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <?= $this->endSection();
