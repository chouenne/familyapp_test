document.addEventListener('DOMContentLoaded', function() {
    // Function to fetch and display the list from the database
    function fetchBuyList() {
        fetch('app/select.php')
            .then(response => response.json())
            .then(data => {
                const buyList = document.getElementById('buyList');
                buyList.innerHTML = '';

                data.forEach(entry => {
                    const listItem = document.createElement('div');
                    listItem.innerHTML = `
                        <p>Store: ${entry.store}, Number of Items: ${entry.number_item}, Item: ${entry.item}, Buy For: ${entry.user_name}</p>
                        <button onclick="deleteEntry(${entry.demoID})">Delete</button>
                    `;
                    buyList.appendChild(listItem);
                });
            });
    }

    // Function to handle form submission
    document.getElementById('buyForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const store = document.getElementById('store').value;
        const numItems = document.getElementById('numItems').value;
        const item = document.getElementById('item').value;
        const userName = document.getElementById('userName').value;

        fetch('app/insert_v3.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `store=${store}&numItems=${numItems}&item=${item}&userName=${userName}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchBuyList(); // Refresh the list after successful insertion
            }
        });
    });

    // Function to handle delete button click
    function deleteEntry(demoID) {
        fetch('app/inser_v3.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `demoID=${demoID}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchBuyList(); // Refresh the list after successful deletion
            }
        });
    }

    // Initial fetch to display existing data
    fetchBuyList();
});

