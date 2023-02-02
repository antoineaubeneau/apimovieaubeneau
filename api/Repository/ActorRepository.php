<?php

//Récuperer un Acteur de film

function getActor(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM actors WHERE id = :id";

    $getActorStmt = $db->prepare($sql);
    $getActorStmt->bindParam(':id', $id);
    $getActorStmt->execute();

    return  $getActorStmt->fetchAll(PDO::FETCH_ASSOC);
}


//Récuperer tous les Acteurs

function getActors(): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM actors";
    $getActorsStmt = $db->prepare($sql);
    $getActorsStmt->execute();

    return $getActorsStmt->fetchAll(PDO::FETCH_ASSOC);
}

//Ajouter/Créer un Acteur

function addActor($first_name, $last_name, $dob, $bio): array
{
    require '../Service/Database.php';

    $sql = "INSERT INTO actors (first_name, last_name, dob, bio)  VALUES (:first_name, :last_name, :dob, :bio)";

    $actorStmt = $db->prepare($sql);

    $actorStmt->execute([
        'id' => $id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'dob' => $dob,
        'bio' => $bio,
    ]);

    $sql = "SELECT MAX(id) FROM actors";
    $getLastActorIdStmt = $db->prepare($sql);
    $getLastActorIdStmt->execute();

    $maxId = $getLastActorIdStmt->fetch(PDO::FETCH_COLUMN);

    return getActor($maxId);
}

//Modifier un Acteur

function updateActor(int $id, $first_name, $last_name, $dob, $bio): array
{
    require '../Service/Database.php';

    $sql = "UPDATE actors SET first_name = :first_name, last_name = :last_name, dob = :dob, bio = :bio WHERE id = :id";

    $getActorStmt = $db->prepare($sql);

    $getActorStmt->execute([
        'id' => $id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'dob' => $dob,
        'bio' => $bio,
    ]);

    return getActor($id);
}


// Supprimer un Acteur 

function deleteActor(int $id)
{
    require '../Service/Database.php';

    $sql = "DELETE FROM actors WHERE id = :id";

    $deleteActorStmt = $db->prepare($sql);

    $deleteActorStmt->execute([
        'id' => $id
    ]);
}


//Récuperer tous les Acteurs d'un film


function getMovieActors(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT actors.id,  FROM movies INNER JOIN movie_actors ON movies.id = movie_actors.movie_id INNER JOIN actors ON actors.id = movie_actors.actor_id WHERE movies.id = :id";

    $getActorStmt = $db->prepare($sql);
    $getActorStmt->bindParam(':id', $id);
    $getActorStmt->execute();

    return  $getActorStmt->fetchAll(PDO::FETCH_ASSOC);
}
