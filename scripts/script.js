    function openModal(name, image, price, description, brand, year) {
        document.getElementById('modalTitle').textContent = name;
        document.getElementById('modalImage').src = image;
        document.getElementById('modalPrice').textContent = `Price: ₱${parseFloat(price).toLocaleString()}`;
        document.getElementById('modalBrand').textContent = `Brand: ${brand}`;
        document.getElementById('modalYear').textContent = `Year: ${year}`;
        document.getElementById('modalDescription').textContent = description;



        document.getElementById('watchModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('watchModal').style.display = 'none';
    }


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
                card.style.display = 'block'; 
            } else {
                card.style.display = 'none'; 
            }
        });
    }
    
    function clearSearch() {
        const watchCards = document.querySelectorAll('.watch-card');
        document.getElementById('searchInput').value = ''; 
    
        watchCards.forEach(card => {
            card.style.display = 'block'; 
        });
    }

    const brandTrack = document.querySelector('.brand-scroller__track');

brandTrack.addEventListener('mouseover', () => {
    brandTrack.style.animationPlayState = 'paused';
});

brandTrack.addEventListener('mouseout', () => {
    brandTrack.style.animationPlayState = 'running';
});




