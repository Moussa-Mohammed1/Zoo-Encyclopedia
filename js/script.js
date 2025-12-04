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
 
let habitatForm = document.getElementById('habitat-form');
let cancel = document.getElementById('cancel');
let addHabitat = document.getElementById('btn-ajouter-habitat');

function handleCancel(){
    location.href = "index.php";
}

addHabitat.addEventListener('click',()=>{
    if (habitatForm.classList.contains('hidden')){
        habitatForm.classList.remove('hidden');
    }
});
cancel.addEventListener('click',handleCancel);

let animalForm = document.getElementById('animal-form');
let addAnimal = document.getElementById('btn-ajouter-animal');
addAnimal.addEventListener('click',()=>{
    if(animalForm.classList.contains('hidden'))animalForm.classList.remove('hidden');
});
animalForm.addEventListener('click', (e)=>{
    if(!animalForm.firstElementChild.contains(e.target)){
        animalForm.classList.add('hidden');
    }
});
document.querySelectorAll('.btn-edit-animal').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const type = btn.dataset.type;
        const img = btn.dataset.img;
        const habitat = btn.dataset.habitat;

        document.getElementById('edit_animal_id').value = id;
        document.getElementById('edit_animal_name').value = name;
        document.getElementById('edit_animal_type').value = type;
        document.getElementById('edit_animal_img').value = img;
        document.getElementById('edit_habitat_ID').value = habitat;

        document.getElementById('edit-animal-modal').classList.remove('hidden');
    });
});
