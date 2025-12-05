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
    $habitat_counts =[];
    $type_counts = [
        'Carnivore' => 0,
        'Herbivore' => 0,
        'Omnivore' => 0
    ];
    $total_animals = count($animals);
    $total_habitats = count($habitats);
    // echo $total_habitats;
    foreach($animals as $animal){
        $habitat_name = $animal['habitat_name'] ?? 'Unknown';
        if (!isset($habitat_counts[$habitat_name])) {
            $habitat_counts[$habitat_name] = 0;
        }
        $habitat_counts[$habitat_name] ++;
        $type = $animal['animal_type'];
        if (isset($type_counts[$type])) {
            $type_counts[$type]++;
        }
    }
    $lang = $_GET['lang'] ?? 'fr';
    include("./lang/$lang.php");
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$translate['Zoo Encyclopedie']?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/styles.css">
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
            <h1 class="text-3xl font-extrabold text-gray-900"><?=$translate['Zoo-Crèche']?></h1>
        </div>
        <nav class="hidden md:flex space-x-6 text-lg font-medium">
            <a href="#accueil" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-home mr-2"></i><?=$translate['Accueil']?> </a>
            <a href="#animaux" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-paw mr-2"></i> <?=$translate['Animaux']?></a>
            <a href="#gestion-zoo" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-cogs mr-2"></i> <?=$translate['Gestion']?></a>
            <a href="#statistiques" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-chart-bar mr-2"></i> <?=$translate['Stats']?></a>
            <a href="#jeu-edu" class="text-gray-600 hover:text-zoo-primary transition duration-300 flex items-center"><i class="fas fa-gamepad mr-2"></i><?=$translate['Jeu']?></a>
        </nav>
        <div class="flex space-x-2">
            <button id="switch-fr" class="px-3 py-1 text-sm font-semibold rounded-lg bg-zoo-secondary text-gray-900 shadow"><a href="?lang=fr">FR</a></button>
            <button id="switch-en" class="px-3 py-1 text-sm font-semibold rounded-lg bg-gray-200 hover:bg-gray-300 transition"><a href="?lang=en">EN</a></button>
        </div>
    </header>

    <main id="content" class="container mx-auto p-4 md:p-8">
        
        <section id="accueil" class="zoo-background py-16 bg-white rounded-xl shadow-lg mb-10 text-center">
            <h2 class="text-4xl font-bold text-zoo-primary mb-4"><?=$translate["Bienvenue à l'Explorateur du Zoo !"]?></h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto"><?=$translate["Un jeu amusant pour les petits élèves de la crèche pour apprendre sur les animaux, leurs habitats et leurs régimes alimentaires."]?></p>
        </section>
        
        <section id="animaux" class="py-8">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-4 border-zoo-secondary pb-2"><i class="fa-solid fa-book"></i><?=$translate['Découvrez nos Animaux !']?></h2>
            <form class="filtres-container bg-white p-6 rounded-lg shadow-md mb-8 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4" 
                    id="filter-form"
                    action = ""
                    method="GET">
                
                <select id="filtre-habitat" name="habitat_filter" class="p-3 border border-gray-300 rounded-lg w-full sm:w-1/2 focus:ring-zoo-primary focus:border-zoo-primary transition duration-150">
                    <?php $selected_habitat = $_GET['habitat_filter']?>
                    <option value=""><?=$translate['Tous les Habitats']?></option>
                    <?php foreach($habitats as $habitat) : ?>
                    <option <?= $selected_habitat == $habitat['habitat_name'] ? 'selected' : ''?> ><?=$habitat['habitat_name']?></option>
                    <?php endforeach?>
                </select>
                <?php $selected_type = $_GET['alimentaire_filter'] ?? ''; ?>
                <select id="filtre-alimentaire" name="alimentaire_filter" class="p-3 border border-gray-300 rounded-lg w-full sm:w-1/2 focus:ring-zoo-primary focus:border-zoo-primary transition duration-150">
                    <option value="" <?= $selected_type == '' ? 'selected' : '' ?>><?=$translate['Tous les Régimes']?></option>
                    <option value="Carnivore" <?= $selected_type == 'Carnivore' ? 'selected' : '' ?>>Carnivore</option>
                    <option value="Herbivore" <?= $selected_type == 'Herbivore' ? 'selected' : '' ?>>Herbivore</option>
                    <option value="Omnivore" <?= $selected_type == 'Omnivore' ? 'selected' : '' ?>>Omnivore</option>
                </select>

                <button 
                    name="submitFilter"
                    type="submit" class="mt-4 font-semibold sm:mt-0 p-3 bg-zoo-primary text-white rounded-lg w-full sm:w-auto hover:bg-zoo-primary-dark transition duration-150">
                    <?=$translate['Filtrer']?>
                </button>
            </form>

            <div id="liste-animaux" class="grid grid-cols-1 overflow-auto py-10  [scrollbar-width:none] min-h-[300px] max-h-[500px]  sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php
   
                    foreach($animals as $animal){
                        if(isset($_GET['submitFilter'])){
                            $wanted_habitat = $_GET['habitat_filter'] ?? '';
                            $wanted_type = $_GET['alimentaire_filter'] ?? '';
                            if (($wanted_type == '' || $animal['animal_type'] == $wanted_type) && ($wanted_habitat == '' || $animal['habitat_name'] == $wanted_habitat)) {
                                wanted_Animal($animal);
                            }
                        }
                        else{
                            wanted_Animal($animal);
                        }
                    };

                    function wanted_Animal($animal) {
                        echo '
                            <div class="carte-animal bg-white rounded-xl shadow-xl overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
                                <img src="' . htmlspecialchars($animal['animal_img']) . '" alt="Animal Image" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h2 class="text-xl font-semibold mb-2">' . htmlspecialchars($animal['animal_name']) . '</h2>
                                    <p class="text-gray-700"><span class="font-medium">Type:</span> ' . htmlspecialchars($animal['animal_type']) . '</p>
                                    <p class="text-gray-700"><span class="font-medium">Habitat:</span> ' . 
                                        ($animal['habitat_name'] ? htmlspecialchars($animal['habitat_name']) : $translate["pas d'habitat enregistré"]) . 
                                    '</p>
                                </div>
                            </div>
                            ';
                    };

                ?>
            </div>
        </section>

        <section id="statistiques" class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-4 border-zoo-secondary pb-2">
                        <i class="fas fa-chart-bar mr-2"></i> <?=$translate['Statistiques du Zoo']?>
                    </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <div class="bg-white p-8 rounded-3xl shadow-2xl border-b-4 border-yellow-500 transform hover:scale-105 transition duration-300 ease-in-out">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                            <i class="fas fa-globe text-yellow-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider"><?=$translate['Résidents Totaux']?></p>
                        <p class="text-4xl font-bold text-gray-900 mt-1" id="total-animaux"><?=$total_animals?></p>
                        <p class="text-sm text-gray-400 mt-2"><?=$translate['Animaux surveillés']?></p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-2xl border-b-4 border-green-500 transform hover:scale-105 transition duration-300 ease-in-out">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <i class="fas fa-tree text-green-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider"><?=$translate['Écosystèmes Majurs']?></p>
                        <p class="text-4xl font-bold text-gray-900 mt-1" id="total-habitats"><?=$total_habitats?></p>
                        <p class="text-sm text-gray-400 mt-2"><?=$translate['Habitats diversifiés']?></p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-2xl border-b-4 border-red-500 transform hover:scale-105 transition duration-300 ease-in-out">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i class="fas fa-bone text-red-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Carnivores</p>
                        <p class="text-4xl font-bold text-gray-900 mt-1" id="type-carnivore"><?=$type_counts['Carnivore']?></p>
                        <p class="text-sm text-gray-400 mt-2"><?=$translate['Prédateurs de pointe']?></p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-2xl border-b-4 border-blue-500 transform hover:scale-105 transition duration-300 ease-in-out">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                            <i class="fas fa-leaf text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Herbivores</p>
                        <p class="text-4xl font-bold text-gray-900 mt-1" id="type-herbivore"><?=$type_counts['Herbivore']?></p>
                        <p class="text-sm text-gray-400 mt-2"><?=$translate['Consommateurs primaires']?></p>
                    </div>
                    
                </div>

                <div class="mt-12">
                    <h3 class="text-3xl font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt text-zoo-secondary mr-2"></i> <?=$translate['Répartition Géographique']?>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200 hover:bg-yellow-100 transition duration-150">
                            <p class="text-xl font-bold text-yellow-800 flex justify-between items-center">
                                 <?=$translate['Savane']?> 
                                <span class="text-3xl font-extrabold text-yellow-900" id="habitat-savane"><?=$habitat_counts['Savane']?></span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1"> <?=$translate['Vastes plaines et grands mammifères']?></p>
                        </div>

                        <div class="bg-green-50 p-6 rounded-xl border border-green-200 hover:bg-green-100 transition duration-150">
                            <p class="text-xl font-bold text-green-800 flex justify-between items-center">
                                 <?=$translate['Forêt']?> 
                                <span class="text-3xl font-extrabold text-green-900" id="habitat-foret"><?=$habitat_counts['Jungle'] ?? 0 ?></span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1"> <?=$translate['Canopée dense et vie cachée']?></p>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-xl border border-blue-200 hover:bg-blue-100 transition duration-150">
                            <p class="text-xl font-bold text-blue-800 flex justify-between items-center">
                                Desert
                                <span class="text-3xl font-extrabold text-blue-900" id="habitat-marais"><?=$habitat_counts['Desert']?></span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1"> <?=$translate['Zones humides et amphibiens']?></p>
                        </div>

                        <div class="bg-red-50 p-6 rounded-xl border border-red-200 hover:bg-red-100 transition duration-150">
                            <p class="text-xl font-bold text-red-800 flex justify-between items-center">
                                Ocean 
                                <span class="text-3xl font-extrabold text-red-900" id="habitat-desert"><?= $habitat_counts['Ocean'] ?? 0 ?></span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1"> <?=$translate['Environnements arides et résilients']?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-12">
                    <h3 class="text-3xl font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center">
                        <i class="fas fa-utensils text-zoo-secondary mr-2"></i>  <?=$translate['Régimes Alimentaires']?>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="bg-red-50 p-6 rounded-xl border border-red-200 flex justify-between items-center hover:shadow-md transition duration-150">
                            <p class="text-xl font-semibold text-red-800">Carnivores</p>
                            <span class="text-4xl font-extrabold text-red-900" id="type-carnivore"><?= $type_counts['Carnivore'] ?? 0?></span>
                        </div>

                        <div class="bg-green-50 p-6 rounded-xl border border-green-200 flex justify-between items-center hover:shadow-md transition duration-150">
                            <p class="text-xl font-semibold text-green-800">Herbivores</p>
                            <span class="text-4xl font-extrabold text-green-900" id="type-herbivore"><?= $type_counts['Herbivore'] ?? 0?></span>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-xl border border-blue-200 flex justify-between items-center hover:shadow-md transition duration-150">
                            <p class="text-xl font-semibold text-blue-800">Omnivores</p>
                            <span class="text-4xl font-extrabold text-blue-900" id="type-omnivore"><?= $type_counts['Omnivore'] ?? 0?></span>
                        </div>
                    </div>
                </div>

            </div>
        </section>


        <section id="gestion-zoo" class="py-8">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 border-b-4 border-red-500 pb-2"><i class="fa-solid fa-screwdriver-wrench"></i>  <?=$translate['Gestion du Zoo (Admin)']?></h2>

            <div class="gestion-tabs mb-6 flex space-x-2 border-b border-gray-200">
                <button id="animal-btn" onclick="showAdmin('animaux')" class="tab-btn bg-red-500 text-white px-4 py-2 rounded-t-lg font-semibold transition hover:bg-red-600">
                    <i class="fas fa-paw mr-2"></i>  <?=$translate['Gérer Animaux']?>
                </button>
                <button id="habitat-btn" onclick="showAdmin('habitats')" class="tab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-t-lg font-semibold transition hover:bg-gray-300">
                    <i class="fas fa-tree mr-2"></i>  <?=$translate['Gérer Habitats']?>
                </button>
            </div>

            <div id="admin-animaux" class="gestion-content bg-white p-6 rounded-lg shadow-xl">
                <h3 class="text-2xl font-bold mb-4 text-red-600"> <?=$translate['Gestion des Animaux']?></h3>
                
                <button id="btn-ajouter-animal" class="mb-4 px-4 py-2 bg-zoo-primary text-white rounded-lg font-semibold hover:bg-green-600 transition duration-200 shadow-md">
                    <i class="fas fa-plus mr-2"></i>  <?=$translate['Ajouter un Nouvel Animal']?>
                </button>

                <div class="overflow-auto  min-h-[100px] max-h-[200px] [scrollbar-width:none]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> <?=$translate['Nom']?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Habitat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?=$translate['Actions']?></th>
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
                                                data-habitat="<?=$animal['habitat_ID']?>"><i class="fas fa-edit"></i><?=$translate['Modifier']?></button>
                                        <a href="./animals/delete_animal.php?id=<?=$animal['ID']?>"><button class="text-red-600 hover:text-red-900 transition "
                                        ><i class="fas fa-trash"></i><?=$translate['Supprimer']?></button></a>
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
                        <label for="edit_animal_name"><?=$translate["Nom de l'animal"]?></label>
                        <input type="text" id="edit_animal_name" name="animal_name" class="w-full border p-2 rounded-lg">
                    </div>

                    <div>
                        <label for="edit_animal_type"><?=$translate["Type d'animal"]?></label>
                        <select id="edit_animal_type" name="animal_type" class="w-full border p-2 rounded-lg">
                            <option value="Carnivore">Carnivore</option>
                            <option value="Herbivore">Herbivore</option>
                            <option value="Omnivore">Omnivore</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_animal_img"><?=$translate["Image de l'animal"]?></label>
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
 
                    <button name="submitEdit" type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg"><?=$translate['Enregistrer']?></button>
                    <button type="button" onclick="document.getElementById('edit-animal-modal').classList.add('hidden');" class="w-full mt-2 bg-gray-400 text-white py-2 rounded-lg"><?=$translate['Annuler']?></button>
                </form>
            </div>

            <div id="admin-habitats" class="gestion-content bg-white p-6 rounded-lg shadow-xl hidden">
                <h3 class="text-2xl font-bold mb-4 text-red-600"><?=$translate["Gestion des Habitats"]?></h3>
                
                <button id="btn-ajouter-habitat" class="mb-4 px-4 py-2 bg-zoo-primary text-white rounded-lg font-semibold hover:bg-green-600 transition duration-200 shadow-md">
                    <i class="fas fa-plus mr-2"></i> <?=$translate['Ajouter un Nouvel Habitat']?>
                </button>

                <div class="overflow-auto  min-h-[100px] max-h-[300px] [scrollbar-width:none]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 ">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?=$translate["Nom de l'Habitat"]?></th>
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
                                                data-description="<?=$habitat['habitat_desc']?>"><i class="fas fa-edit"></i> <?=$translate['Modifier']?></button>
                                        <a href="./habitats/delete_habitat.php?id=<?=$habitat['habitat_ID']?>"><button class="text-red-600 hover:text-red-900 transition btn-supprimer-habitat" data-id="1"><i class="fas fa-trash"></i> <?=$translate['Supprimer']?></button></a>
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
                        <label for="animal_name" class="block font-semibold mb-1"><?=$translate["Nom de l'animal"]?></label>
                        <input type="text" id="animal_name" name="animal_name"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <div>
                        <label for="animal_type" class="block font-semibold mb-1"><?=$translate["Nom de l'animal"]?></label>
                        <select id="animal_type" name="animal_type"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option value=""><?=$translate['-- Choisir --']?></option>
                            <option value="Carnivore">Carnivore</option>
                            <option value="Herbivore">Herbivore</option>
                            <option value="Omnivore">Omnivore</option>
                        </select>
                    </div>

                    <div>
                        <label for="animal_img" class="block font-semibold mb-1"><?=$translate["Image de l'animal"]?></label>
                        <input type="text" id="animal_img" name="animal_img" placeholder="https://www.image/example.com"
                            class="w-full border border-gray-300 rounded-lg p-2 cursor-pointer">
                    </div>

                    <div>
                        <label for="habitat_ID" class="block font-semibold mb-1">Habitat</label>
                        <select id="habitat_ID" name="habitat_ID"
                            class="w-full border border-gray-300 text-black rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option><?=$translate['--choisir un habitat--']?></option>
                                <?php foreach($habitats as $habitat) :?>
                                    <?php echo '<option value="'. $habitat['habitat_ID'] .'">' . $habitat['habitat_name'] . '</option>';?>
                                <?php endforeach;?>
                        </select>
                    </div>

                    <button 
                        type="submit"
                        name="submitAnimal"
                        class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                        <?=$translate["Ajouter l'animal"]?>
                    </button>

                </form>
            </div>
            <div
                id="habitat-form"
                class="inset-0 bg-black/50 fixed flex justify-center items-center hidden">
                <div id="form-habitat-modal" class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200 ">
                    <h4 class="text-xl font-semibold mb-3"><?=$translate['Ajouter un Habitat']?></h4>
                    <form id="form-habitat" action="./habitats/add_habitat.php" method="POST">
                        <div class="mb-4">
                            <label for="nom_habitat" class="block text-sm font-medium text-gray-700"><?=$translate["Nom de l'Habitat"]?></label>
                            <input type="text" id="nom_habitat" name="nom_habitat" required 
                                class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500">
                        </div>
        
                        <div class="mb-4">
                            <label for="description_hab" class="block text-sm font-medium text-gray-700">Description :</label>
                            <textarea id="description_hab" name="description_hab" rows="3" required
                                    class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500"></textarea>
                        </div>
                        
                        <button  name="submitHabitat" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> <?=$translate["Enregistrer l'Habitat"]?>
                        </button>
                        <button id="cancel" name="cancel" type="button" class="px-4 py-2 bg-gray-400 text-white rounded-lg font-semibold hover:bg-gray-500 transition duration-200 ml-2" onclick="document.getElementById('form-habitat-modal').classList.add('hidden');">
                           <?=$translate['Annuler']?>
                        </button>
                    </form>
                </div>
            </div>
            <div
                id="edit-habitat-form"
                class="inset-0 bg-black/50 fixed flex justify-center items-center hidden">
                <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200 ">
                    <h4 class="text-xl font-semibold mb-3"><?=$translate['Modifier un Habitat']?></h4>
                    <form action="./habitats/edit_habitat.php" method="POST">
                        <input type="hidden" id="edit-habitat-id" name="habitat-ID">
                        <div class="mb-4">
                            <label for="nom-habitat" class="block text-sm font-medium text-gray-700"><?=$translate["Nom de l'Habitat:"]?></label>
                            <input type="text" id="nom-habitat" name="nom-habitat" required 
                                class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500">
                        </div>
        
                        <div class="mb-4">
                            <label for="description-hab" class="block text-sm font-medium text-gray-700">Description :</label>
                            <textarea id="description-hab" name="description-hab" rows="3" required
                                    class="mt-1 block w-full rounded-md focus:ring-blue-500 focus:ring-2 outline-none  shadow-sm p-2 border  duration-500"></textarea>
                        </div>
                        
                        <button  name="submitEditHabitat" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> <?=$translate["Enregistrer l'Habitat"]?>
                        </button>
                        <button id="cancel" name="cancel" type="button" class="px-4 py-2 bg-gray-400 text-white rounded-lg font-semibold hover:bg-gray-500 transition duration-200 ml-2" onclick="document.getElementById('form-habitat-modal').classList.add('hidden');">
                            <?=$translate['Annuler']?>
                        </button>
                    </form>
                </div>
            </div>
        <section id="jeu-edu" class="py-8 bg-zoo-secondary/20 p-6 rounded-xl shadow-lg border-l-8 border-zoo-secondary">
            <h2 class="text-4xl font-bold text-gray-900 mb-4"><i class="fa-solid fa-gamepad"></i> <?=$translate['Jeu Éducatif : Sons et Images !']?></h2>
            <p class="text-lg text-gray-700"><?=$translate["Un espace interactif pour tester les connaissances des enfants (à développer avec JavaScript pour les sons et le scoring)."]?></p>
        </section>

    </main>

    <footer class="bg-gray-800 text-white p-6 text-center">
        <p class="text-sm"><?=$translate['&copy; 2024 Mon Zoo Éducatif - Accessibilité et Ergonomie assurées.']?></p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./js/script.js"></script>
</body>
</html>