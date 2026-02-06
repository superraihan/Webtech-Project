function fetchPets() {
    let type = document.querySelector(".category-link.active").getAttribute("data-type");

    let params = new URLSearchParams();
    params.append('page', 'pets');
    params.append('ajax_filter', '1');
    if (type) params.append('type', type);

    fetch('index.php?' + params.toString())
        .then(response => response.text())
        .then(data => {
            document.querySelector(".pets-grid").innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener("DOMContentLoaded", function () {
    // Category Filter Listeners
    const categoryLinks = document.querySelectorAll(".category-link");
    categoryLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // Update active class
            categoryLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");

            fetchPets();
        });
    });
});

function requestAdoption(event, form) {
    event.preventDefault();

    let formData = new FormData(form);

    fetch('index.php?page=pets', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // Show partial message inside the pets-header section or alert
            // Providing a simple alert for now as per plan, better UI could be added.
            if (data.status === 'success') {
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred. Please try again.");
        });

    return false;
}

// Keep validateSearch if referenced elsewhere, but it's likely replaced by fetchPets
function validateSearch() {
    // Old validation logic, keeping empty function to prevent errors if called
}