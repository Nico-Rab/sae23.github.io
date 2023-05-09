let data;
jQuery.ajax({
    url: 'search.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        data = response;
    }
});

const searchInput = document.getElementById("search");
const autocomplete = document.getElementById("autocomplete");
const results = document.getElementById("results");
const wind = document.getElementById("wind");

// Filter the data based on the search value
function filterData(searchValue) {
    if(searchValue.includes(" ")){
        const searchArray = searchValue.split(" ");
        // Get the first part of the searchValue (before the space)
        const firstPart = searchArray[0];
        // Get the second part of the searchValue (after the space, if any)
        const secondPart = searchArray[1] || "";
        return data.filter(contact => {
            return (
                (contact.Prenom.toLowerCase().includes(firstPart.toLowerCase()) &&
                    contact.Nom.toLowerCase().includes(secondPart.toLowerCase())) ||
                (contact.Prenom.toLowerCase().includes(secondPart.toLowerCase()) &&
                    contact.Nom.toLowerCase().includes(firstPart.toLowerCase()))
            );
        });
    } else {
        return data.filter(contact => {
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
        let ville = contact.DS ? contact.DS : contact.DP;
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

$('form').submit(function(event) {
    // Prevent default form submission
    event.preventDefault();

    // Get form data
    let formData = $(this).serialize();

    // Send AJAX request to server
    $.ajax({
        url: 'submit-form.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            let databaseResponse = JSON.parse(response);
            // wind.innerHTML = `${databaseResponse}`;
            // console.log(databaseResponse);
            /// Display the contact information
            const searchValue = searchInput.value;
            const searchArray = searchValue.split(" ");
            const firstName = searchArray[0];
            const lastName = searchArray[1];
            // Find the contact's information in the original data
            const contact = data.find(contact => contact.Prenom === firstName && contact.Nom === lastName);
            const fullName = contact.Prenom + " " + contact.Nom;
            // Display the contact's information
            let html = "";
            let htmlWind = "";
            let ville1;
            let ville2;
            let code_postal1;
            let code_postal2;
            if(contact.DS === null){
                ville1 = databaseResponse[0]['Nom'];
                code_postal1 = databaseResponse[0]['Code_Postal'];
                html = `
            <h2>${fullName}</h2>
            <p><b>Code Postal:</b> ${code_postal1}</p>
            <p><b>Ville:</b> ${ville1}</p>
            `;
                htmlWind = `
            <h2>Vent</h2>
            <p><b>Vitesse:</b> ${databaseResponse['FVDP']}</p>
            <p><b>Direction:</b> ${databaseResponse['DVDP']}</p>
            `;
            } else {
                ville1 = databaseResponse[0]['Nom'];
                code_postal1 = databaseResponse[0]['Code_Postal'];
                ville2 = databaseResponse[1]['Nom'];
                code_postal2 = databaseResponse[1]['Code_Postal'];
                html = `
            <h2>${fullName}</h2>
            <p><b>Code Postal Principal:</b> ${code_postal1}</p>
            <p><b>Ville:</b> ${ville1}</p>
            <p><b>Code Postal Secondaire:</b> ${code_postal2}</p>
            <p><b>Ville Secondaire:</b> ${ville2}</p>
            `;
                htmlWind = `
            <h2>Vent</h2>
            <h3>${ville1}</h3>
            <p><b>Vitesse:</b> ${databaseResponse['FVDP']}</p>
            <p><b>Direction:</b> ${databaseResponse['DVDP']}</p>
            <h3>${ville2}</h3>
            <p><b>Vitesse:</b> ${databaseResponse['FVDS']}</p>
            <p><b>Direction:</b> ${databaseResponse['DVDS']}</p>
            `;
            }

            results.innerHTML = html;
            wind.innerHTML = htmlWind;
        },
        error: function(xhr, status, error) {
            // Display the error on the page
            results.innerHTML = `
            <h2>Error</h2>
            <p>${error}</p>
            <h2>XHR</h2>
            <p>${xhr.responseText}</p>
            <h2>Status</h2>
            <p>${status}</p>
            `;
        }
    });
});
