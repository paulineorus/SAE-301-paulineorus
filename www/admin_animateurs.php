<?php 
// On se connecte à la base de données

require("config/config.php");

try {
    $dbh = new PDO($dsn, $identifiant, $mot_de_passe);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : '.$e->getMessage();
}

// Appel de l'API pour récupérer les stages

$api = "http://localhost/www/API/tousAnimateur.php";
$response = file_get_contents($api);
$donnees = json_decode($response, true);

// Vérifier que l'API a retourné un statut "OK"

if ($donnees["status"] == "OK" ) {
    $animateurs_data = $donnees['tousAnimateur']; 
    require_once("classes/Animateur.php"); 

}
else {
    echo "Erreur lors de la récupération des données.";
    exit();
}

include("ressources/ressourcesCommunes.php");
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Administration - Animateurs</title>
        <script src="js/supprimerAnimateur.js"></script>
    </head>
    <body>
        <header>
            <?php include("navbars/navbarAdmin.php"); ?>
        </header>
        <main class="container py-5">
            <h1 class="text-center text-primary fw-bold mb-2">NOS ANIMATEURS</h1>
            <section class="row justify-content-center" id="liste_animateur">
                <a href="formulaire_ajouter_animateur.php" class="ajouter">Ajouter un animateur</a>
                <!-- Boucle pour afficher tous les animateurs -->

                <?php foreach ($animateurs_data as $animateur_data){
                    // Créer un objet Animateur pour chaque Animateur

                    $animateur = new Animateur(
                    $animateur_data["id"],
                    $animateur_data["nom"],
                    $animateur_data["prenom"],
                    $animateur_data["age"],
                    $animateur_data["telephone"],
                    $animateur_data["description"],
                    $animateur_data["photo"]
                    );
                ?>
                <div class="col-12 col-md-8 mb-4">
                    <button class="suppr" value=<?php echo $animateur->id?>>Supprimer</button>
                    <a href="formulaire_modifier_animateur.php?id=<?php echo $animateur->id ?>"><button class="modif" value=<?php echo $animateur->id?>>Modifier</button></a>
                    <div class="d-flex align-items-start p-4 bg-light rounded shadow animateur-card">
                    <img src="<?php echo $animateur->photo; ?>" alt="Photo de <?php echo $animateur->prenom; ?>" class="rounded-circle me-4 animateur-photo" style="width: 150px; height: 150px; object-fit: cover; flex-shrink: 0;">
                    <div class="flex-grow-1">
                    <h3 class="fw-bold text-danger mb-1"><?php echo $animateur->prenom." ".$animateur->nom; ?></h3>
                    <p class="text-muted mb-2"><?php echo $animateur->age." ans"; ?></p>
                    <p class="mb-0"><?php echo $animateur->telephone; ?></p>
                    <p><?php echo $animateur->description; ?></p>
                </div>
               </div>
              </div>
             <?php } ?>
            </section>
        </main>
    </body>
</html>

