<?php
ini_set('display_errors', 1);
header('Content-Type: application/json');

require '../Service/Database.php';
require '../Repository/ReviewRepository.php';
require '../Repository/MovieRepository.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$parent = $_GET['parent'] ?? null;

switch ($requestMethod) {
    case "GET":
        if ($id) {
            if (!$parent) {
                $review = getReview($id);

                if ($review) {
                    http_response_code(200);
                    echo json_encode($review);
                } else {
                    $error = ['code' => 404, 'message' => "La review de film avec l'id $id n'existe pas."];
                    http_response_code(404);
                    echo json_encode($error);
                }
            } else {
                $movie = getMovie($id);

                if (empty($movie)) {
                    $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                    http_response_code(404);
                    echo json_encode($error);
                } else {
                    $review = getMovieReviews($id);

                    http_response_code(200);
                    echo json_encode($reviews);
                }
            }
        } else {
            $reviews = getReviews();

            http_response_code(200);
            echo json_encode($reviews);
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->name)) {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner tous les champs"];
            echo json_encode($error);
        } else {
            $name = filter_var($data->name, FILTER_UNSAFE_RAW);
            $username = filter_var($data->name, FILTER_UNSAFE_RAW);
            $content = filter_var($data->bio, FILTER_UNSAFE_RAW);
            $date = filter_var($data->dob, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2}$/")));
            // Valider les données avant d'insérer
            $review = addGenre($id, $data->name, $data->movie_id, $data->username, $data->content, $data->date);
            http_response_code(201);
            echo json_encode($review);
        }

        break;

    case "PUT":
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->name, $data->movie_id, $data->username, $data->content, $data->date)) {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner tous les champs"];
            echo json_encode($error);
        } else {
            if ($id) {
                // Vérifier que l'id existe
                $review = getReview($id);

                if (!$review) {
                    $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                    http_response_code(404);
                    echo json_encode($error);
                    return;
                }
                $name = filter_var($data->name, FILTER_UNSAFE_RAW);
                $username = filter_var($data->name, FILTER_UNSAFE_RAW);
                $content = filter_var($data->bio, FILTER_UNSAFE_RAW);
                $date = filter_var($data->dob, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2}$/")));

                // Valider les données avant d'insérer

                $review = updateReview($id, $data->name, $data->movie_id, $data->username, $data->content, $data->date);
                http_response_code(200);
                echo json_encode($review);
            } else {
                http_response_code(400);
                $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à mettre à jour."];
                echo json_encode($error);
            }
        }

        break;

    case 'DELETE':
        if ($id) {
            $review = getReview($id);

            if (!$review) {
                $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                http_response_code(404);
                echo json_encode($error);
                return;
            }

            deleteReview($id);
            http_response_code(204);
        } else {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à supprimer."];
            echo json_encode($error);
        }
        break;
}
