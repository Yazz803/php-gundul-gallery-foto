<?php

// mengkoneksikan php dengan database
$conn = mysqli_connect("localhost", "root", "", "ujikom-gallery");
// $conn = mysqli_connect("sql308.epizy.com", "epiz_32036049", "jy0FsyCXBMG", "epiz_32036049_webtoko");

// query untuk menampilkan/mengambil data dari database
function query($query)
{ // $query ini menampung nilai dari string "SELECT * FROM mahasiswa", jadi di var $result, tinggal tulis saja var $query
    global $conn;  //untuk mengambil variabel yg ada di luar function
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data)
{
    global $conn;
    // mengambil data dari post
    $JudulFoto = $data["JudulFoto"];
    $DeskripsiFoto = $data["DeskripsiFoto"];
    $TanggalUnggah = date("Y-m-d");

    //upload gambar
    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    $default = "";
    // memasukan data dari inputan user ke dalam database table mahasiswa
    $query = "INSERT INTO foto
                VALUES 
                ('', '$JudulFoto', '$DeskripsiFoto', '$TanggalUnggah', '$gambar', '', '')
            ";

    // function untuk menambahkan data. function ini juga bisa digunakan untuk menghapus, mengedit, dll.
    // jadi hanya mengubah variabel $query didalamnya, dan di variabel tersebut, isi dengan perintah yg diinginkan(hapus, edit, dsb)
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn); // jika gagal minus -1(false) jika berhasil plus 1(true)
}

function upload()
{
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // cek apakah tidak ada gambar di upload
    if ($error === 4) { // 4 berarti tidak ada gambar yg diupload
        echo "
            <script>
                alert('Pilih Gambar terlebih dahulu');
            </script>
        ";
        return false;
    }

    // cek apakah yg diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile); // fungsi explode berfungsi untuk memecah string menjadi beberapa array
    // delimiternya (yg '.' ini) bisa diubah sesuai dengan keinginan kita
    // contoh : stringnya Yazid.jpeg, nah nanti ini akan di ubah menjadi array ['Yazid', 'jpeg']
    // Yazid.jpeg = ['Yazid', 'jpeg']
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    // jadi ini maksudnya, ketika sudah dipecah pakai explode, pilih array yg paling belakang
    // memakai fungsi end, dan ubah semua stringnya menjadi huruf kecil semua memakai strtolower
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) { // in_array berfungsi untuk mengecek apakah ada nilai(string, integer) yg ada di dalam array
        echo "
            <script>
                alert('Yang anda upload bukan gambar');
            </script>
        ";
        return false;
    }

    // cek jika ukurannya terlalu besar
    if ($ukuranFile > 5000000) {
        echo "
            <script>
                alert('Gambar harus berukuran kurang dari 5MB');
            </script>
        ";
        return false;
    }

    //lolos pengecekan gambar, gambar siap di upload
    // generate nama gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}

function hapusFoto($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM foto where FotoID = $id"); // ini akan menghapus data dari table mahasiswa yg idnya sesuai dengan yg kita inginkan
    return mysqli_affected_rows($conn);
}

function ubah($data)
{
    global $conn;

    $id = $data["id"];
    $JudulFoto = $data["JudulFoto"];
    $DeskripsiFoto = $data["DeskripsiFoto"];
    $gambarLama = $data["gambarLama"];
    // cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    // memasukan data dari inputan user ke dalam database table mahasiswa
    $query = "UPDATE foto SET 
                JudulFoto='$JudulFoto', 
                DeskripsiFoto ='$DeskripsiFoto', 
                LokasiFile='$gambar'
                WHERE FotoId=$id";

    // function untuk menambahkan data. function ini juga bisa digunakan untuk menghapus, mengedit, dll.
    // jadi hanya mengubah variabel $query didalamnya, dan di variabel tersebut, isi dengan perintah yg diinginkan(hapus, edit, dsb)
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn); // jika gagal minus -1(false) jika berhasil plus 1(true)

}

function cari($keyword)
{
    $query = "SELECT * FROM aksesoris WHERE 
                    nama LIKE '%$keyword%' OR 
                    deskripsi LIKE '%$keyword%'";
    // ini akan mencari semua data dari nama, nrp, email, dan jurusan menggunakan 'OR'
    // jadi ini akan mencari data nama yg depan dan belakangnya itu apapun, misalkan mau nyari nama Muhammad Yazid Akbar, 
    // gk perlu ditulis secara lengkap dan sama persis jika memakai ini, hanya tulis yazid saja bisa ketemu hasilnya yg 
    // ada kata 'yazid' (harus memakai LIKE '%namaparameter%' -> baru bisa jalan/berhasil)

    return query($query);  // mengembalikan hasilnya berupa array assosiative dan masukan kedalam $mahasiswa 
}

function register($data)
{
    global $conn;

    // fungsi stripslashes berguna untuk menghapus karakter spesial yaitu backslash (/)
    $username = stripslashes($data["Username"]);
    $nama = $data["NamaLengkap"];
    $email = $data['Email'];
    $alamat = $data['Alamat'];
    // fungsi dari mysqli_real_escape_string untuk memungkinkan si usernya masukin password ada tanda kutipnya, 
    // dan tanda kutipnya akan dimasukan ke database secara aman
    $password = mysqli_real_escape_string($conn, $data["Password"]);
    // $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT Username FROM user WHERE Username='$username'");
    // jika ini bernilai true, maka artinya username yg diinput oleh user sudah ada di database
    if (mysqli_fetch_assoc($result)) { // untuk mengambil data dari variabel result, pake fungsi mysqli_fetch_assoc -> jadi array associative
        echo "
            <script>
                alert('Username Sudah Terdaftar');
            </script>
        ";
        return false; // supaya insertnya gagal, supaya yg bawah ini g dijalankan, jika sudah lolos/benar, maka masuk ke yg konfirmasi password
    }

    //cek konfirmasi password
    // if ($password !== $password2) {
    //     echo "
    //         <script>
    //         alert('Konfirmasi password tidak sesuai!');
    //         </script>
    //     ";
    //     return false; // untuk memberhentikan functionnya, jadi nanti jika salah, masuk ke else di registrasi.php (nunjukin error)
    // }
    // return berguna untuk mengembalikan nilai

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);
    // jadi nanti password yg diatas akan ditimpa dengan yang ini, lalu disini passwordnya
    // akan diacak oleh fungsi password_hash menggunakan algoritma yg dipilih secara default oleh phpnya
    // algoritma ini akan terus berubah ketika ada cara pengamanan baru
    // var_dump($password); die; // digunakan untuk mengecek passworndya, sudah di enkripsi atau belum

    // tambahkan user ke database
    mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password', '$email', '$nama', '$alamat')");

    return mysqli_affected_rows($conn);
}
