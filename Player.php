<?php

class Player
{
    private $pdo;
    public $id;
    public $username;
    public $email;

    public function __construct($pdo, $id = null, $username = null, $email = null)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    public function save()
    {
        $stmt = $this->pdo->prepare("INSERT INTO players (username, email) VALUES (?, ?)");
        $stmt->execute([$this->username, $this->email]);
        $this->id = $this->pdo->lastInsertId();
    }

    // Méthode pour récupérer un joueur par son ID
    public static function getById($pdo, $playerId)
    {
        $stmt = $pdo->prepare("SELECT * FROM players WHERE id = ?");
        $stmt->execute([$playerId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Player($pdo, $data['id'], $data['username'], $data['email']);
        }
        return null;
    }

    // Méthode pour obtenir l'historique des scores du joueur
    public function getScores()
    {
        $stmt = $this->pdo->prepare("SELECT score, date_played FROM scores WHERE player_id = ? ORDER BY date_played DESC");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode statique pour récupérer les meilleurs joueurs (classement)
    public static function getTopPlayers($pdo, $limit = 10)
    {
        $stmt = $pdo->prepare("SELECT p.username, s.score FROM players p JOIN scores s ON p.id = s.player_id ORDER BY s.score DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
