let flippedCards = [];
let matchedPairs = 0;
const totalPairs = document.querySelectorAll(".card").length / 2;

function flipCard(cardElement, cardId) {
  cardElement.classList.add("flipped");
  flippedCards.push({ element: cardElement, id: cardId });

  if (flippedCards.length === 2) {
    if (flippedCards[0].id === flippedCards[1].id) {
      matchedPairs++;
      flippedCards = [];

      // Vérifie si toutes les paires ont été trouvées
      if (matchedPairs === totalPairs) {
        setTimeout(() => {
          alert("Félicitations ! Vous avez terminé le jeu.");
          // Envoie du score au serveur
          saveScore(matchedPairs * 10); // Multiplie le score par 10, par exemple
        }, 500);
      }
    } else {
      setTimeout(() => {
        flippedCards[0].element.classList.remove("flipped");
        flippedCards[1].element.classList.remove("flipped");
        flippedCards = [];
      }, 1000);
    }
  }
}

// Fonction pour envoyer le score au serveur
function saveScore(score) {
  fetch("save_score.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ score: score }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Score enregistré avec succès :", data); // Confirmation dans la console
    })
    .catch((error) => {
      console.error("Erreur lors de l'enregistrement du score :", error);
    });
}
