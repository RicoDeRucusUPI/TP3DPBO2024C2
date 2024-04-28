<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Comic.php');
include('classes/Genre.php');
include('classes/Publisher.php');
include('classes/Template.php');

// buat instance pengurus
$listComic = new Comic($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

// buka koneksi
$listComic->open();
// tampilkan data pengurus
$listComic->getComicJoin();

// cari Comic
if (isset($_GET['keyword'])) {
    // methode mencari data Comic
    $listComic->searchComic("%".$_GET['keyword']."%");
} else {
    // method menampilkan data Comic
    $listComic->getComicJoin();
}

$data = null;

// ambil data comic
// gabungkan dgn tag html
// untuk di passing ke skin/template
while ($row = $listComic->getResult()) {
    $data .= '<div class="p-2 bg-gray-200 hover:bg-black hover:text-white rounded-md shadow-md">' .
        '<div class="">
        <a href="detail.php?id=' . $row['id_comic'] . '">
            <div class="">
                <img src="assets/images/' . $row['image_comic'] . '" class="h-[200px] object-cover w-full" alt="' . $row['image_comic'] . '">
            </div>
            <div class="">
                <p class="text-lg font-semibold ">' . $row['name_comic'] . '</p>
                <p class="text-xs text-blue-900">' . $row['name_genre'] . ' - '.$row['name_publisher'].'</p>
                <p class=" text-sm my-2">' . $row['description_comic'] . '</p>
            </div>
        </a>
    </div>    
    </div>';
}

// tutup koneksi
$listComic->close();

// buat instance template
$home = new Template('templates/skin.html');

// simpan data ke template
$home->replace('DATA_KOMIK', $data);
$home->write();
