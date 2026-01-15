function validateSearch() {
    let searchInput = document.getElementById("search").value;
    let errorMsg = document.getElementById("search-error");

    if (searchInput.trim() === "") {
        errorMsg.style.display = "block";
        errorMsg.innerText = "Please enter a name to search!";
        return false;
    }

    errorMsg.style.display = "none";
    return true;
}