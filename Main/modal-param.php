  <?php  
  include_once '../Back/bd.php';
    session_start();

  ?>
  
  <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/modal-param.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>
    
    <div id="settings-modal" class="modal">
        <div class="modal-content settings-modal-content">
            <div class="myclosebtn"><span class="close">&times;</span>
        </div>
            
            
       
            <div class="profile-header">
            <div class="profile-info">
    <?php
    // Récupérer le chemin de l'image de profil depuis la base de données
    $profileImage =  $_SESSION['profil']  ?? 'profile-placeholder.jpg';
    ?>
    <img src="<?php echo $profileImage; ?>" alt="Photo de profil" class="profile-image">
    <div class="profile-details">
        <h3><?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></h3>
        <p><?php echo htmlspecialchars($_SESSION['login']); ?></p>
    </div>
</div>
                <div class="profile-actions">
                <form id="logout-form" action="../Back/deco.php" method="POST">
                        <button type="submit" class="btn-secondary" id="deco-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Déconnexion
                        </button>
                    </form>
      
<form id="change-photo-form" action="../Back/upload_profile_picture.php" method="POST" enctype="multipart/form-data" class="form" >
    <label for="profile-picture" class="btn-primary" id="change-photo-btn">
        <i class="fas fa-camera"></i>
        Changer la photo
    </label>
    <input type="file" id="profile-picture" name="profile_picture" accept="image/*" style="display: none;">
    <button type="submit" id="submit-btn" class="btn" >Envoyer</button>
</form>
                </div>
            </div>

          
            <div class="settings-section">
                <h3>Synchronisation</h3>
                <div class="settings-item">
                    <div class="settings-item-info">
                        <i class="fas fa-sync-alt"></i>
                        <div>
                            <h4>Synchronisation des données</h4>
                            <p>Synchroniser avec vos appareils</p>
                        </div>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="sync-toggle" checked>
                        <label for="sync-toggle"></label>
                    </div>
                </div>
                <div class="settings-item">
                    <div class="settings-item-info">
                        <i class="fas fa-cloud"></i>
                        <div>
                            <h4>Sauvegarde</h4>
                            <p>Sauvegarder vos données</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>

<!--session réseaux sociaux  -->
            <div class="settings-section">
                <h3>Nous suivre</h3>
                <div class="social-links">
                    <a href="https://fr-fr.facebook.com/" class="social-link">
                        <i class="fab fa-facebook"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="https://x.com/" class="social-link">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="https://www.instagram.com/" class="social-link">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </a>
                    <a href="https://www.linkedin.com/in/fausat-akintoyess%C3%A9-849641278/" class="social-link">
                        <i class="fab fa-linkedin"></i>
                        <span>LinkedIn</span>
                    </a>
                </div>
            </div>

            <!-- Section Mentions Légales  -->
            <div class="settings-section">
                <h3>Mentions Légales</h3>
                <div class="legal-links">
                    <a href="#" class="legal-link">Conditions d'utilisation</a>
                    <a href="#" class="legal-link">Politique de confidentialité</a>
                    <a href="#" class="legal-link">Mentions légales</a>
                    <a href="#" class="legal-link">Cookies</a>
                </div>
                <div class="copyright">
                    <p>© 2025 TaskMaster - Tous droits réservés</p>
                </div>
            </div>
        </div>
    </div>


    <script type="module" src="">
       
            console.log('Formulaire:', document.getElementById('change-photo-form'));
    console.log('Input file:', document.getElementById('profile-picture'));
    
    // Déclencher automatiquement l'envoi quand un fichier est sélectionné
    document.getElementById('profile-picture').addEventListener('change', function() {
        console.log('Fichier sélectionné:', this.files[0]);
        document.getElementById('change-photo-form').submit();
    });

    
    </script>
</body>

</html>