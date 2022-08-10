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
                         <input name="searchReportAllEndDate" id="searchReportAllEndDate" type="date"
                             class="form-control">
                     </div>
                 </form>
             </div>

             <div class="col-sm-12 col-md-6 col-lg-3">
                 <small class="form-text text-muted">Ticket No.</small>
                 <form class="mt-1">
                     <div class="form-group">
                         <input placeholder="Ticket No" name="inputTicketNo" id="inputTicketNo" type="text"
                             class="form-control">
                     </div>
                 </form>
             </div>

             <div class="col-sm-12 col-md-6 col-lg-3 mb-0 mt-4 justify-content-center text-center">
                 <button id="btnUserReportTicketAll" type="button" class="btn btn-info w-100">
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


 <!-- ticket detail modal -->
 <div class="modal fade clear-modal" id="reportTicketAllDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="reportTicketAllDetailModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title title-modal-report-more-detail"></h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div id="reportTaskAll" class="container">
                     <div class='row'>
                         <div class="col-md-4">
                             <h5>
                                 สถานะ
                             </h5>
                         </div>
                         <div class="col-md-8">
                             <label class="text-report-detail-status"> </label>
                         </div>
                     </div>

                     <div class='row'>
                         <div class="col-md-4 ">
                             <h5>
                                 หัวข้อ
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label class="text-report-detail-topic"> </label>
                         </div>
                     </div>

                     <div class='row'>
                         <div class="col-md-4 ">
                             <h5>
                                 หมวดหมู่
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label class="text-report-detail-catagory"> </label>
                         </div>
                     </div>

                     <div class='row'>
                         <div class="col-md-4">
                             <h5>
                                 หมวดหมู่ย่อย
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label class="text-report-detail-catagory-sub"> </label>
                         </div>
                     </div>

                     <div class='row'>
                         <div class="col-md-4">
                             <h5>
                                 เวลาดำเนินการ <small class="text-muted font-10">/ ชั่วโมง
                                     (30 นาที = 0.5 ชั่วโมง)
                                 </small>
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label class="text-danger text-report-detail-sla"> </label>
                         </div>
                     </div>

                     <div class="form-group">
                         <h5 for="">รายละเอียด</h5>
                         <p class="pl-3 text-dark text-wrap text-report-detail-remark"></p>
                     </div>


                     <div class="mb-4">
                         <div id="accordion" class="custom-accordion mb-4">
                             <div class="card mb-0">
                                 <div class="card-header" id="headingTwo">
                                     <h5 class="m-0">
                                         <a class="custom-accordion-title collapsed d-block pt-2 pb-2"
                                             data-toggle="collapse" href="#collapseTwo" aria-expanded="false"
                                             aria-controls="collapseTwo">
                                             Ticket timeline <span class="float-right"><i
                                                     class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                         </a>
                                     </h5>
                                 </div>
                                 <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                     data-parent="#accordion">
                                     <div class="card-body">
                                         <div class="timeline">
                                             <div class="timeline__wrap">
                                                 <div class="timeline__items"></div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- ticket detail modal end -->

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
