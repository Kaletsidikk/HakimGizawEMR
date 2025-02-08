// Open the sidebar when the hamburger icon is clicked
document.getElementById("open-btn").addEventListener("click", function() {
    document.querySelector(".sidebar").classList.add("open");
});

// Close the sidebar when the close button is clicked
document.getElementById("close-btn").addEventListener("click", function() {
    document.querySelector(".sidebar").classList.remove("open");
});
