
    // Function to open the modal
    function openModal(name, image, price) {
        document.getElementById('watchModal').style.display = 'flex';
        document.getElementById('modalTitle').innerText = name;
        document.getElementById('modalImage').src = image;
        document.getElementById('modalPrice').innerText = price;
        document.getElementById('modalDescription').textContent = description;

        // Display the modal
        document.getElementById('watchModal').style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('watchModal').style.display = 'none';
    }

    // Close the modal if the user clicks outside of it
    window.onclick = function(event) {
        if (event.target === document.getElementById('watchModal')) {
            closeModal();
        }
    }
