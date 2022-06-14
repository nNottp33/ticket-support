<?php
namespace App\Filters;

 use CodeIgniter\HTTP\RequestInterface;
 use CodeIgniter\HTTP\ResponseInterface;
 use CodeIgniter\Filters\FilterInterface;

 class LoggedInFilter implements FilterInterface
 {
     public function before(RequestInterface $request, $agument = null)
     {
         if (session()->has('logged_in')) {
             return redirect()->back();
         }
     }

     public function after(RequestInterface $request, ResponseInterface $response, $agument = null)
     {
         //  code..
     }
 }
