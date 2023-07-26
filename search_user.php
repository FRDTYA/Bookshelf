<?php

include('koneksi.php');

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $select_users = $conn->prepare("SELECT name FROM `tb_user` WHERE name LIKE ?");
    $select_users->bind_param("s", $search_query);
    $search_query = '%' . $search . '%';
    $select_users->execute();
    $result_users = $select_users->get_result();
    $users = array();
    while ($fetch_users = $result_users->fetch_assoc()) {
        array_push($users, $fetch_users);
    }
    echo json_encode($users);
}

?>