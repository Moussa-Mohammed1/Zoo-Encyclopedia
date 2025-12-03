function showAdmin(type) {
    const adminAnimaux = document.getElementById('admin-animaux');
    const adminHabitats = document.getElementById('admin-habitats');
    let animalBtn = document.getElementById('animal-btn');
    let habitatBtn = document.getElementById('habitat-btn');
    if (type === 'animaux') {
        adminAnimaux.classList.remove('hidden');
        adminHabitats.classList.add('hidden');
        habitatBtn.className = "";
        habitatBtn.className = "tab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-t-lg font-semibold transition hover:bg-gray-300";
        animalBtn.className = "";
        animalBtn.className = "tab-btn bg-red-500 text-white px-4 py-2 rounded-t-lg font-semibold transition hover:bg-red-600";

        
    } else if (type === 'habitats') {
        adminHabitats.classList.remove('hidden');
        adminAnimaux.classList.add('hidden');
        habitatBtn.className = "";
        habitatBtn.className = "tab-btn bg-red-500 text-white px-4 py-2 rounded-t-lg font-semibold transition hover:bg-red-600";
        animalBtn.className = "";
        animalBtn.className = "tab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-t-lg font-semibold transition hover:bg-gray-300";
    }
}
