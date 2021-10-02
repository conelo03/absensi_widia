<?php
date_default_timezone_set('Asia/Jakarta');
$db = new mysqli("localhost","root","","db_absensi_widia");

// Check connection
if ($db -> connect_errno) {
  echo "Failed to connect to MySQL: " . $db -> connect_error;
  exit();
}

$tanggal = date('Y-m-d');
$days = date('D', strtotime($tanggal));

if ($days == 'Sat' || $days == 'Sun' ) {
	die();
} else {
	$data = mysqli_query($db,"SELECT * from users WHERE level='Karyawan' ");
	while($d = mysqli_fetch_array($data)){
		$id_user = $d['id_user'];
		$cek = mysqli_query($db,"SELECT * from rekap_absensi WHERE id_user='$id_user' and tgl='$tanggal' ");
		$cek_absen_hari_ini = mysqli_num_rows($cek);
		if($cek_absen_hari_ini == 0){
			mysqli_query($db,"INSERT into rekap_absensi(id_user, tgl, keterangan) values('$id_user','$tanggal','Alpha')");
		}
	}
}

?>