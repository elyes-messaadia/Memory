<?php
require_once 'Player.php';

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=memory_game", "root", "");

// Récupération des données envoyées en JSON
$data = json_decode(file_get_contents("php://input"), true);

// Enregistre le score
$playerId = 1; // ID du joueur actuel, remplace par une logique pour récupérer l'ID dynamique
$score = $data['score'];

$stmt = $pdo->prepare("INSERT INTO scores (player_id, score) VALUES (?, ?)");
$stmt->execute([$playerId, $score]);

echo json_encode(['status' => 'success']);
