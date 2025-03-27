
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


    function filterWatchesByName() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const watchCards = document.querySelectorAll('.watch-card');
    
        watchCards.forEach(card => {
            const watchName = card.getAttribute('data-name').toLowerCase();
            if (watchName.includes(input)) {
                card.style.display = 'block'; // Show the card if it matches the search
            } else {
                card.style.display = 'none'; // Hide the card if it doesn't match
            }
        });
    }
    
    function clearSearch() {
        const watchCards = document.querySelectorAll('.watch-card');
        document.getElementById('searchInput').value = ''; // Clear the input field
    
        watchCards.forEach(card => {
            card.style.display = 'block'; // Show all cards
        });
    }

    const brandTrack = document.querySelector('.brand-scroller__track');

brandTrack.addEventListener('mouseover', () => {
    brandTrack.style.animationPlayState = 'paused';
});

brandTrack.addEventListener('mouseout', () => {
    brandTrack.style.animationPlayState = 'running';
});