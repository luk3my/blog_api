<?php

    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Post.php';

    //Instantiate DB and connect

    $database = new Database();
    $db = $database->connect();

    //Instantiate blog post object

    $post = new Post($db);

    //Blog Post Query
    $result = $post->read();
    //Get Row Count
    $num = $result->rowCount();

    //Check if any posts
    if($num > 0) {
        //Initialise post array
        $posts_arr = array();
        $posts_arr['data'] = array();

        //while loop
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $post_item = array(
                'id' => $id,
                'title' => $title,
                'body' => html_entity_decode($body),
                'author' => $author,
                'category_id' => $category_id,
                'category_name' => $category_name
            );

            //Push to 'data'
            array_push($posts_arr['data'], $post_item);
        }

        // Convert to JSON and Output
        echo json_encode($posts_arr);
    //If num is 0 - no posts
    } else {
        echo json_encode(
            array('message' => 'No Posts Found')
        );
    }
