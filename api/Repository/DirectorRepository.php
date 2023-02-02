<?php

//Récuperer un Directeur de film

function getDirector(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM directors WHERE id = :id";

    $getDirectorStmt = $db->prepare($sql);
    $getDirectorStmt->bindParam(':id', $id);
    $getDirectorStmt->execute();

    return  $getDirectorStmt->fetchAll(PDO::FETCH_ASSOC);
}


//Récuperer tous les Directeurs

function getDirectors(): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM directors";
    $getDirectorsStmt = $db->prepare($sql);
    $getDirectorsStmt->execute();

    return $getDirectorsStmt->fetchAll(PDO::FETCH_ASSOC);
}

//Ajouter/Créer un Directeur

function addDirector($first_name, $last_name, $dob, $bio): array
{
    require '../Service/Database.php';

    $sql = "INSERT INTO directors (first_name, last_name, dob, bio)  VALUES (:first_name, :last_name, :dob, :bio)";

    $directorStmt = $db->prepare($sql);

    $directorStmt->execute([
        'id' => $id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'dob' => $dob,
        'bio' => $bio,
    ]);

    $sql = "SELECT MAX(id) FROM directors";
    $getLastDirectorIdStmt = $db->prepare($sql);
    $getLastDirectorIdStmt->execute();

    $maxId = $getLastDirectorIdStmt->fetch(PDO::FETCH_COLUMN);

    return getDirector($maxId);
}

//Modifier un Directeur

function updateDirector(int $id, $first_name, $last_name, $dob, $bio): array
{
    require '../Service/Database.php';

    $sql = "UPDATE directors SET first_name = :first_name, last_name = :last_name, dob = :dob, bio = :bio WHERE id = :id";

    $getDirectorStmt = $db->prepare($sql);

    $getDirectorStmt->execute([
        'id' => $id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'dob' => $dob,
        'bio' => $bio,
    ]);

    return getDirector($id);
}


// Supprimer un Directeur 

function deleteDirector(int $id)
{
    require '../Service/Database.php';

    $sql = "DELETE FROM directors WHERE id = :id";

    $deleteDirectorStmt = $db->prepare($sql);

    $deleteDirectorStmt->execute([
        'id' => $id
    ]);
}


//Récuperer tous les Directeurs d'un film


function getMovieDirectors(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT directors.id,  FROM movies INNER JOIN movie_directors ON movies.id = movie_directors.movie_id INNER JOIN directors ON directors.id = movie_directors.directors WHERE movies.id = :id";

    $getDirectorStmt = $db->prepare($sql);
    $getDirectorStmt->bindParam(':id', $id);
    $getDirectorStmt->execute();

    return  $getDirectorStmt->fetchAll(PDO::FETCH_ASSOC);
}
