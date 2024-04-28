<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Comic.php');
include('classes/Publisher.php');
include('classes/Genre.php');
include('classes/Template.php');
include('classes/Upload.php');

$comic = new Comic($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$publisher = new Publisher($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$genre = new Genre($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$comic->open();
$publisher->open();
$genre->open();
$publisher->getPublisher();
$genre->getGenre();
$comic->getComic();

$upload = new Upload("./assets/images/");

if (!isset($_GET['id'])) {
    if (isset($_POST['submit'])) {
        $name_image = time() .basename($_FILES['image_upload']["name"]);
        if($upload->start($_POST, $_FILES['image_upload'], $name_image)){
            if ($comic->addData($_POST, $name_image) >= 0) {
                echo "<script>
                    alert('Data berhasil ditambah!');
                    document.location.href = 'comic.php';
                </script>";
            } else {
                echo "<script>
                    alert('Data gagal ditambah!');
                    document.location.href = 'comic.php';
                </script>";
            }
        }else{
            echo "<script>
                alert('Gagal Upload Gambar');
                document.location.href = 'comic.php';
            </script>";
        }
    }

    $btn = 'Tambah';
    $title = 'Tambah';
}

$view = new Template('templates/skintabel.html');

$mainTitle = 'Comic';
$header = '<tr class="text-center">
<th class="">No.</th>
<th class="">Cover</th>
<th class="">Name Comic</th>
<th class="w-[40%]">Description Comic</th>
<th class="">ID Genre</th>
<th class="">ID Publisher</th>
<th class="">Aksi</th>
</tr>';
$data = null;
$no = 1;
$formLabel = 'comic';

while ($div = $comic->getResult()) {
    $colorChange = ($no % 2 == 0) ? "bg-gray-200": "bg-white"; 
    $data .= '<tr class="text-center '.$colorChange.' h-[80px]">
    <th scope="row">' . $no . '</th>
    <td><img src="./assets/images/' . $div['image_comic'] . '" class="w-full h-[50px] object-cover"/></td>
    <td>' . $div['name_comic'] . '</td>
    <td>' . $div['description_comic'] . '</td>
    <td>' . $div['id_genre'] . '</td>
    <td>' . $div['id_publisher'] . '</td>
    <td>
        <a class="p-2 bg-yellow-500" href="comic.php?id=' . $div['id_comic'] . '" title="Edit Data">Edit</a>
        <a class="p-2 bg-red-700 text-white" href="comic.php?hapus=' . $div['id_comic'] . '" title="Delete Data">Delete</a>
        </td>
    </tr>';
    $no++;
}

$html_form_add = '
            <form action="comic.php" method="POST" class="flex flex-col gap-4 w-[50%] py-2 shadow-md p-2" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Masukan Nama" class="border-md border p-2 text-sm" required />
                <textarea type="text" name="description" placeholder="Masukan Deskripsi" class="border-md border p-2 text-sm" required></textarea>
                <select class="text-sm border-md border p-2 text-gray-500" name="id_genre" required>
                    <option value="">Pilih Genre</option>
                    '.createHtmlOption($genre, ["id_genre","name_genre"]).'
                </select>
                <select class="text-sm border-md border p-2 text-gray-500" name="id_publisher" required>
                    <option value="">Pilih Publisher</option>
                    '.createHtmlOption($publisher, ["id_publisher","name_publisher"]).'
                </select>
                <div class="text-sm">
                    <span>Cover Comic : </span>
                    <input type="file" name="image_upload" class="text-xs mx-auto" required/>
                </div>
                <input type="submit" name="submit" class="bg-green-600 p-2 rounded-md text-white text-xs" value="Add Data">
            </form>';


function selectedOption($value, $variabel)
{
    $result = ($variabel == $value) ? "selected" : "" ;

    return $result;
}


function createHtmlOption($data, $name_array){
    $html = "";
    while ($value = $data->getResult()) {
        $name = $value[$name_array[1]];
        $html .= "<option value=".$value[$name_array[0]].">".$name."</option>";
    }
    return $html;
}

function createHtmlOptionWithSelected($data, $name_array, $row){
    $html = ""; 
    while ($value = $data->getResult()) {
        $name = $value[$name_array[1]];
        $html .= "<option ".selectedOption($value[$name_array[0]], $row[$name_array[0]])." value=".$value[$name_array[0]].">".$name."</option>";
    }
    return $html;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($id > 0) {
        if (isset($_POST['submit'])) {
            $name_image = null;
            if($_FILES['image_upload']["name"] != null){
                $name_image = time() .basename($_FILES['image_upload']["name"]);
                $upload->start($_POST, $_FILES['image_upload'], $name_image);
            }

            if ($comic->updateData($id, $_POST, $name_image) >= 0) {
                echo "<script>
                alert('Data berhasil diubah!');
                document.location.href = 'comic.php';
            </script>";
            } else {
                echo "<script>
                alert('Data gagal diubah!');
                document.location.href = 'comic.php';
            </script>";
            }
        }

        $comic->getComicById($id);
        $publisher->getPublisher();
        $genre->getGenre();
        $row = $comic->getResult();


        $html_form_add = '
            <form action="comic.php?id='.$row['id_comic'].'" method="POST" class="flex flex-col gap-4 w-[50%] py-2 shadow-md p-2" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Masukan Nama" class="border-md border p-2 text-sm" value="'.$row['name_comic'].'" required />
                <textarea type="text" name="description" placeholder="Masukan Deskripsi" class="border-md border p-2 text-sm" required>'.$row['description_comic'].'</textarea>
                <select class="text-sm border-md border p-2 text-gray-500" name="id_genre" required>
                    <option value="">Pilih Genre</option>
                    '.createHtmlOptionWithSelected($genre, ["id_genre","name_genre"], $row).'
                </select>
                <select class="text-sm border-md border p-2 text-gray-500" name="id_publisher" required>
                    <option value="">Pilih Publisher</option>
                    '.createHtmlOptionWithSelected($publisher, ["id_publisher","name_publisher"], $row).'
                </select>
                <div class="text-sm">
                    <span>Cover Comic : </span>
                    <input type="file" name="image_upload" class="text-xs mx-auto" />
                </div>
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
        if ($comic->deleteData($id) >= 0) {
            echo "<script>
                alert('Data berhasil dihapus!');
                document.location.href = 'comic.php';
            </script>";
        } else {
            echo "<script>
                alert('Data gagal dihapus!');
                document.location.href = 'comic.php';
            </script>";
        }
    }
}

$comic->close();

$view->replace('DATA_MAIN_TITLE', $mainTitle);
$view->replace('DATA_TABEL_HEADER', $header);
$view->replace('DATA_TITLE', $title);
$view->replace('DATA_BUTTON', $btn);
$view->replace('DATA_FORM_LABEL', $formLabel);
$view->replace('DATA_TABEL', $data);
$view->replace('DATA_HTML_FORM', $html_form_add);
$view->write();
