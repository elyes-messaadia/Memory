<?php

class Card
{
    private $id;
    private $imagePath;

    // Constructeur pour initialiser une carte
    public function __construct($id, $imagePath)
    {
        $this->id = $id;
        $this->imagePath = $imagePath;
    }

    // Getter pour l'ID de la carte
    public function getId()
    {
        return $this->id;
    }

    // Getter pour le chemin de l'image de la carte
    public function getImagePath()
    {
        return $this->imagePath;
    }

    // Méthode statique pour récupérer toutes les cartes depuis la base de données
    public static function getAllCards($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM cards");
        $cards = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cards[] = new Card($row['id'], $row['image_path']);
        }

        return $cards;
    }
}
