// Attendre que le DOM soit chargé
$(document).ready(function() {
    // Vérifier si le cookie existe et s'il a une valeur de fidélité de 11
    if (document.cookie.indexOf("fidelite") == -1 || parseInt(getCookie("fidelite")) !== 11) {
      // Rediriger vers la page précédente
      window.history.back();
    }
  });