<?php 
    include("./config.php");
    $sql = "SELECT a.* , h.habitat_name
            FROM animal a 
            LEFT JOIN habitat h ON a.habitat_ID = h.habitat_ID
            ORDER BY a.ID DESC";
    $result = mysqli_query($conn, $sql);
    $habitat_sql = "SELECT *
                    FROM habitat";
    $habitat_result = mysqli_query($conn, $habitat_sql);

    $habitats = [];
    if (mysqli_num_rows($habitat_result) > 0) {
        while($row = mysqli_fetch_assoc($habitat_result)){
            $habitats[] = $row;
        };
    };
    $animals = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $animals[] = $row;
        };
    };
    // if (mysqli_query($conn, $habitat_sql)) {
    //     echo "<script>alert('Connectet')</script>";
    // }
    // else { echo "<script>alert('Unconnected!')</script>";}
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo Encyclopedie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                'zoo-primary': '#4CAF50', 
                'zoo-secondary': '#FFC107', 
                'zoo-bg': '#F7F7F7',
              }
            }
          }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-zoo-bg text-gray-800">
    
    <header class="sticky top-0 z-30 bg-white shadow-lg p-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <i class="fas fa-paw text-zoo-primary text-3xl"></i>
            <h1 class="text-3xl font-extrabold text-gray-900">Zoo-Crèche</h1>
        </div>
        <nav class="hidden md:flex space-x-6 text-lg font-medium">
            <a href="#accueil" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-home mr-2"></i> Accueil</a>
            <a href="#animaux" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-paw mr-2"></i> Animaux</a>
            <a href="#gestion-zoo" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-cogs mr-2"></i> Gestion</a>
            <a href="#statistiques" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-chart-bar mr-2"></i> Stats</a>
            <a href="#jeu-edu" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-gamepad mr-2"></i> Jeu</a>
        </nav>
        <div class="flex space-x-2">
            <button id="switch-fr" class="px-3 py-1 text-sm font-semibold rounded-lg bg-zoo-secondary text-gray-900 shadow">FR</button>
            <button id="switch-en" class="px-3 py-1 text-sm font-semibold rounded-lg bg-gray-200 hover:bg-gray-300 transition">EN</button>
        </div>
    </header>

    <main id="content" class="container mx-auto p-4 md:p-8">
        
        <section id="accueil" class="py-16 bg-white rounded-xl shadow-lg mb-10 text-center">
            <h2 class="text-4xl font-bold text-zoo-primary mb-4">Bienvenue à l'Explorateur du Zoo !</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Un jeu amusant pour les petits élèves de la crèche pour apprendre sur les animaux, leurs habitats et leurs régimes alimentaires.</p>
        </section>
        
        <section id="animaux" class="py-8">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-4 border-zoo-secondary pb-2"><i class="fa-solid fa-book"></i> Découvrez nos Animaux !</h2>

            <div class="filtres-container bg-white p-6 rounded-lg shadow-md mb-8 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <input type="text" placeholder="Rechercher..." class="p-3 border border-gray-300 rounded-lg w-full sm:w-1/2 outline-none focus:ring-zoo-primary focus:border-zoo-primary transition duration-150">
                <select id="filtre-habitat" class="p-3 border border-gray-300 rounded-lg w-full sm:w-1/2 focus:ring-zoo-primary focus:border-zoo-primary transition duration-150">
                    <option value="">Tous les Habitats</option>
                    <option value="Savane">Savane</option>
                    <option value="Jungle">Jungle</option>
                    <option value="Désert">Désert</option>
                    <option value="Océan">Océan</option>
                </select>
                <select id="filtre-alimentaire" class="p-3 border border-gray-300 rounded-lg w-full sm:w-1/2 focus:ring-zoo-primary focus:border-zoo-primary transition duration-150">
                    <option value="">Tous les Régimes</option>
                    <option value="Carnivore">Carnivore</option>
                    <option value="Herbivore">Herbivore</option>
                    <option value="Omnivore">Omnivore</option>
                </select>
            </div>

            <div id="liste-animaux" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($animals as $animal) :?>
                    <?php if(!count($animals) == 0) :?> 
                        <div class="carte-animal bg-white rounded-xl shadow-xl overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
                        
                            <img src="<?php echo htmlspecialchars($animal['animal_img']); ?>" alt="Animal Image" class="w-full bg- h-48 object-cover">

                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2"><?php echo $animal['animal_name'] ;?></h2>
                                <p class="text-gray-700"><span class="font-medium">Type:</span> <?php echo $animal['animal_type'] ;?></p>
                                <p class="text-gray-700"><span class="font-medium">Habitat:
                                    <?php if($animal['habitat_name']): ?>
                                        </span> <?php echo $animal['habitat_name'];?></p>
                                    <?php else : ?>
                                        </span> pas d'habitat enregistré</p>
                                    <?php endif; ?>
                            </div>
                        </div>
                    <?php else : ?>
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            Aucun animal trouvé. 
                        </td>
                    </tr>
                    <?php endif;?>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="statistiques" class="py-8">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-4 border-zoo-secondary pb-2">
                <i class="fas fa-chart-bar mr-2"></i> Statistiques du Zoo
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">Distribution par Habitat</h3>

                    <div class="space-y-2">
                        <p class="text-lg text-gray-800">Savane : <span id="habitat-savane" class="font-bold">12</span></p>
                        <p class="text-lg text-gray-800">Forêt : <span id="habitat-foret" class="font-bold">8</span></p>
                        <p class="text-lg text-gray-800">Marais : <span id="habitat-marais" class="font-bold">6</span></p>
                        <p class="text-lg text-gray-800">Désert : <span id="habitat-desert" class="font-bold">9</span></p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">Distribution par Type Alimentaire</h3>

                    <div class="space-y-2">
                        <p class="text-lg text-gray-800">Carnivores : <span id="type-carnivore" class="font-bold">14</span></p>
                        <p class="text-lg text-gray-800">Herbivores : <span id="type-herbivore" class="font-bold">11</span></p>
                        <p class="text-lg text-gray-800">Omnivores : <span id="type-omnivore" class="font-bold">10</span></p>
                    </div>
                </div>

            </div>

            <div class="mt-6 bg-zoo-primary/10 p-4 rounded-lg border-l-4 border-zoo-primary shadow-inner">
                <p class="text-lg font-semibold text-gray-800">
                    Résumé : Nombre total d'animaux : 
                    <strong><span id="total-animaux">35</span></strong> | 
                    Habitats : 
                    <strong><span id="total-habitats">4</span></strong>
                </p>
            </div>
        </section>


        <section id="gestion-zoo" class="py-8">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-4 border-red-500 pb-2"><i class="fa-solid fa-screwdriver-wrench"></i> Gestion du Zoo (Admin)</h2>

            <div class="gestion-tabs mb-6 flex space-x-2 border-b border-gray-200">
                <button id="animal-btn" onclick="showAdmin('animaux')" class="tab-btn bg-red-500 text-white px-4 py-2 rounded-t-lg font-semibold transition hover:bg-red-600">
                    <i class="fas fa-paw mr-2"></i> Gérer Animaux
                </button>
                <button id="habitat-btn" onclick="showAdmin('habitats')" class="tab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-t-lg font-semibold transition hover:bg-gray-300">
                    <i class="fas fa-tree mr-2"></i> Gérer Habitats
                </button>
            </div>

            <div id="admin-animaux" class="gestion-content bg-white p-6 rounded-lg shadow-xl">
                <h3 class="text-2xl font-bold mb-4 text-red-600">Gestion des Animaux</h3>
                
                <button id="btn-ajouter-animal" class="mb-4 px-4 py-2 bg-zoo-primary text-white rounded-lg font-semibold hover:bg-green-600 transition duration-200 shadow-md">
                    <i class="fas fa-plus mr-2"></i> Ajouter un Nouvel Animal
                </button>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Habitat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="table-animaux-body" class="bg-white divide-y divide-gray-200">
                            <?php foreach($animals as $animal) :?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $animal['animal_name'];?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $animal['habitat_name'];?></td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900 transition btn-edit-animal"
                                                data-id="<?= $animal['ID']?>"
                                                data-name="<?=$animal['animal_name']?>"
                                                data-type="<?=$animal['animal_type']?>"
                                                data-img="<?=$animal['animal_img']?>"
                                                data-habitat="<?=$animal['habitat_ID']?>"><i class="fas fa-edit"></i> Modifier</button>
                                        <a href="./animals/delete_animal.php?id=<?=$animal['ID']?>"><button class="text-red-600 hover:text-red-900 transition "
                                        ><i class="fas fa-trash"></i>Supprimer</button></a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="edit-animal-modal" class="inset-0 z-50 fixed flex justify-center items-center bg-black/50 hidden">
                <form id="edit-animal-form" action="./animals/edit_animal.php" class="w-2/4 space-y-4 bg-white p-6 rounded-lg shadow" method="POST">
                    <input type="hidden" id="edit_animal_id" name="animal_id">
                    <div>
                        <label for="edit_animal_name">Nom de l'animal</label>
                        <input type="text" id="edit_animal_name" name="animal_name" class="w-full border p-2 rounded-lg">
                    </div>

                    <div>
                        <label for="edit_animal_type">Type d'animal</label>
                        <select id="edit_animal_type" name="animal_type" class="w-full border p-2 rounded-lg">
                            <option value="Carnivore">Carnivore</option>
                            <option value="Herbivore">Herbivore</option>
                            <option value="Omnivore">Omnivore</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_animal_img">Image de l'animal</label>
                        <input type="text" id="edit_animal_img" name="animal_img" class="w-full border p-2 rounded-lg">
                    </div>

                    <div>
                        <label for="edit_habitat_ID">Habitat</label>
                        <select id="edit_habitat_ID" name="habitat_ID" class="w-full border p-2 rounded-lg">
                            <option value="">--select an habitat--</option>
                            <?php foreach($habitats as $habitat) :?>
                                <option value="<?= $habitat['habitat_ID'] ?>"><?= $habitat['habitat_name'] ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
 
                    <button name="submitEdit" type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg">Enregistrer</button>
                    <button type="button" onclick="document.getElementById('edit-animal-modal').classList.add('hidden');" class="w-full mt-2 bg-gray-400 text-white py-2 rounded-lg">Annuler</button>
                </form>
            </div>

            <div id="admin-habitats" class="gestion-content bg-white p-6 rounded-lg shadow-xl hidden">
                <h3 class="text-2xl font-bold mb-4 text-red-600">Gestion des Habitats</h3>
                
                <button id="btn-ajouter-habitat" class="mb-4 px-4 py-2 bg-zoo-primary text-white rounded-lg font-semibold hover:bg-green-600 transition duration-200 shadow-md">
                    <i class="fas fa-plus mr-2"></i> Ajouter un Nouvel Habitat
                </button>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom de l'Habitat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="table-habitats-body" class="bg-white divide-y divide-gray-200">
                            <?php foreach($habitats as $habitat) :?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo $habitat['habitat_name'];?> </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate"><?php echo $habitat['habitat_desc'];?></td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900 transition btn-modifier-habitat edit-habitat-btn" 
                                                data-id="<?=$habitat['habitat_ID']?>" 
                                                data-habitat="<?=$habitat['habitat_name']?>" 
                                                data-description="<?=$habitat['habitat_desc']?>"><i class="fas fa-edit"></i> Modifier</button>
                                        <a href="./habitats/delete_habitat.php?id=<?=$habitat['habitat_ID']?>"><button class="text-red-600 hover:text-red-900 transition btn-supprimer-habitat" data-id="1"><i class="fas fa-trash"></i> Supprimer</button></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                    </table>
                </div>

               
            </section>
            <div
                id="animal-form"
                class="inset-0 z-50  bg-black/50 fixed flex justify-center items-center hidden">
                <form action="./animals/add_animal.php" method="POST" enctype="multipart/form-data" class="w-2/4 space-y-4 bg-white p-6 rounded-lg shadow">
        
                    <div>
                        <label for="animal_name" class="block font-semibold mb-1">Nom de l'animal</label>
                        <input type="text" id="animal_name" name="animal_name"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <div>
                        <label for="animal_type" class="block font-semibold mb-1">Type d'animal</label>
                        <select id="animal_type" name="animal_type"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option value="">-- Choisir --</option>
                            <option value="Carnivore">Carnivore</option>
                            <option value="Herbivore">Herbivore</option>
                            <option value="Omnivore">Omnivore</option>
                        </select>
                    </div>

                    <div>
                        <label for="animal_img" class="block font-semibold mb-1">Image de l'animal</label>
                        <input type="text" id="animal_img" name="animal_img" placeholder="https://www.image/example.com"
                            class="w-full border border-gray-300 rounded-lg p-2 cursor-pointer">
                    </div>

                    <div>
                        <label for="habitat_ID" class="block font-semibold mb-1">Habitat</label>
                        <select id="habitat_ID" name="habitat_ID"
                            class="w-full border border-gray-300 text-black rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option>--select an habitat--</option>
                                <?php foreach($habitats as $habitat) :?>
                                    <?php echo '<option value="'. $habitat['habitat_ID'] .'">' . $habitat['habitat_name'] . '</option>';?>
                                <?php endforeach;?>
                        </select>
                    </div>

                    <button 
                        type="submit"
                        name="submitAnimal"
                        class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Ajouter l'animal
                    </button>

                </form>
            </div>
            <div
                id="habitat-form"
                class="inset-0 bg-black/50 fixed flex justify-center items-center hidden">
                <div id="form-habitat-modal" class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200 ">
                    <h4 class="text-xl font-semibold mb-3">Ajouter un Habitat</h4>
                    <form id="form-habitat" action="./habitats/add_habitat.php" method="POST">
                        <div class="mb-4">
                            <label for="nom_habitat" class="block text-sm font-medium text-gray-700">Nom de l'Habitat:</label>
                            <input type="text" id="nom_habitat" name="nom_habitat" required 
                                class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500">
                        </div>
        
                        <div class="mb-4">
                            <label for="description_hab" class="block text-sm font-medium text-gray-700">Description :</label>
                            <textarea id="description_hab" name="description_hab" rows="3" required
                                    class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500"></textarea>
                        </div>
                        
                        <button  name="submitHabitat" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> Enregistrer l'Habitat
                        </button>
                        <button id="cancel" name="cancel" type="button" class="px-4 py-2 bg-gray-400 text-white rounded-lg font-semibold hover:bg-gray-500 transition duration-200 ml-2" onclick="document.getElementById('form-habitat-modal').classList.add('hidden');">
                            Annuler
                        </button>
                    </form>
                </div>
            </div>
            <div
                id="edit-habitat-form"
                class="inset-0 bg-black/50 fixed flex justify-center items-center hidden">
                <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200 ">
                    <h4 class="text-xl font-semibold mb-3">Modifier un Habitat</h4>
                    <form action="./habitats/edit_habitat.php" method="POST">
                        <input type="hidden" id="edit-habitat-id" name="habitat-ID">
                        <div class="mb-4">
                            <label for="nom-habitat" class="block text-sm font-medium text-gray-700">Nom de l'Habitat:</label>
                            <input type="text" id="nom-habitat" name="nom-habitat" required 
                                class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500">
                        </div>
        
                        <div class="mb-4">
                            <label for="description-hab" class="block text-sm font-medium text-gray-700">Description :</label>
                            <textarea id="description-hab" name="description-hab" rows="3" required
                                    class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500"></textarea>
                        </div>
                        
                        <button  name="submitEditHabitat" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> Enregistrer l'Habitat
                        </button>
                        <button id="cancel" name="cancel" type="button" class="px-4 py-2 bg-gray-400 text-white rounded-lg font-semibold hover:bg-gray-500 transition duration-200 ml-2" onclick="document.getElementById('form-habitat-modal').classList.add('hidden');">
                            Annuler
                        </button>
                    </form>
                </div>
            </div>
        <section id="jeu-edu" class="py-8 bg-zoo-secondary/20 p-6 rounded-xl shadow-lg border-l-8 border-zoo-secondary">
            <h2 class="text-4xl font-bold text-gray-900 mb-4"><i class="fa-solid fa-gamepad"></i> Jeu Éducatif : Sons et Images !</h2>
            <p class="text-lg text-gray-700">Un espace interactif pour tester les connaissances des enfants (à développer avec JavaScript pour les sons et le scoring).</p>
        </section>

    </main>

    <footer class="bg-gray-800 text-white p-6 text-center">
        <p class="text-sm">&copy; 2024 Mon Zoo Éducatif - Accessibilité et Ergonomie assurées.</p>
    </footer>

    <script src="./js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>