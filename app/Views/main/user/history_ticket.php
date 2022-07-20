 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">

     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">History tickets</h4>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item"><a
                                     href="<?=  base_url('user/home') ?>"
                                     class="text-muted">Home</a></li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">History list</li>
                         </ol>
                     </nav>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid">
         <div class="card">
             <div class="card-body">

                 <div class="row">
                     <div class="col-sm-12 col-md-6 col-lg-3">
                         <small class="form-text text-muted">วันที่เริ่มต้น</small>
                         <form class="mt-1">
                             <div class="form-group">
                                 <input id="searchStartDate" type="date" class="form-control">
                             </div>
                         </form>
                     </div>

                     <div class="col-sm-12 col-md-6 col-lg-3">
                         <small class="form-text text-muted">วันที่สิ้นสุด</small>
                         <form class="mt-1">
                             <div class="form-group">
                                 <input id="searchEndDate" type="date" class="form-control">
                             </div>
                         </form>
                     </div>


                     <div class="col-sm-12 col-md-6 col-lg-3 mb-0 mt-4 justify-content-center text-center">
                         <button onclick="searchHistory()" type="button" class="btn btn-info w-100">
                             ค้นหา <i class=" fas fa-search"></i> </button>
                     </div>
                 </div>

                 <div class="table-responsive">
                     <table id="tableResultHistory" class="table table-striped table-bordered no-wrap">
                         <thead>
                             <tr>
                                 <th>สถานะ</th>
                                 <th>หัวข้อ</th>
                                 <th>หมวดหมู่</th>
                                 <th>หมวดหมู่ย่อย</th>
                                 <th>รายละเอียด</th>
                                 <th>วันที่สร้าง</th>
                                 <th>อัพเดทล่าสุด</th>
                             </tr>
                         </thead>
                         <tbody></tbody>
                         <tfoot>
                             <tr>
                                 <th>สถานะ</th>
                                 <th>หัวข้อ</th>
                                 <th>หมวดหมู่</th>
                                 <th>หมวดหมู่ย่อย</th>
                                 <th>รายละเอียด</th>
                                 <th>วันที่สร้าง</th>
                                 <th>อัพเดทล่าสุด</th>
                             </tr>
                         </tfoot>
                     </table>
                 </div>

             </div>
         </div>
     </div>
 </div>

 <script>
     $(document).ready(function() {
         $("#tableResultHistory").dataTable({
             processing: true,
             stateSave: true,
             searching: true,
             responsive: true,
             bDestroy: true,
             colReorder: {
                 realtime: true,
             },
         });
     });
 </script>

 <?= $this->endSection();
