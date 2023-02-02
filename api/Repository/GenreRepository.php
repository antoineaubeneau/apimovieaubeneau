<?php

//Récuperer un Genre de film

function getGenre(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM genres WHERE id = :id";

    $getGenreStmt = $db->prepare($sql);
    $getGenreStmt->bindParam(':id', $id);
    $getGenreStmt->execute();

    return $getGenreStmt->fetchAll(PDO::FETCH_ASSOC);
}


//Récuperer tous les Genres

function getGenres(): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM genres";
    $getGenresStmt = $db->prepare($sql);
    $getGenresStmt->execute();

    return $getGenresStmt->fetchAll(PDO::FETCH_ASSOC);
}

//Ajouter/Créer un Genre

function addGenre($name): array
{
    require '../Service/Database.php';

    $sql = "INSERT INTO genres (name)  VALUES (:name)";

    $genreStmt = $db->prepare($sql);

    $genreStmt->execute([
        'id' => $id,
        'name' => $name,
    ]);

    $sql = "SELECT MAX(id) FROM genres";
    $getLastGenreIdStmt = $db->prepare($sql);
    $getLastGenreIdStmt->execute();

    $maxId = $getLastGenreIdStmt->fetch(PDO::FETCH_COLUMN);

    return getGenre($maxId);
}

//Modifier un Genre

function updateGenre(int $id, $name): array
{
    require '../Service/Database.php';

    $sql = "UPDATE genres SET first_name = :first_name, last_name = :last_name, dob = :dob, bio = :bio WHERE id = :id";

    $getDirectorStmt = $db->prepare($sql);

    $getDirectorStmt->execute([
        'id' => $id,
        'name' => $name,
    ]);

    return getDirector($id);
}


// Supprimer un Genre 

function deleteGenre(int $id)
{
    require '../Service/Database.php';

    $sql = "DELETE FROM genres WHERE id = :id";

    $deleteGenreStmt = $db->prepare($sql);

    $deleteGenreStmt->execute([
        'id' => $id
    ]);
}


//Récuperer tous les Genres d'un film


function getMovieGenres(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT genres.id,  FROM movies INNER JOIN movie_genres ON movies.id = movie_genres.movie_id INNER JOIN genres ON genres.id = movie_genres.genres WHERE movies.id = :id";

    $getGenreStmt = $db->prepare($sql);
    $getGenreStmt->bindParam(':id', $id);
    $getGenreStmt->execute();

    return  $getGenreStmt->fetchAll(PDO::FETCH_ASSOC);
}
