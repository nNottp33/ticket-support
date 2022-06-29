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
                 <tbody></tbody>
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


 <!-- catagory modal -->
 <div class="modal fade clear-modal" id="catModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="catModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="catModalLabel">ข้อมูลหมวดหมู่</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">

                 <div class="input-group mb-3">
                     <div class="customize-input mx-1 w-100">
                         <small class="form-text text-muted">ชื่อหมวดหมู่</small>
                         <input type="text" class="form-control" id="inputCat" aria-describedby="catText"
                             placeholder="ชื่อหมวดหมู่">
                     </div>
                 </div>

             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                 <button type="button" class="btn btn-primary">บันทึกข้อมูล</button>
             </div>
         </div>
     </div>
 </div>
 <!-- catagory modal end  -->



 <!-- sub category modal -->
 <div class="modal fade clear-modal" id="subCatModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="subCatModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="subCatModalLabel">ข้อมูลหมวดหมู่ย่อย</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="float-right my-2">
                     <a href="#" onclick="insertSubCat()" class="btn btn-primary">เพิ่มข้อมูล</a>
                 </div>
                 <div class="table-responsive">
                     <table id="tableSubCat" class="table table-striped table-bordered no-wrap">
                         <thead>
                             <tr>
                                 <th>จัดการข้อมูล</th>
                                 <th>สถานะ</th>
                                 <th>ชื่อหมวดหมู่ย่อย</th>
                                 <th>รายละเอียด</th>
                                 <th>ระยะเวลาดำเนินการ</th>
                             </tr>
                         </thead>
                         <tbody></tbody>
                         <tfoot>
                             <tr>
                                 <th>จัดการข้อมูล</th>
                                 <th>สถานะ</th>
                                 <th>ชื่อหมวดหมู่ย่อย</th>
                                 <th>รายละเอียด</th>
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
 <!-- sub category modal end -->


 <!-- sub category insert modal -->
 <div class="modal fade clear-modal" id="subCatSaveModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="subCatSaveModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="subCatSaveModalLabel">ข้อมูลหมวดหมู่ย่อย</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">

                 <div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">ชื่อหมวดหมู่ย่อย</small>
                             <input type="text" class="form-control" required id="inputNameSubCat"
                                 placeholder="ชื่อหมวดหมู่ย่อย">
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">รายละเอียด</small>
                             <textarea id="inputDetailSubCat" class="form-control" rows="3"
                                 placeholder="รายละเอียด"></textarea>
                         </div>
                     </div>

                     <div class="input-group mb-3">
                         <div class="customize-input mx-1 w-100">
                             <small class="form-text text-muted">ระยะเวลาดำเนินการ / ชั่วโมง</small>
                             <input type="number" class="form-control" required id="inputSla"
                                 placeholder="ระยะเวลาดำเนินการ / ชั่วโมง">
                         </div>
                     </div>

                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                 <button type="button" id="btnSaveSubCat" onclick="saveSubCat()"
                     class="btn btn-primary">บันทึกข้อมูล</button>
                 <button type="button" id="btnUpdateSubCat" class="btn btn-primary">แก้ไขข้อมูล</button>
             </div>
         </div>
     </div>
 </div>
 <!-- sub category insert modal end -->

 <!-- modal admin detail -->
 <div class="modal fade clear-modal" id="adminDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="catModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="catModalLabel">ข้อมูลผู้รับผิดชอบ</h5>
                 <a href="#" class="icon-close" data-bs-dismiss="modal" aria-label="Close"></a>
             </div>
             <div class="modal-body">
                 <div class="table-responsive">
                     <table id="tableListOwner" class="table table-striped table-bordered no-wrap">
                         <thead>
                             <tr>
                                 <th>รหัสพนักงาน</th>
                                 <th>ชื่อ</th>
                                 <th>Email</th>
                                 <th>ตำแหน่ง</th>
                             </tr>
                         </thead>
                         <tbody></tbody>
                         <tfoot>
                             <tr>
                                 <th>รหัสพนักงาน</th>
                                 <th>ชื่อ</th>
                                 <th>Email</th>
                                 <th>ตำแหน่ง</th>
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

 <!-- modal admin detail end -->
 <?= $this->endSection();
