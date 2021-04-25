<?php 

    $connect = new PDO("mysql:host=localhost;dbname=vuecrud", "root", "");
    $received_data = json_decode(file_get_contents("php://input"));

    $data = array();

    if ($received_data->action == "fetchall") { // แสดงข้อมูลทั้งหมด
        $query = "SELECT * FROM tbl_users";
        $statement = $connect->prepare($query);
        $statement->execute();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        echo json_encode($data);
    }

    if ($received_data->action == "insert") { // เพิ่มข้อมูล
        $data = array(
            ":first_name" => $received_data->firstName,
            ":last_name" => $received_data->lastName,
            ":email" => $received_data->email,
        );

        $query = "INSERT INTO tbl_users(first_name, last_name, email) VALUES(:first_name, :last_name, :email)";

        $statement = $connect->prepare($query);
        $statement->execute($data);

        $output = array(
            'message' => 'Data Inserted Successfully'
        );

        echo json_encode($output);
    }

    if ($received_data->action == "fetchSingle") { // ดึงข้อมูลเดิมมาแสดง ตอนกดปุ่ม Edit
        $query = "SELECT * FROM tbl_users WHERE id = '".$received_data->id."'";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $row ) {
            $data['id'] = $row['id'];
            $data['first_name'] = $row['first_name'];
            $data['last_name'] = $row['last_name'];
            $data['email'] = $row['email'];
        }

        echo json_encode($data);
    }

    if ($received_data->action == "update") {
        $data = array(
            ":first_name" => $received_data->firstName,
            ":last_name" => $received_data->lastName,
            ":email" => $received_data->email,
            ":id" => $received_data->hiddenId,
        );

        $query = "UPDATE tbl_users SET first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id";

        $statement = $connect->prepare($query);
        $statement->execute($data);

        $output = array(
            'message' => 'Data Updated Successfully'
        );

        echo json_encode($output);
    }

    if ($received_data->action == "delete") {
        $query = "DELETE FROM tbl_users WHERE id = '".$received_data->id."'";

        $statement = $connect->prepare($query);
        $statement->execute();

        $output = array(
            'message' => 'Data Deleted Successfully'
        );

        echo json_encode($output);
    }
?>