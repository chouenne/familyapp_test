async function fetchItems(url){
    const response = await fetch(url);
    const data = await response.json();
    console.log(data);
    displayData(data);
  }
  
  //call function to fetch data
  fetchItems('app/select.php');
  
  function displayData(data){
    //select element from HTML where we'll put our tv show
    const display = document.querySelector('#display');
    display.innerHTML = '';
  
    //create an unordered list
    let ul = document.createElement('ul');
    // let recordul = document.createElement('ul');
    // recordul .classList.add("recordlists");
    // let a = document.createElement('button');
    let a = document.createElement('button');
  
    
    data.forEach((user)=>{
      //console.log(user);
      //create items, add text and append to the list
      let recordLine = document.createElement('div');
      recordLine .classList.add("recordlines");
    
      let li = document.createElement('li');
      li.innerHTML = `<div>Buy ${user.number_item} ${user.item} at ${user.store} for ${user.user_name}.</div>`;
      
      // * _update * added an ugly "edit" link */
     
      // deleteIcon.href = `#${user.demoID}`;
      // deleteIcon.dataset.demoID = user.demoID;
      
      a.href = `#${user.demoID}`;
      a.dataset.demoID = user.demoID;
      // deleteIcon.innerHTML = '<div><i class="fa-solid fa-pen fa-2xs"></i></div>';
      a.innerHTML = '<div><i class="fa-solid fa-square-pen fa-lg"></i></div>';
      let buyEdit = a.querySelector('div');
      buyEdit.classList.add('editicon');
      // let buyDelete = deleteIcon.querySelector('div');
      // buyDelete.classList.add('deleteicon');
      

    //   <input class="form-check-input me-1" type="radio" name="listGroupRadio" value="" id="firstRadio" checked>
    // <label class="form-check-label" for="firstRadio">First radio</label>
     

      let deleteButton = document.createElement('div');
      deleteButton.href = `#${user.demoID}`;
      deleteButton.dataset.demoID = user.demoID;
      deleteButton.innerHTML = '<div><i class="fa-solid fa-circle-check fa-lg"></i></div>';
      deleteButton.classList.add('deleteicon');
      let iconFlex = document.createElement('icon-flex');
      iconFlex.classList.add('icon-flex');

      deleteButton.addEventListener('click', (event) => {
        event.preventDefault();
        let demoID = user.demoID; // Assuming demoID is the identifier for the record
        deleteRecord(demoID);
        recordLine.remove();
    });
      
      let buyList = li.querySelector('div');
      buyList.classList.add('record');
      
      recordLine.appendChild(buyList);
      // recordLine.appendChild(iconFlex);
   
      recordLine.appendChild(deleteButton); 
      recordLine.appendChild(buyEdit); 

      // recordLine.appendChild(buyDelete);
   
      buyEdit.addEventListener('click', (event)=> {
        // event.preventDefault();
        // buyList.style.display = 'none';
        // const input = document.createElement('input');
        //  input.value = text;
        //     input.classList.add('edit-input');
        //wow lots of parameters
        updateForm(event, user.demoID, user.store, user.number_item, user.item, user.user_name);
      });

      //see below for functionality
      // * end _update */
      ul.appendChild(recordLine);
    })
    //don't forget to append your elements.
    display.appendChild(ul);
  }

  function deleteRecord(demoID) {
    // Send an AJAX request to the server to delete the record with the given demoID
    // Example using fetch API:
    fetch('app/delete.php', {
        method: 'POST',
        body: `demoID=${demoID}`,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        console.log('Record deleted successfully:', data);
    })
    .catch(error => {
        console.error('Error deleting record:', error);
    });
}

  const submitButton = document.querySelector('#submit');
  submitButton.addEventListener('click', getFormData);
  
  function getFormData(event){
    event.preventDefault();
  
    //get the form data & call an async function
    const insertFormData = new FormData(document.querySelector('#insert-form'));
    let url = 'app/insert_v3.php';
    inserter(insertFormData, url);
  }
  
  async function inserter(data, url){
    const response = await fetch(url, {
      method: "POST",
      body: data
    });
    const confirmation = await response.json();
  
    console.log(confirmation);
    //call function again to refresh the page
    fetchItems('app/select.php');
  }
  
  // _update section **
  
  function updateForm(event, demoID, store, number_item, item, user_name){
    console.log(demoID, store, number_item, item, user_name);
    //default is to navigate to an id i.e. reload page?
    event.preventDefault(); 
  
    // pseudo code: let's either 
      // populate the existing form OR
      // create a form element inline ** doing this one ** 
  
    //a few handy things
      //get href i.e. https://nortonb.web582.com/demo_db/index_update.html#63
        //then split into 2 array elements
        //then get the 2nd (index[1]) array element et voila... magic
      // console.log(event.target.href.split('#')[1]);
      // console.log(event.target.parentNode);
      // console.log(event.target.parentNode.textContent);
    //we click the link <a href...> so need to target parentNode i.e. <li>...
    
    let li = event.target.parentNode;
  
    event.target.parentNode.innerHTML = `<form class="edit-input" id="update-form"><input type="hidden" name="demoID" value="${demoID}"> Buy <input placeholder="How many" type="number" name="number_item" value="${number_item}"> <input placeholder="What to buy" type="text" name="item" value="${item}"> at <input placeholder="Where to Buy" type="text" name="store" value="${store}"> for <input placeholder="Who need" type="text" name="user_name" value="${user_name}">. <a href="#update" id="update">update</a>`;
    
    console.log(li.querySelector('#update'));
    li.querySelector('#update').addEventListener('click', (event)=>{
      event.preventDefault();
      console.log(event);
      //expecting "demoID", "store" "item" and "user_name" in form data.
      let updateData = new FormData(document.querySelector('#update-form'));
      //call function to "fetch" data, posting to app/update.php
      let url = 'app/update.php';
      updater(updateData, url);
    })
  
  }

  // async function deleteBuy(demoId) {
  //   await fetch(`app/insert_v3.php?id=${demoId}`);
  //   await fetchItems();
  // }
  
  
  //again with the names!!
  async function updater(data, url){
    const response = await fetch(url, {
      method: "POST",
      body: data
    });
    const confirmation = await response.json();
  
    console.log(confirmation);
    //call function again to refresh the page
    fetchItems('app/select.php');
  }
  