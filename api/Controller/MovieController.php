<?php
ini_set('display_errors', 1);
header('Content-Type: application/json');

require '../Service/Database.php';
require '../Repository/MovieRepository.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($requestMethod) {
    case "GET":
        if ($id) {
            $movie = getMovie($id);

            if ($movie) {
                http_response_code(200);
                echo json_encode($movie);
            } else {
                $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                http_response_code(404);
                echo json_encode($error);
            }
        } else {
            $movies = getMovies();

            http_response_code(200);
            echo json_encode($movies);
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->title, $data->release_date, $data->plot, $data->runtime)) {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner tous les champs"];
            echo json_encode($error);
        } else {
            $title = filter_var($data->title, FILTER_UNSAFE_RAW);
            $release_date = filter_var($data->runtime, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2}$/")));
            $plot = filter_var($data->plot, FILTER_UNSAFE_RAW);
            $runtime = filter_var($data->runtime, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2}$/")));
            // Valider les données avant d'insérer
            $movie = addMovie($data->title, $data->release_date, $data->plot, $data->runtime);
            http_response_code(201);
            echo json_encode($movie);
        }

        break;

    case "PUT":
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->title, $data->release_date, $data->plot, $data->runtime)) {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner tous les champs"];
            echo json_encode($error);
        } else {
            if ($id) {
                // Vérifier que l'id existe
                $movie = getMovie($id);

                if (!$movie) {
                    $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                    http_response_code(404);
                    echo json_encode($error);
                    return;
                }

                // Valider les données avant d'insérer

                $movie = updateMovie($id, $data->title, $data->release_date, $data->plot, $data->runtime);
                http_response_code(200);
                echo json_encode($movie);
            } else {
                http_response_code(400);
                $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à mettre à jour."];
                echo json_encode($error);
            }
        }

        break;

    case 'DELETE':
        if ($id) {
            $movie = getMovie($id);

            if (!$movie) {
                $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                http_response_code(404);
                echo json_encode($error);
                return;
            }

            deleteMovie($id);
            http_response_code(204);
        } else {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à supprimer."];
            echo json_encode($error);
        }
        break;
}
