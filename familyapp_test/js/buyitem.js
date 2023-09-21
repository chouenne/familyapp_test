async function fetchItems(url) {
    const response = await fetch(url);
    const data = await response.json();
    displayData(data);
   
}

fetchItems('app/select.php');

function displayData(data) {
    const display = document.querySelector('#display');
    display.innerHTML = '';

    let ul = document.createElement('ul');
    data.forEach((user)=>{
        let li = document.createElement('li');
        li.innerHTML = `Buy ${user.item} at ${user.store} for ${user.user_name}.`;

        ul.appendChild(li);
    
    })

    display.appendChild(ul);
}

const submitButton = document.querySelector('#submit');

submitButton.addEventListener('click', getFormData);

function getFormData(event){
    event.preventDefault();

  // Get the form data using the FormData API from the form with the ID 'insert-form'.
  const insertFormData = new FormData(document.querySelector('#insert-form'));

  // Specify the URL where the form data should be inserted.
  let url = 'app/insert.php';

  // Call the 'inserter' function to insert the form data using a POST request.
  inserter(insertFormData, url);
}

// This async function inserts data into the server using a POST request.
async function inserter(data, url) {
  // Send a POST request to the specified URL with the form data.
  const response = await fetch(url, {
    method: "POST",
    body: data
  });

  // Extract JSON confirmation data from the response.
  const confirmation = await response.json();

  // Log the confirmation data to the console.
  console.log(confirmation);

  // Refresh the page by calling the fetchFavourites function again to fetch and display updated data.
  fetchItems('app/select.php');
}