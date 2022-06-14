<?php
namespace App\Filters;

 use CodeIgniter\HTTP\RequestInterface;
 use CodeIgniter\HTTP\ResponseInterface;
 use CodeIgniter\Filters\FilterInterface;

 class RoleUserFilter implements FilterInterface
 {
     public function before(RequestInterface $request, $agument = null)
     {
         if (session()->get('class') != 'user') {
             return redirect()->to(base_url('/admin'));
         }
     }

     public function after(RequestInterface $request, ResponseInterface $response, $agument = null)
     {
     }
 }
