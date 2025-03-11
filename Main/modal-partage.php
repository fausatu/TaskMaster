<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/modal-partage.css">
    <title>Document</title>
</head>
<body>
<div id="shareTaskModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Partager la tâche</h2>
        <form id="shareTaskForm">
            <input type="hidden" id="taskIdToShare" name="taskId">
            <div class="form-group">
                <label for="sharedUserEmail">Entrez l'e-mail de la personne :</label>
                <input type="email" id="sharedUserEmail" name="sharedUserEmail" required>
            </div>
            <div class="form-group">
                <label for="permissions">Permissions :</label>
                <select id="permissions" name="permissions" required>
                    <option value="lecture">Lecture seule</option>
                    <option value="modification">Lecture et écriture</option>
                </select>
            </div>
            <button type="submit" class="btn">Partager</button>
        </form>
    </div>
</div>


</body>
</html>