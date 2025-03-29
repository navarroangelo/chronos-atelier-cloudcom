    function openModal(name, image, price, description, brand, year) {
        document.getElementById('modalTitle').textContent = `Name: ${name}`;
        document.getElementById('modalImage').src = image;
        document.getElementById('modalPrice').textContent = `Price: ₱${parseFloat(price).toLocaleString()}`;
        document.getElementById('modalDescription').textContent = `Description: ${description}`;
        document.getElementById('modalBrand').textContent = `Brand: ${brand || 'N/A'}`;
        document.getElementById('modalYear').textContent = `Year: ${year || 'N/A'}`;

    // Display the modal
    document.getElementById('watchModal').style.display = 'block';
}

    function closeModal() {
        document.getElementById('watchModal').style.display = 'none';
    }

    // Close the modal if the user clicks outside of it
    window.onclick = function(event) {
        if (event.target === document.getElementById('watchModal')) {
            closeModal();
        }
    }


    function filterWatches() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const watchCards = document.querySelectorAll('.watch-card');
    
        watchCards.forEach(card => {
            const watchName = card.getAttribute('data-name').toLowerCase();
            const watchBrand = card.querySelector('.watch-card__brand').textContent.toLowerCase();
    
            if (watchName.includes(input) || watchBrand.includes(input)) {
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