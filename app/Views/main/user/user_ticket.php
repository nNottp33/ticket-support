 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">

     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Your tickets</h4>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item"><a
                                     href="<?=  base_url('user/home') ?>"
                                     class="text-muted">Home</a></li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">Ticket list</li>
                         </ol>
                     </nav>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid">
         <!-- floating button -->
         <div class="floating-container">
             <div onclick="insertTicket()" class="floating-button" data-bs-toggle="modal"
                 data-bs-target="#userTicketModal">+</div>
         </div>
         <!-- end floating button -->

         <div class="table-responsive">
             <table id="tableUserTicket" class="table table-striped table-bordered no-wrap">
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

 <!-- create ticket modal -->
 <div class="modal fade clear-modal" id="userTicketModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="staticBackdropLabel">สร้าง Ticket</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <form id="ticketForm" enctype="multipart/form-data" method="post">
                         <div class="form-group mb-4">
                             <small class="form-text text-muted">หัวข้อ</small>
                             <input type="text" class="form-control" name="ticketTopic" id="ticketTopic"
                                 placeholder="หัวข้อ Ticket">
                         </div>

                         <div class="form-group mb-4">
                             <small class="form-text text-muted">หมวดหมู่</small>
                             <select id="userSelectCategory" name="userSelectCategory" onchange="getSubCatagory()"
                                 title="กรุณาเลือกหมวดหมู่" class="selectpicker form-control" data-live-search="true"
                                 data-width="100%">
                             </select>
                         </div>

                         <div class="form-group mb-4">
                             <small class="form-text text-muted">หมวดหมู่ย่อย</small>
                             <select id="userSelectSubCategory" name="userSelectSubCategory"
                                 title="กรุณาเลือกหมวดหมู่ย่อย" class="selectpicker form-control"
                                 data-live-search="true" data-width="100%">
                             </select>
                         </div>

                         <div class="form-group">
                             <small class="form-text text-muted">รายละเอียด</small>
                             <textarea id="ticketDetail" name="ticketDetail" class="form-control" rows="3"
                                 placeholder="อธิบายรายละเอียดปัญหา"></textarea>
                         </div>

                         <div class="form-group">
                             <small class="form-text text-muted">แนบไฟล์ตัวอย่าง</small>
                             <div class="mb-3">
                                 <input accept="image/*" multiple="true" onchange="previewFile(this)"
                                     class="form-control" type="file" name="file" id="file">
                             </div>

                         </div>

                         <div class="form-group">
                             <small class="form-text text-muted">ภาพประกอบ</small>
                             <div class="display-upload-img">
                                 <img class="previewImg" accept="image/png, image/jpeg" id="previewImg" src=""
                                     alt="Image preview" width="100%" height="180">
                             </div>
                         </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                     <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                 </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- create ticket modal end -->

 <!-- return ticket modal -->
 <div class="modal fade clear-modal" id="userTicketReturnModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="userTicketReturnModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="userTicketReturnModalLabel">Return Ticket</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <form id="ticketFormReturn" enctype="multipart/form-data" method="post">
                         <div class="form-group">
                             <small class="form-text text-muted">รายละเอียด</small>
                             <textarea id="ticketDetailReturn" name="ticketDetailReturn" class="form-control" rows="3"
                                 placeholder="อธิบายปัญหาที่ยังไม่สำเร็จ"></textarea>
                         </div>

                         <div class="form-group">
                             <small class="form-text text-muted">แนบไฟล์ตัวอย่าง</small>
                             <div class="mb-3">
                                 <input accept="image/*" multiple="true" onchange="previewFile(this)"
                                     class="form-control" type="file" name="fileReturn" id="fileReturn">
                             </div>

                         </div>

                         <div class="form-group">
                             <small class="form-text text-muted">ภาพประกอบ</small>
                             <div class="display-upload-img">
                                 <img class="previewImg" accept="image/png, image/jpeg" id="previewImgReturn" src=""
                                     alt="Image preview" width="100%" height="180">
                             </div>
                         </div>


                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                     <button type="submit" id="btnReturnTicket" class="btn btn-primary">บันทึกข้อมูล</button>
                 </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- return ticket modal end -->

 <!-- ticket detail modal -->
 <div class="modal fade clear-modal" id="userTicketDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="userTicketDetailModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="titleTicketDetail"></h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div id="ticketTask" class="container">
                     <div class='row'>
                         <div class="col-md-4">
                             <h5>
                                 สถานะ
                             </h5>
                         </div>
                         <div class="col-md-8">
                             <label id="textStatus"> </label>
                         </div>
                     </div>

                     <div class='row'>
                         <div class="col-md-4 ">
                             <h5>
                                 หมวดหมู่
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label id="textCat"> </label>
                         </div>
                     </div>

                     <div class='row'>
                         <div class="col-md-4">
                             <h5>
                                 หมวดหมู่ย่อย
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label id="textSubCat"> </label>
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
                             <label class="text-danger" id="textPeriod"> </label>
                         </div>
                     </div>

                     <div class="form-group">
                         <h5 for="">รายละเอียด</h5>
                         <p id="taskDetail" class="pl-3 text-dark text-wrap"></p>
                     </div>


                     <div class="mb-4">
                         <div id="accordion" class="custom-accordion mb-4">
                             <div class="card mb-0">
                                 <div class="card-header" id="headingOne">
                                     <h5 class="m-0">
                                         <a class="custom-accordion-title d-block pt-2 pb-2" data-toggle="collapse"
                                             href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                             ตัวอย่างไฟล์แนบ <span class="float-right"><i
                                                     class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                         </a>
                                     </h5>
                                 </div>
                                 <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                     data-parent="#accordion">
                                     <div class="card-body">
                                         <div class="display-upload-img" alt="Image preview" aria-label="Image preview">
                                             <img class="image-task" id="imgTask" src="" alt="Image preview">
                                         </div>
                                     </div>
                                 </div>
                             </div>

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
         getUserTicket();

     });
 </script>

 <?= $this->endSection();
