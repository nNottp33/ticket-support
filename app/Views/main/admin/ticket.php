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
                         <div class="row">
                             <!-- Column -->
                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover">
                                     <div class="p-2 bg-primary text-center">
                                         <h1 class="font-light text-white">4</h1>
                                         <h6 class="text-white">Total Tickets</h6>
                                     </div>
                                 </div>
                             </div>
                             <!-- Column -->
                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover">
                                     <div class="p-2 bg-success text-center">
                                         <h1 class="font-light text-white">1</h1>
                                         <h6 class="text-white">Complete</h6>
                                     </div>
                                 </div>
                             </div>
                             <!-- Column -->
                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover">
                                     <div class="p-2 bg-secondary text-center">
                                         <h1 class="font-light text-white">1</h1>
                                         <h6 class="text-white">Closes</h6>
                                     </div>
                                 </div>
                             </div>
                             <!-- Column -->
                             <div class="col-md-6 col-lg-3 col-xlg-3">
                                 <div class="card card-hover">
                                     <div class="p-2 bg-warning text-center">
                                         <h1 class="font-light text-white">1</h1>
                                         <h6 class="text-white">Pending</h6>
                                     </div>
                                 </div>
                             </div>
                             <!-- Column -->
                         </div>
                         <div class="table-responsive">
                             <table id="tableTicket" class="table table-striped table-bordered no-wrap">
                                 <thead>
                                     <tr>
                                         <th>สถานนะ</th>
                                         <th>หัวข้อ</th>
                                         <th>รายละเอียด</th>
                                         <th>ผู้แจ้งปัญหา</th>
                                         <th>เวลาที่แจ้งปัญหา</th>
                                         <th>อัพเดทล่าสุด</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="Confirm Ticket" onclick="updateTicketStatus('approve')"
                                                 class="btn btn-success btn-sm">
                                                 <i class="fas fa-check"></i>
                                             </a>
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="Reject Ticket" onclick="updateTicketStatus('reject')"
                                                 class="btn btn-danger btn-sm">
                                                 <i class="fas fa-times"> </i>
                                             </a>
                                         </td>

                                         <td>ขอร้อง MIS</td>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="more" class="btn btn-outline-info btn-sm"
                                                 onclick="getDetailTicket('id?')"><i class="fas fa-list"></i></a>
                                         </td>

                                         <td class="text-center">
                                             <a href="#" onclick="getUserDetail('Test@successmore.com')"
                                                 data-bs-toggle="tooltip" data-bs-placement="bottom" title="more"
                                                 class="btn btn-sm btn-light">
                                                 Test@successmore.com
                                             </a>
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>
                                     </tr>

                                     <tr>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="pending..." onclick="updateTicketStatus('close')"
                                                 class="btn btn-warning btn-sm">
                                                 <li class="far fa-clock"></li>
                                             </a>
                                         </td>

                                         <td>ขอร้อง NAV</td>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="more" class="btn btn-outline-info btn-sm"
                                                 onclick=" getDetailTicket('id?')"><i class="fas fa-list"></i></a>
                                         </td>

                                         <td class="text-center">
                                             <a href="#" onclick="getUserDetail('Test2@successmore.com')"
                                                 data-bs-toggle="tooltip" data-bs-placement="bottom" title="more"
                                                 class="btn btn-sm btn-light">
                                                 Test2@successmore.com
                                             </a>
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>
                                     </tr>


                                     <tr>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="closes task" class="btn btn-secondary btn-sm">
                                                 <li class="far fa-check-circle"></li>
                                             </a>
                                         </td>

                                         <td>ขอร้อง POS</td>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="more" class="btn btn-outline-info btn-sm"
                                                 onclick=" getDetailTicket('id?')"><i class="fas fa-list"></i></a>
                                         </td>

                                         <td class="text-center">
                                             <a href="#" onclick="getUserDetail('Test3@successmore.com')"
                                                 data-bs-toggle="tooltip" data-bs-placement="bottom" title="more"
                                                 class="btn btn-sm btn-light">
                                                 Test3@successmore.com
                                             </a>
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>
                                     </tr>

                                     <tr>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="complete!" class="btn btn-outline-success btn-sm">
                                                 <li class="fas fa-check-circle"></li>
                                             </a>
                                         </td>

                                         <td>ขอร้อง อื่น ๆ</td>
                                         <td class="text-center">
                                             <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                 title="more" class="btn btn-outline-info btn-sm"
                                                 onclick=" getDetailTicket('id?')"><i class="fas fa-list"></i></a>
                                         </td>

                                         <td class="text-center">
                                             <a href="#" onclick="getUserDetail('Test4@successmore.com')"
                                                 data-bs-toggle="tooltip" data-bs-placement="bottom" title="more"
                                                 class="btn btn-sm btn-light">
                                                 Test4@successmore.com
                                             </a>
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>

                                         <td>
                                             08/06/2022
                                         </td>
                                     </tr>
                                 </tbody>
                                 <tfoot>
                                     <tr>
                                         <th>สถานนะ</th>
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
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
             </div>
         </div>
     </div>
 </div>


 <div class="modal fade clear-modal" id="getUserDetaillModal" tabindex="-1" role="dialog">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="getUserDetaillModalLabel">ข้อมูลผู้ใช้</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <p id="userDetails"></p>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
             </div>
         </div>
     </div>
 </div>

 <script>
     $(document).ready(function() {
         var tableTicket = $("#tableTicket").dataTable({
             "processing": true,
             "stateSave": true,
             "searching": true,
             "responsive": true,
             "bDestroy": true,
         });
     });
 </script>
 <?= $this->endSection();
