<x-app-layout>
    <x-breadcrumb name="cluster.distance" />

    <x-card-container>
        <p class="text-xs font-semibold">Euclidean Distance</p>
        <div class="overflow-auto">
            <div id="euclidean-distance"></div>
        </div>
    </x-card-container>
    @push('js-internal')
        <script async>
            const distances = @json($distances);
            // make table to visualize the distance
            const table = document.createElement('table');
            table.classList.add('table-auto', 'w-full');
            const thead = document.createElement('thead');
            const tbody = document.createElement('tbody');
            const theadRow = document.createElement('tr');
            const theadCol = document.createElement('th');
            theadCol.classList.add('border', 'px-4', 'py-2');
            theadCol.textContent = 'C';
            theadRow.appendChild(theadCol);
            for (let i = 0; i < distances[0].length; i++) {
                const theadCol = document.createElement('th');
                theadCol.classList.add('border', 'px-4', 'py-2');
                theadCol.textContent = `C ${i + 1}`;
                theadRow.appendChild(theadCol);
            }
            thead.appendChild(theadRow);
            table.appendChild(thead);
            for (let i = 0; i < distances.length; i++) {
                const row = document.createElement('tr');
                const col = document.createElement('td');
                col.classList.add('border', 'px-4', 'py-2');
                col.textContent = `C ${i + 1}`;
                row.appendChild(col);
                for (let j = 0; j < distances[i].length; j++) {
                    const col = document.createElement('td');
                    col.classList.add('border', 'px-4', 'py-2');
                    col.textContent = distances[i][j].toFixed(2);
                    row.appendChild(col);
                }
                tbody.appendChild(row);
            }
            table.appendChild(tbody);
            document.getElementById('euclidean-distance').appendChild(table);
        </script>
    @endpush
</x-app-layout>
