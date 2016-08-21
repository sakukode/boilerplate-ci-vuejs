<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('dateindo'))
{
	function dateindo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
   // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
        $bulanindo = array("Januari", "Februari", "Maret",
                           "April", "Mei", "Juni",
                           "Juli", "Agustus", "September",
                           "Oktober", "November", "Desember");
    
        $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
        $waktu = substr($date, 11); //memisahkan format waktu menggunakan substring
        
        $result = $tgl . " " . $bulanindo[(int)$bulan-1] . " ". $tahun;
        return $result;
}
}

if ( ! function_exists('dateindo2'))
{
  function dateindo2($date,$choice) { // fungsi atau method untuk mengubah tanggal ke format indonesia
   // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
        $bulanindo = array("Jan", "Feb", "Mar",
                           "Apr", "Mei", "Jun",
                           "Jul", "Agust", "Sept",
                           "Okt", "Nov", "Des");
    
        $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
        $waktu = substr($date, 11); //memisahkan format waktu menggunakan substring
        
        switch ($choice) {
          case 'year':
            return $tahun;
            break;
          case 'month':
            return $bulanindo[(int)$bulan-1];
            break;
          case 'date':
            return $tgl;
            break;
          case 'time':
            return $waktu;
            break;
          
          default:
            $result = $tgl . " " . $bulanindo[(int)$bulan-1] . " ". $tahun . " ". $waktu;
            return $result;
            break;
        }
}
}