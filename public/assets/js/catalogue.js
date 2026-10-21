document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('search-form');
    const input = document.getElementById('search-input');
    const body = document.getElementById('catalogue-body');
    const empty = document.getElementById('catalogue-empty');
    let timer = null;

    const cell = (text) => {
        const td = document.createElement('td');
        td.textContent = text;
        return td;
    };

    const statusCell = (disponibles) => {
        const td = document.createElement('td');
        const badge = document.createElement('span');
        const available = Number(disponibles) > 0;
        badge.className = 'badge ' + (available ? 'bg-success' : 'bg-danger');
        badge.textContent = available ? 'Disponible' : 'Indisponible';
        td.appendChild(badge);
        return td;
    };

    const render = (livres) => {
        body.replaceChildren();
        empty.classList.toggle('d-none', livres.length > 0);

        livres.forEach((livre) => {
            const row = document.createElement('tr');
            row.append(
                cell(livre.titre),
                cell(livre.auteur),
                cell(livre.categorie),
                cell(livre.annee),
                statusCell(livre.disponibles)
            );
            body.appendChild(row);
        });
    };

    const load = async () => {
        const params = new URLSearchParams({ q: input.value.trim() });

        try {
            const response = await fetch(form.dataset.searchUrl + '?' + params.toString(), {
                headers: { Accept: 'application/json' },
            });

            if (response.ok) {
                render(await response.json());
            }
        } catch (error) {
            return;
        }
    };

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(load, 300);
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        clearTimeout(timer);
        load();
    });
});
