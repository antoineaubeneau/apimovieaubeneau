<?php

//Récuperer une Review de film

function getReview(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM reviews WHERE id = :id";

    $getReviewStmt = $db->prepare($sql);
    $getReviewStmt->bindParam(':id', $id);
    $getReviewStmt->execute();

    return  $getReviewStmt->fetchAll(PDO::FETCH_ASSOC);
}


//Récuperer tous les Reviews

function getReviews(): array
{

    require '../Service/Database.php';

    $sql = "SELECT * FROM reviews";
    $getReviewsStmt = $db->prepare($sql);
    $getReviewsStmt->execute();

    return $getReviewsStmt->fetchAll(PDO::FETCH_ASSOC);
}

//Ajouter/Créer une Review

function addReview($movie_id, $username, $content, $date): array
{
    require '../Service/Database.php';

    $sql = "INSERT INTO reviews (movie_id, username, content, date)  VALUES (:movie_id, :username, :content, :date)";

    $reviewStmt = $db->prepare($sql);

    $reviewStmt->execute([
        'id' => $id,
        'movie_id' => $movie_id,
        'username' => $username,
        'content' => $content,
        'date' => $date,
    ]);

    $sql = "SELECT MAX(id) FROM review";
    $getLastReviewIdStmt = $db->prepare($sql);
    $getLastReviewIdStmt->execute();

    $maxId = $getLastReviewIdStmt->fetch(PDO::FETCH_COLUMN);

    return getReview($maxId);
}

//Modifier une Review

function updateReview(int $id, $first_name, $last_name, $dob, $bio): array
{
    require '../Service/Database.php';

    $sql = "UPDATE reviews SET movie_id = :movie_id, username = :username, content = :content, date = :date WHERE id = :id";

    $getReviewStmt = $db->prepare($sql);

    $getReviewStmt->execute([
        'id' => $id,
        'movie_id' => $movie_id,
        'username' => $username,
        'content' => $content,
        'date' => $date,
    ]);

    return getReview($id);
}


// Supprimer une Review 

function deleteReview(int $id)
{
    require '../Service/Database.php';

    $sql = "DELETE FROM reviews WHERE id = :id";

    $deleteReviewStmt = $db->prepare($sql);

    $deleteReviewStmt->execute([
        'id' => $id
    ]);
}


//Récuperer tous les Reviews d'un film


function getMovieReviews(int $id): array
{

    require '../Service/Database.php';

    $sql = "SELECT reviews.id, reviews.movie_id, reviews.username, reviews.content, reviews.date FROM reviews JOIN movies ON movies.id = reviews.movie_id WHERE movies.id = :id ORDER BY reviews.date DESC";

    $getReviewStmt = $db->prepare($sql);
    $getReviewStmt->bindParam(':id', $id);
    $getReviewStmt->execute();

    return  $getReviewStmt->fetchAll(PDO::FETCH_ASSOC);
}
