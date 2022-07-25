 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">

     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Report tickets</h4>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item"><a
                                     href="<?=  base_url('user/report/ticket/all') ?>"
                                     class="text-muted">Report</a></li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">report all</li>
                         </ol>
                     </nav>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid">
         <div class="row">
             <div class="col-sm-12 col-md-6 col-lg-3">
                 <small class="form-text text-muted">Ticket No.</small>
                 <form class="mt-1">
                     <div class="form-group">
                         <input name="inputTicketNo" id="inputTicketNo" type="text" class="form-control">
                     </div>
                 </form>
             </div>
         </div>

         <div class="row">
             <div class="col-sm-12 col-md-6 col-lg-3">
                 <small class="form-text text-muted">วันที่เริ่มต้น</small>
                 <form class="mt-1">
                     <div class="form-group">
                         <input name="searchReportAllStartDate" id="searchReportAllStartDate" type="date"
                             class="form-control">
                     </div>
                 </form>
             </div>

             <div class="col-sm-12 col-md-6 col-lg-3">
                 <small class="form-text text-muted">วันที่สิ้นสุด</small>
                 <form class="mt-1">
                     <div class="form-group">
                         <input name="searchReportAllStartDate" id="searchReportAllDate" type="date"
                             class="form-control">
                     </div>
                 </form>
             </div>

             <div class="col-sm-12 col-md-6 col-lg-3 mb-0 mt-4 justify-content-center text-center">
                 <button type="button" class="btn btn-info w-100">
                     ค้นหา <i class=" fas fa-search"></i> </button>
             </div>
         </div>
         <div class="table-responsive">
             <table id="tableReportTicketAll" class="table table-striped table-bordered no-wrap">
                 <thead>
                     <tr>
                         <th>#</th>
                         <th>สถานะ</th>
                         <th>Ticket No.</th>
                         <th>หัวข้อ</th>
                         <th>หมวดหมู่</th>
                         <th>ผู้รับผิดชอบ</th>
                         <th>วันที่สร้าง</th>
                     </tr>
                 </thead>
                 <tbody></tbody>
                 <tfoot>
                     <tr>
                         <th>#</th>
                         <th>สถานะ</th>
                         <th>Ticket No.</th>
                         <th>หัวข้อ</th>
                         <th>หมวดหมู่</th>
                         <th>ผู้รับผิดชอบ</th>
                         <th>วันที่สร้าง</th>
                     </tr>
                 </tfoot>
             </table>
         </div>
     </div>
 </div>

 <script>
     $(document).ready(function() {
         $('#tableReportTicketAll').dataTable({
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
