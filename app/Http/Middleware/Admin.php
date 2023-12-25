<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// tambahan akses ke model database Akses
use App\Models\Akses; 

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // cek apakah session id sudah diset, kalau belum lempar ke halaman login
        if(isset(auth()->user()->id)){
            $akses = Akses::getGrupUser(auth()->user()->id);
            foreach($akses as $p):
                $kelompok = $p->kelompok;
            endforeach;
        }else{
            return redirect('/');
        }
        // tambahkan logik pengecekan roles admin melalui session kelompok
        // fungsi auth check untuk memeriksa apakah user 
        // sedang dalam keadaan terotentikasi atau tidak
        if ( (!auth()->check()) || ($kelompok!=='admin') ) {
            abort(403);
        }
        
        return $next($request);
    }
}
