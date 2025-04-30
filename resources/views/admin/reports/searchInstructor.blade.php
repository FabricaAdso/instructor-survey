<form onsubmit="event.preventDefault(); performSearch(1);">
                    <input type="text" id="instructor_search" name="instructor_search">
                    <button type="submit" class="btn-search">Buscar</button>
                </form>

                
                <script>
                    
    function performSearch(page = 1) {
        const searchValue = document.getElementById('instructor_search').value;
        const url =
            `{{ route('admin.instructors') }}?page=${page}&instructor_search=${encodeURIComponent(searchValue)}`;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.querySelector('.table-container').innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
    }

    document.addEventListener('DOMContentLoaded', () => {
        performSearch();
    });
                </script>


<style>
       /* Input de búsqueda */
       #instructor_search {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            max-width: 300px;
            min-width: 80px;
            transition: border-color 0.3s;
        }

        #instructor_search:focus {
            border-color: #388E3C;
            outline: none;
        }

        .btn-search {
            font-size: 1rem;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid transparent;
            transition: background-color 0.3s ease;
            background-color: white;
            color: #4CAF50;
            border-color: #4CAF50;
            cursor: pointer;
        }
</style>