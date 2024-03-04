<?php
session_start();
// jika tidak ada session loogin, maka kembalikan user ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include("functions.php");

$fotos = query("SELECT * FROM foto");
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
                <div style="display: grid;grid-template-columns: repeat(4, 1fr);gap: 1rem;">
                    <?php foreach ($fotos as $foto) : ?>
                        <img data-toggle="modal" data-target="#imageModal<?= $foto['FotoID']; ?>" style="width: 100%;height: auto;display: block;" alt="crypto" src="img/<?= $foto['LokasiFile']; ?>">
                    <?php endforeach; ?>
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
<?php foreach ($fotos as $foto) : ?>
    <div class="modal fade" id="imageModal<?= $foto['FotoID']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Photo</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <img class="modal-body" style="width: 100%;height: auto;display: block;" alt="crypto" src="img/<?= $foto['LokasiFile'] ?>">
                <ul>
                    <li>Judul Foto : <?= $foto['JudulFoto'] ?> </li>
                    <li>Deskripsi Foto : <?= $foto['DeskripsiFoto'] ?> </li>
                    <li>Tanggal Unggah : <?= $foto['TanggalUnggah'] ?> </li>
                </ul>
                <div class="modal-footer">
                    <a href="editImage.php?fotoID=<?= $foto['FotoID']; ?>" class="btn btn-primary" type="button">Edit Image</a>
                    <a href="deletePhoto.php?fotoID=<?= $foto['FotoID']; ?>" class="btn btn-danger" type="button">Delete Image</a>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php include 'layouts/foot.php'; ?>