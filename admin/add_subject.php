<?php
    session_start();
    global $error;
    if(!isset($_SESSION['admin']))
    {
        $error = "Та нэвтэрч орно уу!";
        header("Location: login.php");
    }
    else
    {
        if(isset($_POST['lesson_id']) && isset($_POST['subject_name']) && isset($_POST['subject_description']))
        {
            include "../db.php";
			if($stmt = $mysqli->prepare("INSERT INTO subject(lesson_id, name, description) values(?, ?, ?)"))
            {
                $stmt->bind_param("iss",$_POST['lesson_id'], $_POST['subject_name'], $_POST['subject_description']);
                $stmt->execute();
                $notice = $stmt->affected_rows . " сэдэв нэмэгдлээ.";
                $stmt->close();
            }
            $mysqli->close();
        }
    }
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <?php
            if($notice != null)
                echo $notice . "<br />";
        ?>
        <form action="add_subject.php" method="post" name="add_grade">
            Хичээл:
            <select name="lesson_id">
            <?php
				include "../db.php";
				//Энэ жаахан онцгүй шийдэл. Гэхдээ дараа нь ajax ашиглаад сайжруулж болно.
				//Юу вэ гэхээр сонгох өмхөн холбоос баазыг шатлан шүүх биш харин шууд нэг дор нэг сонгох хэсэгт цугт нь рэндэрлээд id-г нь авна.
                $query = "SELECT lesson.id, grade.description, lesson.name FROM lesson INNER JOIN grade ON grade.id = lesson.grade_id ORDER BY lesson.id";
                if($result = $mysqli->prepare($query))
                {
                    $result->execute();
                    $result->bind_result($lesson_id, $grade_description, $lesson_name);
                    while($result->fetch())
                    {
                        printf("<option value=\"%d\">%s %s</option>", $lesson_id, $grade_description, $lesson_name);
                    }
                    $result->close();
                }
                $mysqli->close();

            ?>
            </select><br />
            Сэдвийн нэр:
            <input type="text" name="subject_name" /><br />
            Сэдвийн тайлбар:
            <input type="text" name="subject_description" /><br />
            <input type="submit" value="Оруулах" />
        </form>
        <a href="index.php">Буцах</a> | <a href="../index.php">Нүүр</a>
    </body>
</html>
