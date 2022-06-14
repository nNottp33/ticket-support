<?php
namespace App\Filters;

 use CodeIgniter\HTTP\RequestInterface;
 use CodeIgniter\HTTP\ResponseInterface;
 use CodeIgniter\Filters\FilterInterface;

 class RoleAdminFilter implements FilterInterface
 {
     public function before(RequestInterface $request, $agument = null)
     {
         if (session()->get('class') != 'admin') {
             return redirect()->to(base_url('/user/home'));
         }
     }

     public function after(RequestInterface $request, ResponseInterface $response, $agument = null)
     {
     }
 }
