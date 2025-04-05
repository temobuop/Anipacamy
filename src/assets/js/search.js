
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-input');
    const searchSuggest = document.getElementById('search-suggest');
    const searchLoading = document.getElementById('search-loading');
    const resultContainer = searchSuggest.querySelector('.result');
    let timeoutId;

    searchInput.addEventListener('input', function () {
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

    document.addEventListener('click', function (e) {
        if (!searchSuggest.contains(e.target) && !searchInput.contains(e.target)) {
            searchSuggest.style.display = 'none';
        }
    });

    async function fetchSearchResults(query) {
        try {
            const response = await fetch(`/src/ajax/search-ajax.php?keyword=${encodeURIComponent(query)}`);
            const text = await response.text();
            console.log('Raw response:', text);

            let data;
            try {
                data = JSON.parse(text);
            } catch (parseError) {
                console.error('Failed to parse JSON:', parseError);
                resultContainer.innerHTML = '<div class="no-results">Invalid server response</div>';
                searchLoading.style.display = 'none';
                resultContainer.style.display = 'block';
                return;
            }

            console.log('Parsed data:', data);

            searchLoading.style.display = 'none';
            resultContainer.style.display = 'block';

            if (data.success && Array.isArray(data.results?.data) && data.results.data.length > 0) {
                // Show only the first 5 results
                const firstFiveResults = data.results.data.slice(0, 5);

                const resultItems = firstFiveResults.map(anime => {
                    const title = anime.title || 'Untitled';
                    const japTitle = anime.japanese_title || '';
                    const duration = anime.duration || '';
                    const showType = anime.tvInfo?.showType || '';
                    const sub = anime.tvInfo?.sub ?? 0;
                    const dub = anime.tvInfo?.dub ?? 0;

                    return `
                        <a href="/details/${anime.id}" class="nav-item" style="display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #ddd;">
                            <div class="thumbnail" style="flex-shrink: 0; width: 60px; height: 80px; overflow: hidden;">
                                <img src="${anime.poster}" alt="${title}" style="width: 100%; height: auto;">
                            </div>
                            <div class="info" style="flex-grow: 1; margin-left: 10px;">
                                <div class="title" style="font-weight: bold;">${title}</div>
                                <div class="meta">${japTitle}</div>
                                <div class="meta">${duration} • ${showType} • 
                                    <i class="fas fa-closed-captioning"></i> ${sub} 
                                    <i class="fas fa-microphone" style="margin-left: 10px;"></i> ${dub}
                                </div>
                            </div>
                        </a>
                    `;
                }).join('');

                const viewAllButton = `
                    <a href="/search?keyword=${encodeURIComponent(query)}" class="nav-item nav-bottom" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; font-weight: bold; background: #f9f9f9;">
                        View all results <i class="fa fa-angle-right ml-2"></i>
                    </a>
                `;

                resultContainer.innerHTML = resultItems + viewAllButton;
            } else {
                resultContainer.innerHTML = '<div class="no-results">No results found. Please check your spelling.</div>';
            }

        } catch (error) {
            console.error('Search error:', error);
            resultContainer.innerHTML = '<div class="no-results">Something went wrong. Try again later.</div>';
            searchLoading.style.display = 'none';
            resultContainer.style.display = 'block';
        }
    }
});

