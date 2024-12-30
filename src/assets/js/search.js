document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const searchSuggest = document.getElementById('search-suggest');
    const searchLoading = document.getElementById('search-loading');
    const resultContainer = searchSuggest.querySelector('.result');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length >= 2) {
            searchSuggest.style.display = 'block';
            searchLoading.style.display = 'block';
            resultContainer.style.display = 'none';

            clearTimeout(timeoutId);

            timeoutId = setTimeout(() => {
                fetchSearchResults(query);
            }, 300);
        } else {
            searchSuggest.style.display = 'none';
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchSuggest.contains(e.target) && !searchInput.contains(e.target)) {
            searchSuggest.style.display = 'none';
        }
    });

    async function fetchSearchResults(query) {
        try {
            const response = await fetch(`/src/ajax/search-ajax.php?keyword=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            searchLoading.style.display = 'none';
            resultContainer.style.display = 'block';
            
            if (data.success && data.data && data.data.animes && data.data.animes.length > 0) {
                resultContainer.innerHTML = data.data.animes.map(anime => `
                    <a href="/details/${anime.id}" class="nav-item" style="display: flex; align-items: center;">
                        <div class="thumbnail" style="flex-shrink: 0;">
                            <img src="${anime.poster}" alt="${anime.name}">
                        </div>
                        <div class="info" style="flex-grow: 1; margin-left: 10px;">
                            <div class="title">${anime.name}</div>
                            <div class="meta">${anime.alias || ''}</div>
                            <div class="meta">${anime.releaseDate || ''} • ${anime.type} • ${anime.duration || ''} • <i class="fas fa-closed-captioning"></i> ${anime.episodes.sub}, <i class="fas fa-microphone"></i> ${anime.episodes.dub}</div>
                        </div>
                    </a>
                `).join('');
            } else {
                resultContainer.innerHTML = '<div class="no-results">Check your spelling</div>';
            }
        } catch (error) {
            console.error('Search error:', error);
            resultContainer.innerHTML = '<div class="no-results">Just click the search icon</div>';
            searchLoading.style.display = 'none';
            resultContainer.style.display = 'block';
        }
    }
});