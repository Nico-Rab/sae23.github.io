// Load the data from the JSON file
let contacts = []
fetch("contacts.json")
    .then(response => response.json())
    .then(json => contacts = json);
const searchInput = document.getElementById("search");
const autocomplete = document.getElementById("autocomplete");
const results = document.getElementById("results");

// Filter the data based on the search value
function filterData(searchValue) {
    if(searchValue.includes(" ")){
        const searchArray = searchValue.split(" ");

        // Get the first part of the searchValue (before the space)
        const firstPart = searchArray[0];

        // Get the second part of the searchValue (after the space, if any)
        const secondPart = searchArray[1] || "";
        return contacts.filter(contact => {
            return (
                (contact.Prenom.toLowerCase().includes(firstPart.toLowerCase()) &&
                contact.Nom.toLowerCase().includes(secondPart.toLowerCase())) ||
                (contact.Prenom.toLowerCase().includes(secondPart.toLowerCase()) &&
                    contact.Nom.toLowerCase().includes(firstPart.toLowerCase()))
            );
        });
    } else {
        return contacts.filter(contact => {
            return (
                contact.Prenom.toLowerCase().includes(searchValue.toLowerCase()) ||
                contact.Nom.toLowerCase().includes(searchValue.toLowerCase())
            );
        });
    }
}

// Display the search results
function displayResults(filteredData) {
    let html = "";

    // Iterate over the filtered data and create a list item for each contact
    filteredData.forEach(contact => {
        let ville = contact.ville2 ? contact.ville2 : contact.ville1;
        html += `<li data-prenom="${contact.Prenom}" data-nom="${contact.Nom}" data-ville="${ville}">${contact.Prenom} ${contact.Nom}</li>`;
    });

    // Add the list items to the results element
    autocomplete.innerHTML = html;

    // Add a click event listener to each list item
    const listItems = document.querySelectorAll("#autocomplete li");
    listItems.forEach(item => {
        item.addEventListener("click", () => {
            // When an item is clicked, fill in the search bar with the contact's name
            // and clear the list
            searchInput.value = `${item.dataset.prenom} ${item.dataset.nom}`;
            autocomplete.innerHTML = "";
        });
    });
}

// Display the contact information
function searchContact(){
    // Get the contact first name and last name
    const searchValue = searchInput.value;
    const searchArray = searchValue.split(" ");
    const firstName = searchArray[0];
    const lastName = searchArray[1];

    // Find the contact's information in the original data
    const contact = contacts.find(contact => contact.Prenom === firstName && contact.Nom === lastName);
    const fullName = contact.Prenom + " " + contact.Nom;
    // Display the contact's information
    let html = ""
    if(contact.ville2 === null){
        html = `
        <h2>${fullName}</h2>
        <p><b>Adresse:</b> ${contact.adresse1}</p>
        <p><b>Ville:</b> ${contact.ville1}</p>
        `;
    } else {
        html = `
        <h2>${fullName}</h2>
        <p><b>Adresse Principale:</b> ${contact.adresse1}</p>
        <p><b>Ville:</b> ${contact.ville1}</p>
        <p><b>Adresse Secondaire:</b> ${contact.adresse2}</p>
        <p><b>Ville:</b> ${contact.ville2}</p>
        `;
    }
    results.innerHTML = html;
}

// Add an event listener to the search bar
searchInput.addEventListener("input", () => {
    // When the search bar value changes, filter the data and display the results
    if(searchInput.value===""){
        autocomplete.innerHTML = "";
        return;
    }
    let filteredData = filterData(searchInput.value);
    displayResults(filteredData);
});
