 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">
     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Hello, <?= session()->get('nickname') == '-' ? 'Unknown' : session()->get('nickname') ?>!
                 </h3>
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
         <?php if (isset($data)) : ?>
         <div class="card-group">
             <div class="card border-right">
                 <div class="card-body">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <div class="d-inline-flex align-items-center">
                                 <h2 class="text-dark mb-1 font-weight-medium">
                                     <?=$data['count_user_new'] ?>
                                 </h2>
                                 <span
                                     class="badge bg-primary font-16 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">
                                     <small class="font-10">Total</small>
                                     <?=$data['count_user_all'] ?>
                                 </span>
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
                                 <?=$data['count_ticket_month'] ?>
                             </h2>
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
                                 <h2 class="text-dark mb-1 font-weight-medium"> <?=$data['count_ticket_complete_month'] ?>
                                 </h2>
                             </div>
                             <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Tickets complete</h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i class="far fa-flag"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="card">
                 <div class="card-body">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <h2 class="text-dark mb-1 font-weight-medium"><?=$data['count_ticket_reject_month'] ?>
                             </h2>
                             <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Tickets reject</h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i class="far fa-window-close"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <?php else : ?>

         <div class="card-group">
             <div class="card border-right animate-pulse ">
                 <div class="card-body animate-pulse ">
                     <div class="d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <div class="d-inline-flex align-items-center">
                                 <h2 class="load-number mb-1 font-weight-medium">
                                     0
                                 </h2>
                                 <span
                                     class="load-number badge bg-primary font-16 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">
                                     <small class="font-10">Total</small>
                                     0
                                 </span>
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
                     <div class="d-flex animate-pulse d-lg-flex d-md-block align-items-center">
                         <div>
                             <h2 class="load-number animate-pulse mb-1 w-100 text-truncate font-weight-medium">
                                 0
                             </h2>
                             <h6 class="animate-pulse text-muted font-weight-normal mb-0 w-100 text-truncate">Ticket of
                                 Month
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
                     <div class="d-flex animate-pulse d-lg-flex d-md-block align-items-center">
                         <div>
                             <div class="animate-pulse d-inline-flex align-items-center">
                                 <h2 class="load-number mb-1 opacity-7 font-weight-medium">0
                                 </h2>
                             </div>
                             <h6 class="animate-pulse text-muted opacity-7 mb-0 w-100 text-truncate">Tickets
                                 complete</h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i class="far fa-flag"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="card">
                 <div class="card-body">
                     <div class="animate-pulse d-flex d-lg-flex d-md-block align-items-center">
                         <div>
                             <h2 class="load-number opacity-7 animate-pulse mb-1 font-weight-medium">
                                 0
                             </h2>
                             <h6 class="opacity-7 animate-pulse text-muted mb-0 w-100 text-truncate">Tickets
                                 reject</h6>
                         </div>
                         <div class="ml-auto mt-md-3 mt-lg-0">
                             <span class="opacity-7 text-muted"><i class="far fa-window-close"></i></span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <?php endif; ?>

         <div class="row">
             <div class="col-md-12 col-lg-8">
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
                             <table id="oftenTicketTable" class="table table-striped no-wrap">
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
                                 <tbody></tbody>
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
         oftenTicketDashboard()
         $(".load-number").each(function() {
             $(this).data('count', parseInt($(this).html(), 10));
             $(this).html('0');
             count($(this));
         });
     });
 </script>
 <?= $this->endSection();
