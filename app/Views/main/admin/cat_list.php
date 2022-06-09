 <?= $this->extend('template/layout') ?>

 <?php $this->section('content'); ?>
 <div class="page-wrapper">
     <div class="page-breadcrumb">
         <div class="row">
             <div class="col-7 align-self-center">
                 <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">ข้อมูลหมวดหมู่ Tickets</h4>
                 <div class="d-flex align-items-center">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb m-0 p-0">
                             <li class="breadcrumb-item"><a
                                     href="<?=  base_url('admin/cat/list') ?>"
                                     class="text-muted">Catagories</a></li>
                             <li class="breadcrumb-item text-muted active" aria-current="page">Catagories list</li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <div class="col-5 align-self-center">
                 <div class="customize-input float-right">
                     <a href="#" class="btn btn-primary" onclick="insertCategory()">
                         <i class="fas fa-plus"></i>
                         <span class="hide-menu">
                             เพิ่ม ticket cat
                         </span>
                     </a>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid">
         <div class="table-responsive">
             <table id="tableCat" class="table table-striped table-bordered no-wrap">
                 <thead>
                     <tr>
                         <th>จัดการข้อมูล</th>
                         <th>สถานะ</th>
                         <th>ชื่อหมวดหมู่</th>
                         <th>ผู้รับผิดชอบ</th>
                         <th>รายละเอียดหมวดย่อย</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td class="text-center">
                             <a href="#" onclick="editCat()" class="btn btn-primary btn-sm">
                                 <i class="fas fa-edit"></i>
                             </a>
                             <a href="#" class="btn btn-danger btn-sm">
                                 <i class="fas fa-trash"></i>
                             </a>
                         </td>
                         <td class="status-badge">
                             <span class="badge badge-success"> เปิดใช้งาน </span>
                         </td>
                         <td class="text-center">หมวดหมู่ 1</td>
                         <td class="text-center">
                             <a href="#" onclick="getAdminDetail('ข้อมูลนาย A')" data-bs-toggle="tooltip"
                                 data-bs-placement="bottom" title="more" class="btn btn-sm btn-light">
                                 นาย A
                             </a>
                         </td>
                         <td class="text-center">
                             <a href="#" onclick="getSubCatagories('id ? ขอร้อง POS')" data-bs-toggle="tooltip"
                                 data-bs-placement="bottom" title="more" class="btn btn-sm btn-dark">
                                 <i class="fas fa-list"></i>
                             </a>
                         </td>
                     </tr>
                 </tbody>
                 <tfoot>
                     <tr>
                         <th>จัดการข้อมูล</th>
                         <th>สถานะ</th>
                         <th>ชื่อหมวดหมู่</th>
                         <th>ผู้รับผิดชอบ</th>
                         <th>รายละเอียดหมวดย่อย</th>
                     </tr>
                 </tfoot>
             </table>

         </div>
     </div>
 </div>

 <div class="modal fade clear-modal" id="catModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="catModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="catModalLabel">ข้อมูลหมวดหมู่</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="form-group mb-4">
                     <select id="selectCategory" name="category" onchange="test()" title="กรุณาเลือกหมวดหมู่"
                         class="selectpicker form-control" data-live-search="true" data-width="100%">
                         <option value="0"
                             data-content="<span class='btn btn-link text-dark'><i class='fa fa-plus text-dark'></i> เพิ่ม</span>">
                         </option>
                         <option value="1">ขอร้อง MIS</option>
                         <option value="2">ขอร้อง NAV</option>
                     </select>
                 </div>


                 <div class="form-group">
                     <input type="text" class="form-control" id="inputNewCatagory" aria-describedby="subCatText"
                         placeholder="New catagory">
                 </div>


                 <div class="form-group">
                     <input type="text" class="form-control" id="inputSubCat" aria-describedby="subCatText"
                         placeholder="Sub catagory">
                 </div>

                 <div class="form-group">
                     <input type="email" class="form-control" id="inputEmail" aria-describedby="subCatText"
                         placeholder="Email">
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                 <button type="button" class="btn btn-primary">บันทึกข้อมูล</button>
             </div>
         </div>
     </div>
 </div>


 <div class="modal fade clear-modal" id="subCatModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="subCatModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="subCatModalLabel">ข้อมูลหมวดหมู่ย่อย</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="table-responsive">
                     <table id="tableSubCat" class="table table-striped table-bordered no-wrap">
                         <thead>
                             <tr>
                                 <th>จัดการข้อมูล</th>
                                 <th>สถานะ</th>
                                 <th>ชื่อหมวดหมู่ย่อย</th>
                                 <th>ระยะเวลาดำเนินการ</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr>
                                 <td class="text-center">
                                     <a href="#" onclick="editSubCat()" class="btn btn-primary btn-sm">
                                         <i class="fas fa-edit"></i>
                                     </a>
                                     <a href="#" class="btn btn-danger btn-sm">
                                         <i class="fas fa-trash"></i>
                                     </a>
                                 </td>
                                 <td class="status-badge">
                                     <span class="badge badge-success"> เปิดใช้งาน </span>
                                 </td>
                                 <td class="text-center">หมวดหมู่ 1</td>
                                 <td class="text-center">1 ชม</td>
                             </tr>
                         </tbody>
                         <tfoot>
                             <tr>
                                 <th>จัดการข้อมูล</th>
                                 <th>สถานะ</th>
                                 <th>ชื่อหมวดหมู่ย่อย</th>
                                 <th>ระยะเวลาดำเนินการ</th>
                             </tr>
                         </tfoot>
                     </table>

                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
             </div>
         </div>
     </div>
 </div>


 <div class="modal fade clear-modal" id="adminDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="catModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="catModalLabel">ข้อมูลผู้รับผิดชอบ</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">

             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
             </div>
         </div>
     </div>
 </div>

 <script>
     $(document).ready(function() {
         var tableCat = $("#tableCat").dataTable({
             "processing": true,
             "stateSave": true,
             "searching": true,
             "responsive": true,
             "bDestroy": true,
         });

         var tableSubCat = $("#tableSubCat").dataTable({
             "processing": true,
             "stateSave": true,
             "searching": true,
             "responsive": true,
             "bDestroy": true,
         });
     });
 </script>
 <?= $this->endSection();
