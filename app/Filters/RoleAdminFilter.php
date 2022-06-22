<?php
namespace App\Filters;

 use CodeIgniter\HTTP\RequestInterface;
 use CodeIgniter\HTTP\ResponseInterface;
 use CodeIgniter\Filters\FilterInterface;

 class RoleAdminFilter implements FilterInterface
 {
     public function before(RequestInterface $request, $agument = null)
     {
         $userModel = new \App\Models\UserModel();
         $checkStatus = $userModel->where('id', session()->get('id'))->first();

         if (session()->get('logged_in') && $checkStatus['status'] == 1 && $checkStatus['class'] == session()->get('class')) {
             if (session()->get('class') != 'admin') {
                 return redirect()->to(base_url('/user/home'));
             }
         } else {
             if (session()->get('logged_in')) {
                 session()->destroy();
             }
             return redirect()->to(base_url('/auth'));
         }
     }

     public function after(RequestInterface $request, ResponseInterface $response, $agument = null)
     {
     }
 }
