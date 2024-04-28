<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Genre.php');
include('classes/Template.php');

$genre = new Genre($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$genre->open();
$genre->getGenre();


if (!isset($_GET['id'])) {
    if (isset($_POST['submit'])) {
        if ($genre->addGenre($_POST) >= 0) {
            echo "<script>
                alert('Data berhasil ditambah!');
                document.location.href = 'genre.php';
            </script>";
        } else {
            echo "<script>
                alert('Data gagal ditambah!');
                document.location.href = 'genre.php';
            </script>";
        }
    }

    $btn = 'Tambah';
    $title = 'Tambah';
}

$view = new Template('templates/skintabel.html');

$mainTitle = 'Genre';
$header = '<tr class="text-center">
<th class="">No.</th>
<th class="">Name Genre</th>
<th class="">Aksi</th>
</tr>';
$data = null;
$no = 1;
$formLabel = 'genre';

while ($div = $genre->getResult()) {
    $colorChange = ($no % 2 == 0) ? "bg-gray-200": "bg-white"; 
    $data .= '<tr class="text-center '.$colorChange.' h-[80px]">
    <th scope="row">' . $no . '</th>
    <td>' . $div['name_genre'] . '</td>
    <td>
        <a class="p-2 bg-yellow-500" href="genre.php?id=' . $div['id_genre'] . '" title="Edit Data">Edit</a>
        <a class="p-2 bg-red-700 text-white" href="genre.php?hapus=' . $div['id_genre'] . '" title="Delete Data">Delete</a>
        </td>
    </tr>';
    $no++;
}

$html_form_add = '
            <form action="genre.php" method="POST" class="flex flex-col gap-4 w-[50%] py-2 shadow-md p-2" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Masukan Nama" class="border-md border p-2 text-sm" required />
                <input type="submit" name="submit" class="bg-green-600 p-2 rounded-md text-white text-xs" value="Add Data">
            </form>';


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($id > 0) {
        if (isset($_POST['submit'])) {
            if ($genre->updateGenre($id, $_POST) >= 0) {
                echo "<script>
                alert('Data berhasil diubah!');
                document.location.href = 'genre.php';
            </script>";
            } else {
                echo "<script>
                alert('Data gagal diubah!');
                document.location.href = 'genre.php';
            </script>";
            }
        }

        $genre->getgenreById($id);
        $row = $genre->getResult();


        $html_form_add = '
            <form action="genre.php?id='.$row['id_genre'].'" method="POST" class="flex flex-col gap-4 w-[50%] py-2 shadow-md p-2" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Masukan Nama" class="border-md border p-2 text-sm" value="'.$row['name_genre'].'" required />
                <input type="submit" name="submit" class="bg-green-600 p-2 rounded-md text-white text-xs" value="Update Data">
            </form>';

        $btn = 'Simpan';
        $title = 'Ubah';

        $view->replace('DATA_HTML_FORM', $html_form_add);
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if ($id > 0) {
        if ($genre->deleteGenre($id) >= 0) {
            echo "<script>
                alert('Data berhasil dihapus!');
                document.location.href = 'genre.php';
            </script>";
        } else {
            echo "<script>
                alert('Data gagal dihapus!');
                document.location.href = 'genre.php';
            </script>";
        }
    }
}

$genre->close();

$view->replace('DATA_MAIN_TITLE', $mainTitle);
$view->replace('DATA_TABEL_HEADER', $header);
$view->replace('DATA_TITLE', $title);
$view->replace('DATA_BUTTON', $btn);
$view->replace('DATA_FORM_LABEL', $formLabel);
$view->replace('DATA_TABEL', $data);
$view->replace('DATA_HTML_FORM', $html_form_add);
$view->write();
