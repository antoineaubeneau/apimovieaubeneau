<?php
ini_set('display_errors', 1);
header('Content-Type: application/json');

require '../Service/Database.php';
require '../Repository/ActorRepository.php';
require '../Repository/MovieRepository.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$parent = $_GET['parent'] ?? null;

switch ($requestMethod) {
    case "GET":
        if ($id) {
            if (!$parent) {
                $actor = getActor($id);

                if ($actor) {
                    http_response_code(200);
                    echo json_encode($actor);
                } else {
                    $error = ['code' => 404, 'message' => "L'acteur avec l'id $id n'existe pas."];
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
                    $actors = getMovieActors($id);

                    http_response_code(200);
                    echo json_encode($actors);
                }
            }
        } else {
            $actors = getActors();

            http_response_code(200);
            echo json_encode($actors);
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->first_name, $data->last_name, $data->dob, $data->bio)) {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner tous les champs"];
            echo json_encode($error);
        } else {
            $first_name = filter_var($data->first_name, FILTER_UNSAFE_RAW);
            $last_name = filter_var($data->last_name, FILTER_UNSAFE_RAW);
            $dob = filter_var($data->dob, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2}$/")));
            $bio = filter_var($data->bio, FILTER_UNSAFE_RAW);
            // Valider les données avant d'insérer
            $actor = addActor($data->first_name, $data->last_name, $data->dob, $data->bio);
            http_response_code(201);
            echo json_encode($actor);
        }

        break;

    case "PUT":
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->first_name, $data->last_name, $data->dob, $data->bio)) {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner tous les champs"];
            echo json_encode($error);
        } else {
            if ($id) {
                // Vérifier que l'id existe
                $actor = getActor($id);

                if (!$actor) {
                    $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                    http_response_code(404);
                    echo json_encode($error);
                    return;
                }

                $first_name = filter_var($data->first_name, FILTER_UNSAFE_RAW);
                $last_name = filter_var($data->last_name, FILTER_UNSAFE_RAW);
                $dob = filter_var($data->dob, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2}$/")));
                $bio = filter_var($data->bio, FILTER_UNSAFE_RAW);
                // Valider les données avant d'insérer

                $actor = updateActor($id, $data->first_name, $data->last_name, $data->dob, $data->bio);
                http_response_code(200);
                echo json_encode($actor);
            } else {
                http_response_code(400);
                $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à mettre à jour."];
                echo json_encode($error);
            }
        }

        break;

    case 'DELETE':
        if ($id) {
            $actor = getActor($id);

            if (!$actor) {
                $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                http_response_code(404);
                echo json_encode($error);
                return;
            }


            deleteActor($id);
            http_response_code(204);
        } else {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à supprimer."];
            echo json_encode($error);
        }
        break;
}
