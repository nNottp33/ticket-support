 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">

     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-12 align-self-center">
                 <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Ticket List</h4>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item">
                                 <a href="index.html" class="text-muted">Apps</a>
                             </li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">Ticket List</li>
                         </ol>
                     </nav>
                 </div>
             </div>

         </div>
     </div>

     <div class="container-fluid">

         <div class="row">
             <div class="col-12">
                 <div class="card">
                     <div class="card-body">
                         <div class="row m-auto justify-content-center">
                             <!-- Column -->
                             <div class="col-md-4 col-lg-2 col-xlg-2">
                                 <div onclick="getAdminTicket()" class="card card-hover card-counter">
                                     <div class="p-2 bg-primary text-center">
                                         <h1 id="totalTicket" class="font-light text-white"></h1>
                                         <h6 class="text-white">Total Tickets</h6>
                                     </div>
                                 </div>
                             </div>

                             <!-- Column -->
                             <div class="col-md-4 col-lg-2 col-xlg-2">
                                 <div onclick="getAdminTicketByStatus([0])" class="card card-hover card-counter">
                                     <div class="p-2 bg-cyan text-center">
                                         <h1 id="newTicket" class="font-light text-white"></h1>
                                         <h6 class="text-white">New</h6>
                                     </div>
                                 </div>
                             </div>

                             <!-- Column -->
                             <div class="col-md-4 col-lg-2 col-xlg-2">
                                 <div onclick="getAdminTicketByStatus([1,5,6])" class="card card-hover card-counter">
                                     <div class="p-2 bg-warning text-center">
                                         <h1 id="pendingTicket" class="font-light text-white"></h1>
                                         <h6 class="text-white">Pending</h6>
                                     </div>
                                 </div>
                             </div>

                             <!-- Column -->
                             <div class="col-md-4 col-lg-2 col-xlg-2">
                                 <div onclick="getAdminTicketByStatus([2])" class="card card-hover card-counter">
                                     <div class="p-2 bg-success text-center">
                                         <h1 id="completeTicket" class="font-light text-white"></h1>
                                         <h6 class="text-white">Complete</h6>
                                     </div>
                                 </div>
                             </div>

                             <!-- Column -->
                             <div class="col-md-4 col-lg-2 col-xlg-2">
                                 <div onclick="getAdminTicketByStatus([4])" class="card card-hover card-counter">
                                     <div class="p-2 bg-secondary text-center">
                                         <h1 id="closeTicket" class="font-light text-white"></h1>
                                         <h6 class="text-white">Closes</h6>
                                     </div>
                                 </div>
                             </div>
                         </div>


                         <div class="table-responsive">
                             <table id="tableTicketAdmin" class="table table-striped table-bordered no-wrap">
                                 <thead>
                                     <tr>
                                         <th>สถานะ</th>
                                         <th>Ticket no.</th>
                                         <th>หัวข้อ</th>
                                         <th>รายละเอียด</th>
                                         <th>ผู้แจ้งปัญหา</th>
                                         <th>เวลาที่แจ้งปัญหา</th>
                                         <th>อัพเดทล่าสุด</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                                 <tfoot>
                                     <tr>
                                         <th>สถานนะ</th>
                                         <th>Ticket no.</th>
                                         <th>หัวข้อ</th>
                                         <th>รายละเอียด</th>
                                         <th>ผู้แจ้งปัญหา</th>
                                         <th>เวลาที่แจ้งปัญหา</th>
                                         <th>อัพเดทล่าสุด</th>
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

 <div class="modal fade clear-modal" id="getTicketDetailModal" tabindex="-1" role="dialog">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="getTicketDetailModalLabel">รายละเอียด Ticket</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <p id="aticleDetails"></p>
                 <div class="">
                     <div class="row">
                         <div class="col-md-12">
                             <div class="form-group">
                                 <h4> <b> ขอร้อง NAV </b></h4>
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
                             <rect width="100%" height="100%" fill="#868e96"></rect><text x="50%" y="50%" fill="#dee2e6"
                                 dy=".3em">Image cap</text>
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
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
             </div>
         </div>
     </div>
 </div>


 <div class="modal fade clear-modal" id="getUserDetaillModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" role="dialog">
     <div class="modal-dialog">
         <div class="modal-content content-card-user">
             <div class="modal-header header-card-user">
                 <!-- <h5 class="modal-title" id="getUserDetaillModalLabel">ข้อมูลผู้แจ้งปัญหา</h5> -->
                 <a href="#" class="icon-close close-card-user" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-12 col-sm-12 col-lg-12">
                         <div class="single_advisor_profile wow fadeInUp" data-wow-delay="0.3s"
                             style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                             <div class="advisor_thumb">
                                 <div class="porfileDefault">
                                     <img src="">
                                     <div id="textProfile" class="centered"></div>
                                     </img>
                                 </div>

                                 <div class="social-info">
                                     <span class="designation" id="text-ticketEmpId"> </span>
                                 </div>
                             </div>

                             <div class="single_advisor_details_info">
                                 <h6 id="text-ticketFullname"></h6>
                                 <p id="text-ticketPosition" class="designation"></p>
                                 <p id="text-ticketDepartment" class="designation"></p>
                                 <p id="text-ticketTel" class="designation"></p>
                                 <p id="text-ticketMail" class="designation"></p>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <!-- <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
             </div> -->
         </div>
     </div>
 </div>

 <!-- modal reject ticket -->
 <div class="modal fade clear-modal" id="rejectTicketModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="rejectTicketModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="rejectTicketModalLabel">แก้ไข Ticket</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <form id="ticketFormChange" enctype="multipart/form-data" method="post">


                         <div class="form-group mb-4">
                             <small class="form-text text-muted">หมวดหมู่</small>
                             <select id="changeTicketCategory" name="changeTicketCategory" title="กรุณาเลือกหมวดหมู่"
                                 class="selectpicker form-control" data-live-search="true" data-width="100%">
                             </select>
                         </div>

                         <div class="form-group mb-4">
                             <small class="form-text text-muted">หมวดหมู่ย่อย</small>
                             <select id="changeTicketSubCategory" name="changeTicketSubCategory"
                                 title="กรุณาเลือกหมวดหมู่ย่อย" class="selectpicker form-control"
                                 data-live-search="true" data-width="100%">
                             </select>
                         </div>

                         <div class="form-group">
                             <small class="form-text text-muted">ผู้รับผิดชอบ</small>
                             <select id="changeTicketOwner" name="changeTicketOwner" title="กรุณาเลือกผู้รับผิดชอบ"
                                 class="selectpicker form-control" data-live-search="true" data-width="100%">
                             </select>
                         </div>

                     </form>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                     <button type="submit" id="btnChangeTicket" class="btn btn-primary">บันทึกข้อมูล</button>
                 </div>

             </div>
         </div>
     </div>
 </div>
 <!-- modal reject ticket end -->


 <!-- modal ticket detail dialog -->
 <div class="modal fade clear-modal" id="ticketTaskDialog" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="ticketTaskDialogLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="ticketTaskDialogLabel">Ticket reply</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <form id="ticketFormSuccess" enctype="multipart/form-data" method="post">
                         <div class="input-group mb-3">
                             <div class="customize-input mx-1 w-100">
                                 <small class="form-text text-muted">สาเหตุ (หากไม่มีให้ใส่ - )</small>
                                 <input type="text" class="form-control" required id="cause" name="cause"
                                     placeholder="สาเหตุ">
                             </div>
                         </div>


                         <div class="input-group mb-3">
                             <div class="customize-input mx-1 w-100">
                                 <small class="form-text text-muted">วิธีแก้ปัญหา (หากไม่มีให้ใส่ - )</small>
                                 <textarea id="solution" name="solution" class="form-control" rows="3"
                                     placeholder="วิธีแก้ปัญหา"></textarea>
                             </div>
                         </div>


                         <div class="input-group mb-3">
                             <div class="customize-input mx-1 w-100">
                                 <small class="form-text text-muted">** หมายเหตุ (หากไม่มีให้ใส่ - )</small>
                                 <textarea id="remark" name="remark" class="form-control" rows="3"
                                     placeholder="หมายเหตุ"></textarea>
                             </div>
                         </div>


                         <div class="form-group">
                             <small class="form-text text-muted">แนบไฟล์ตัวอย่าง</small>
                             <div class="mb-3">
                                 <input multiple="true" onchange="previewFile(this)" class="form-control" type="file"
                                     name="previewImgTask" id="previewImgTask">
                             </div>
                         </div>

                         <div class="form-group div-previewImage-user">
                             <small class="form-text text-muted">ภาพประกอบ</small>
                             <div class="display-upload-img">
                                 <img class="previewImg" id="previewImg" src="" alt="Image preview" width="100%"
                                     height="180">
                             </div>
                         </div>

                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                     <button type="submit" id="btnSendTicket" class="btn btn-primary">ส่งข้อมูล</button>
                 </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- modal ticket detail dialog end -->


 <!-- ticket detail modal -->
 <div class="modal fade clear-modal" id="ticketTaskDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="ticketTaskDetailModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="text-ticket-no"></h5>
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
                             <label id="text-StatusTask"> </label>
                         </div>
                     </div>


                     <div class='row'>
                         <div class="col-md-4 ">
                             <h5>
                                 หัวข้อ
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label id="text-topicTask"> </label>
                         </div>
                     </div>


                     <div class='row'>
                         <div class="col-md-4 ">
                             <h5>
                                 หมวดหมู่
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label id="text-CatTask"> </label>
                         </div>
                     </div>

                     <div class='row'>
                         <div class="col-md-4">
                             <h5>
                                 หมวดหมู่ย่อย
                             </h5>
                         </div>
                         <div class="col-md-8 text-dark">
                             <label id="text-SubCatTask"> </label>
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
                             <label class="text-danger" id="text-PeriodTask"> </label>
                         </div>
                     </div>

                     <div class="form-group">
                         <h5 for="">รายละเอียด</h5>
                         <p id="text-DetailTask" class="pl-3 text-dark text-wrap"></p>
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
         getAdminTicket();
         countTicket();
     });
 </script>
 <?= $this->endSection();
