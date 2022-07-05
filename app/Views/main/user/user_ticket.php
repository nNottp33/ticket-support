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


         <!-- grid tickets -->
         <div class="row">
             <!-- column -->
             <div class="col-lg-3 col-md-6">
                 <!-- Card -->
                 <div class="card card-ticket card-ticketDetail" onclick="showDetailUserTicker()">
                     <div class="card-body">
                         <h4 class="card-title">Ticket no. 3455234</h4>
                         <h5 class="card-subtitle mb-2 text-muted"> ขอร้อง MIS </h5>
                         <p class="card-text">
                             ขอสร้างข้อมูลบน Dashboard
                             <span class="card-sub-detail">
                                 ระยะเวลาดำเนินการ 60 นาที
                             </span>
                         </p>


                         <a href="#" class="btn btn-warning active" aria-current="page">
                             <li class="fas fa-clock"> </li> กำลังดำเนินการ
                         </a>

                     </div>
                 </div>
                 <!-- Card -->
             </div>
         </div>
         <!-- grid tickets end -->
     </div>
 </div>


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


     <div class="modal fade clear-modal" id="userTicketDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="titleTicketDetail">ขอร้อง MIS</h5>
                     <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
                 </div>
                 <div class="modal-body">
                     <div class="">
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <label for="">รายละเอียด</label>
                                     <p class="">
                                         ขอสร้างข้อมูลบน Dashboard .....
                                     </p>
                                 </div>
                             </div>
                         </div>
                         <div class="m-auto text-center justify-content-center">
                             <svg class="bd-placeholder-img card-img-top" width="100%" height="180"
                                 xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap"
                                 preserveAspectRatio="xMidYMid slice" focusable="false">
                                 <title>Placeholder</title>
                                 <rect width="100%" height="100%" fill="#868e96"></rect><text x="50%" y="50%"
                                     fill="#dee2e6" dy=".3em">Image cap</text>
                             </svg>
                         </div>

                         <div class="my-2">
                             <span>
                                 ระยะเวลาดำเนินการ 60 นาที
                             </span>
                         </div>

                         <div class="my-2">
                             <span>
                                 สถานะ <b class="ml-2 ticket-detail-status"> กำลังดำเนินการ </b>
                             </span>
                         </div>
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <?= $this->endSection();
