<?php

//Récuperer tous les films

function getMovies(): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM movies LIMIT 10";
    $getMoviesStmt = $db->prepare($sql);
    $getMoviesStmt->execute();

    return $getMoviesStmt->fetchAll(PDO::FETCH_ASSOC);
}

//Récuperer un Film

function getMovie(int $id)
{
    require '../Service/Database.php';

    $sql = "SELECT * FROM movies WHERE id = :id";

    $getMovieStmt = $db->prepare($sql);
    $getMovieStmt->execute([
        'id' => $id
    ]);

    return $getMovieStmt->fetchAll(PDO::FETCH_ASSOC);
}


//Ajouter/Créer un Film

function addMovie($title, $release_date, $plot, $runtime): array
{
    require '../Service/Database.php';

    $sql = "INSERT INTO movies (title, release_date, plot, runtime)  VALUES (:title, :release_date, :plot, :runtime)";

    $MovieStmt = $db->prepare($sql);

    $MovieStmt->execute([
        'title' => $title,
        'release_date' => $release_date,
        'plot' => $plot,
        'runtime' => $runtime,
    ]);

    $sql = "SELECT MAX(id) FROM movies";
    $getLastMovieIdStmt = $db->prepare($sql);
    $getLastMovieIdStmt->execute();

    $maxId = $getLastMovieIdStmt->fetch(PDO::FETCH_COLUMN);

    return getMovie($maxId);
}

//Modifier un Film

function updateMovie(int $id, $title, $release_date, $plot, $runtime): array
{
    require '../Service/Database.php';

    $sql = "UPDATE movies SET title = :title, release_date = :release_date, plot = :plot, runtime = :runtime WHERE id = :id";

    $getMovieStmt = $db->prepare($sql);

    $getMovieStmt->execute([
        'id' => $id,
        'title' => $title,
        'release_date' => $release_date,
        'plot' => $plot,
        'runtime' => $runtime,
    ]);

    return getMovie($id);
}


// Supprimer un Film 

function deleteMovie(int $id)
{
    require '../Service/Database.php';

    $sql = "DELETE FROM movies WHERE id = :id";

    $deleteMovieStmt = $db->prepare($sql);

    $deleteMovieStmt->execute([
        'id' => $id
    ]);
}
