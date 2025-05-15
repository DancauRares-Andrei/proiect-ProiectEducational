document.addEventListener("DOMContentLoaded", function() {
    var contactForm = document.getElementById("contactForm");

    if (contactForm) { // Verifică dacă elementul există
        contactForm.addEventListener("submit", function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())  // Citește răspunsul ca text
            .then(text => {
                let firstLine = text.split("\n")[0]; // Extrage doar prima linie cu JSON-ul
                console.log("Răspuns curățat:", firstLine); // Depanare

                try {
                    let data = JSON.parse(firstLine); // Convertește în JSON
                    document.getElementById('response-message').textContent = data.message;
                } catch (error) {
                    console.error("Eroare la parsarea JSON:", error, "Text primit:", text);
                }
            })
            .catch(error => console.error('Eroare la trimiterea formularului:', error));
        });
    } else {
        console.warn("⚠️ Formularul nu a fost găsit pe pagină!");
    }
});

