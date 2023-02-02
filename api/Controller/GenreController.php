<?php
ini_set('display_errors', 1);
header('Content-Type: application/json');

require '../Service/Database.php';
require '../Repository/GenreRepository.php';
require '../Repository/MovieRepository.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$parent = $_GET['parent'] ?? null;

switch ($requestMethod) {
    case "GET":
        if ($id) {
            if (!$parent) {
                $genre = getGenre($id);

                if ($genre) {
                    http_response_code(200);
                    echo json_encode($genre);
                } else {
                    $error = ['code' => 404, 'message' => "Le genre avec l'id $id n'existe pas."];
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
                    $genre = getMovieGenres($id);

                    http_response_code(200);
                    echo json_encode($genres);
                }
            }
        } else {
            $genres = getGenres();

            http_response_code(200);
            echo json_encode($genres);
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
            // Valider les données avant d'insérer
            $genre = addGenre($id, $data->name);
            http_response_code(201);
            echo json_encode($genre);
        }

        break;

    case "PUT":
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->name)) {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner tous les champs"];
            echo json_encode($error);
        } else {
            if ($id) {
                // Vérifier que l'id existe
                $genre = getGenre($id);

                if (!$genre) {
                    $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                    http_response_code(404);
                    echo json_encode($error);
                    return;
                }

                $name = filter_var($data->name, FILTER_UNSAFE_RAW);

                // Valider les données avant d'insérer

                $genre = updateGenre($id, $data->name);
                http_response_code(200);
                echo json_encode($genre);
            } else {
                http_response_code(400);
                $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à mettre à jour."];
                echo json_encode($error);
            }
        }

        break;

    case 'DELETE':
        if ($id) {
            $genre = getGenre($id);

            if (!$genre) {
                $error = ['code' => 404, 'message' => "Le film avec l'id $id n'existe pas."];
                http_response_code(404);
                echo json_encode($error);
                return;
            }

            deleteGenre($id);
            http_response_code(204);
        } else {
            http_response_code(400);
            $error = ["code" => 400, "message" => "Veuillez renseigner l'id du film à supprimer."];
            echo json_encode($error);
        }
        break;
}
