

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website <?php ?></title>
</head>
<body>




<?php

    $text = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam odio corrupti aut sapiente maxime esse dolorum eos maiores veritatis asperiores? Rerum voluptatum quis veritatis. Quam molestias vero facere libero minima, nostrum vitae accusamus eum delectus reprehenderit veniam, ea sapiente cum quis non eius, consequatur odio at inventore veritatis perferendis neque.
Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam odio corrupti aut sapiente maxime esse dolorum eos maiores veritatis asperiores? Rerum voluptatum quis veritatis. Quam molestias vero facere libero minima, nostrum vitae accusamus eum delectus reprehenderit veniam, ea sapiente cum quis non eius, consequatur odio at inventore veritatis perferendis neque.
Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam odio corrupti aut sapiente maxime esse dolorum eos maiores veritatis asperiores? Rerum voluptatum quis veritatis. Quam molestias vero facere libero minima, nostrum vitae accusamus eum delectus reprehenderit veniam, ea sapiente cum quis non eius, consequatur odio at inventore veritatis perferendis neque.";

    // $splitted = explode('.', $text);

    // $joined = implode('...', $splitted);

    // var_dump($joined);

    // foreach($splitted as $text) {

    //     echo "<p>{$text}</p>";
    // }

    // var_dump($splitted);

    $students = [
    [
        "id"      => 1,
        "name"    => "John Doe",
        "courses" => [
            [
                "course_id"   => "CS101",
                "course_name" => "Computer Science",
                "grade"       => "A",
            ],
            [
                "course_id"   => "MATH201",
                "course_name" => "Mathematics",
                "grade"       => "B+",
            ],
        ],
    ],
    [
        "id"      => 2,
        "name"    => "Jane Smith",
        "courses" => [
            [
                "course_id"   => "ENG101",
                "course_name" => "English Literature",
                "grade"       => "A-",
            ],
            [
                "course_id"   => "PHY101",
                "course_name" => "Physics",
                "grade"       => "B",
            ],
        ],
    ],
    ];

?>



<?php foreach ($students as $student): ?>
    <h2><?php echo $student['name'] ?></h2>

    <?php foreach ($student['courses'] as $courses): ?>
        <p><?php echo $courses['course_name'] ?></p>
    <?php endforeach?>
    <?php endforeach?>

    <!-- <table border="">

    <tr>
        <th>Name</th>
    </tr>
    <tr>
        <th>Course</th>
    </tr>

    </table> -->






</body>
</html>
