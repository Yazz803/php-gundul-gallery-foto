<?php
session_start();
// jika tidak ada session loogin, maka kembalikan user ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
include("functions.php");


$id = $_GET['fotoID'];

$dataFoto = query("SELECT * FROM foto WHERE FotoID=$id")[0];
if (isset($_POST["kirim"])) {

    // var_dump($_POST); die; // -> die ini berfungsi untuk menghentikan baris code yg ada di bawahnya
    // cek apakah data sudah berhasil dikirim/ditambahkan
    if (ubah($_POST) > 0) { //variabel $_POST nantinya akan mengambil data dari user lalu dikirimkan ke variabel data yg ada di function.php
        echo "
          <script>
              alert('data berhasil diubah');
              document.location.href= 'index.php';
          </script>
      "; // jika row nya +1
    } else {
        echo "
          <script>
              alert('data gagal diubah');
              document.location.href='index.php';
          </script>
      "; // jika row nya -1
    }
}
?>


<?php include 'layouts/head.php'; ?>
<!-- Page Wrapper -->
<div id="wrapper">
    <?php include 'layouts/sidebar.php'; ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            <?php include 'layouts/navbar.php'; ?>
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div style="max-width: 500px;margin:auto">
                    <h3 class="text-center mb-4">Add Image Gallery</h3>
                    <center>
                        <img src="img/<?= $dataFoto['LokasiFile']; ?>" width="200px" alt="">
                    </center>
                    <form class="user" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $dataFoto['FotoID']; ?>">
                        <input type="hidden" name="gambarLama" id="gambarLama" value="<?= $dataFoto['LokasiFile']; ?>">
                        <div class="custom-file mb-4">
                            <input type="file" class="custom-file-input" name="gambar" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <div class="form-group">
                            <input value="<?= $dataFoto['JudulFoto']; ?>" type="text" name="JudulFoto" class="form-control form-control-user" placeholder="Enter Title Image...">
                        </div>
                        <div class="form-group">
                            <input value="<?= $dataFoto['DeskripsiFoto']; ?>" type="text" name="DeskripsiFoto" class="form-control form-control-user" placeholder="Enter Description Image...">
                        </div>
                        <button type="submit" name="kirim" class="btn btn-primary btn-user btn-block">
                            Edit
                        </button>
                        <hr>
                    </form>
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <?php include 'layouts/footer.php'; ?>

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal-->
<div class="modal fade" id="imageModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Photo</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <img class="modal-body" style="width: 100%;height: auto;display: block;" alt="crypto" src="https://source.unsplash.com/random/?Cryptocurrency&1">
            <ul>
                <li>Judul Foto : </li>
                <li>Deskripsi Foto : </li>
                <li>Tanggal Unggah : </li>
            </ul>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php include 'layouts/foot.php'; ?>