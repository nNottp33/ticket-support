 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">

     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Dashboard report</h4>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item"><a
                                     href="<?=  base_url('admin/report/dashboard') ?>"
                                     class="text-muted">Report</a></li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">dashboard report</li>
                         </ol>
                     </nav>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid">

         <div class="row">
             <div class="col-sm-12 col-md-6 col-lg-3">
                 <small class="form-text text-muted">ผู้รับผิดชอบ</small>
                 <form class="mt-1">
                     <div class="form-group">
                         <select id="ownerReportDash" multiple title="เลือกผู้รับผิดชอบ" data-actions-box="true"
                             data-live-search="true" data-width="100%" class="form-control selectpicker show-tick">
                         </select>
                     </div>
                 </form>
             </div>

             <div class="col-sm-12 col-md-6 col-lg-3">
                 <small class="form-text text-muted">วันที่เริ่มต้น</small>
                 <form class="mt-1">
                     <div class="form-group">
                         <input id="startDateReportDash" type="date" class="form-control">
                     </div>
                 </form>
             </div>

             <div class="col-sm-12 col-md-6 col-lg-3">
                 <small class="form-text text-muted">วันที่สิ้นสุด</small>
                 <form class="mt-1">
                     <div class="form-group">
                         <input id="endDateReportDash" type="date" class="form-control">
                     </div>
                 </form>
             </div>

             <div class="col-sm-12 col-md-6 col-lg-3 mb-0 mt-4 justify-content-center text-center">
                 <button id="reportDash" type="button" class="btn btn-info w-100">
                     ค้นหา <i class=" fas fa-search"></i> </button>
             </div>
         </div>

         <div class="table-responsive">
             <table id="tableReportDashboard" class="table table-striped table-bordered no-wrap ">
                 <thead>
                     <tr>
                         <!-- <th>#</th> -->
                         <th>Category</th>
                         <th>Total ticket</th>
                         <th>Owner</th>
                         <th>Ticket IN SLA</th>
                         <th>Ticket OUT SLA</th>
                     </tr>
                 </thead>
                 <tbody></tbody>
                 <tfoot>
                     <tr>
                         <!-- <th>#</th> -->
                         <th>Category</th>
                         <th>Total ticket</th>
                         <th>Owner</th>
                         <th>Ticket IN SLA</th>
                         <th>Ticket OUT SLA</th>
                     </tr>
                 </tfoot>
             </table>
         </div>
     </div>
 </div>


 <script>
     $(document).ready(function() {
         getOwnerReport()
         $('#tableReportDashboard').dataTable({
             processing: true,
             stateSave: true,
             searching: true,
             responsive: true,
             bDestroy: true,
             colReorder: {
                 realtime: true,
             },
         })

     });
 </script>

 <?= $this->endSection();
