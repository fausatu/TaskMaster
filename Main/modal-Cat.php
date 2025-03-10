
  
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="../styles/modal-cat.css">
     <link rel="stylesheet" href="../styles/main.css">
</head>
<body>

   <!-- Modal d'ajout de catégorie -->
    <div class="add-category">
        <i class="fas fa-plus"></i>
        <span>Ajouter une liste</span>
    </div>
    <div id="add-category-modal" class="modal-cat">

        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter une nouvelle liste</h2>
            <form action="../Back/ajouter_categorie.php" method="post">
                <div class="form-group">
                    <label for="nom-categorie">Nom</label>
                    <input type="text" id="nom-categorie" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="icone-categorie">Icône</label>
                    <select id="icone-categorie" name="icone">
                        <option value="fas fa-list">📝 Liste</option>
                        <option value="fas fa-home">🏠 Maison</option>
                        <option value="fas fa-briefcase">💼 Travail</option>
                        <option value="fas fa-book">📚 Études</option>
                        <option value="fas fa-shopping-cart">🛒 Courses</option>
                        <option value="fas fa-plane">✈️ Voyage</option>
                        <option value="fas fa-heart">❤️ Personnel</option>
                        <option value="fas fa-star">⭐ Important</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="couleur-categorie">Couleur</label>
                    <input type="color" id="couleur-categorie" name="couleur" value="#4a6cfa">
                </div>
                <button type="submit" class="btn-primary">Ajouter</button>
            </form>
        </div>
    </div>
</body>
</html>
