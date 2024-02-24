const addBtn = document.getElementById('add-btn');
const table = document.querySelector('table');
const thead = document.querySelector('thead');
const tbody = document.querySelector('tbody');

const selectAllBody = document.querySelector('.select-all');
const selectModel = document.getElementById('select-all-model');
const selectPsuModel = document.getElementById('select-all-psumodel');
const selectLocation = document.getElementById('select-all-location');
const selectCustomer = document.getElementById('select-all-customer');
const selectCondition = document.getElementById('select-all-condition');
selectLocation.innerHTML = getOptions('location-options', false);
selectCustomer.innerHTML = getOptions('customer-options', false);

// Event listeners for changing all columns at the same time
selectModel.addEventListener('input', (e) => {
    const models = tbody.querySelectorAll('.td-model');
    models.forEach(model => {
        model.value = e.target.value;
    });
});

selectPsuModel ? selectPsuModel.addEventListener('input', (e) => {
    const models = tbody.querySelectorAll('.td-psu-model');
    models.forEach(model => {
        model.value = e.target.value;
    });
}) : '';

selectLocation.addEventListener('change', (e) => {
    const selects = tbody.querySelectorAll('.td-location');
    selects.forEach(select => {
        select.value = e.target.value;
    });
});

selectCustomer.addEventListener('change', (e) => {
    const selects = tbody.querySelectorAll('.td-customer-name');
    selects.forEach(select => {
        select.value = e.target.value;
    });
});

selectCondition.addEventListener('input', (e) => {
    const conds = tbody.querySelectorAll('.td-condition');
    conds.forEach(cond => {
        cond.value = e.target.value;
    });
});

let spacePressed = false;
let rowCount = 0;

// Gets each column name
const columnNames = [];
thead.querySelectorAll('tr th').forEach(th => {
    columnNames.push(th.innerText);
});

// Adds a new row when the space key is pressed
document.addEventListener('keyup', (e) => {
    if (e.key === ' ' && document.activeElement.tagName !== 'INPUT') {
        addRow();
    }
});

// Adds a new row when the "add row" button is clicked
addBtn.addEventListener('click', () => addRow());

// Function to add a new row
function addRow() {
    const tr = document.createElement('tr');
    for (let i in columnNames) {
        const nameString = `${columnNames[i].replace(' ', '-').toLowerCase()}`;
        const nameId = `${columnNames[i].replace(' ', '-').toLowerCase()}-${rowCount}`;

        if (nameString === 'location') {
            tr.innerHTML += `
                <td>
                    <select class="td-${nameString}" name="${nameId}" id="${nameId}" required>
                        ${getOptions('location-options')}
                    </select>
                </td>
            `;
        } else if (nameString === 'customer-name') {
            tr.innerHTML += `
                <td>
                    <select class="td-${nameString}" name="${nameId}" id="${nameId}" required>
                        ${getOptions('customer-options')}
                    </select>
                </td>
            `;
        } else if (nameString === 'operations') {
            const delBtn = createDeleteBtn();
            delBtn.addEventListener('click', () => deleteRow(tr));
            const td = document.createElement('td');
            td.appendChild(delBtn);
            tr.appendChild(td);
        } else {
            tr.innerHTML += `<td><input type="text" class="td-${nameString}" name="${nameId}" id="${nameId}"></td>`;
        }
    }

    rowCount++;
    tbody.appendChild(tr);
}

// Creates a delete button for a row
function createDeleteBtn() {
    const btn = document.createElement('button');
    btn.setAttribute('type', 'button');
    btn.classList.add('delete-btn');
    btn.innerText = '❌';
    return btn;
}

// Function to delete a row
function deleteRow(row) {
    row.remove();
    reindexRows();
    rowCount--;
}

// Function to re-index the rows and update input names
function reindexRows() {
    const rows = tbody.querySelectorAll('tr');
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input, select');
        inputs.forEach(input => {
            const nameParts = input.name.split('-');
            nameParts[nameParts.length - 1] = index;
            input.name = nameParts.join('-');
            input.id = `${nameParts[0]}-${index}`;
        });
    });
}

// Gets option data
function getOptions(elemId, showSelect = true) {
    const optionsEl = document.getElementById(elemId);
    const options = optionsEl.innerText.split(',');

    let optionString = `<option selected value="null" ${showSelect ? 'disabled' : ''}>${showSelect ? '--Select--' : ''}</option>`;
    for (let j in options) {
        optionString += `<option>${options[j]}</option>`;
    }

    return optionString;
}

// Prevents the ENTER key from submitting the data
document.addEventListener('keypress', function(e) {
    // Check if the pressed key is Enter (key code 13)
    if (e.key === 'Enter') {
        // Check if the currently focused element is an input field or textarea
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'BUTTON') {
            // Prevent the default behavior of the Enter key
            e.preventDefault();
        }
    }
});

// Handle pasting data from clipboard
document.addEventListener('paste', (e) => pasteFromSheet(e));

// Function to handle pasting data from clipboard
function pasteFromSheet(e) {

    const focusedElement = document.activeElement;
    if (focusedElement.tagName !== 'INPUT' && focusedElement.tagName !== 'SELECT') {
        e.preventDefault();

        const clipboardData = e.clipboardData || window.clipboardData;
        const pastedData = clipboardData.getData('text');

        const rows = pastedData.split('\n');
        rows.forEach(row => {
            addRow();
            const cells = row.split('\t');
            const tr = tbody.children[rowCount - 1];
            cells.forEach((cell, idx) => {
                const td = tr.children[idx];
                const input = td.firstElementChild;
                input.value = cell;
                if (input.nodeName === 'SELECT') {
                    input.selectedIndex = getOptionIndex(input, cell);
                }
            });
        });
    }
}

// Function to get option index
function getOptionIndex(selectElem, text) {
    const options = [...selectElem.options];
    for (let i = 0; i < options.length; i++) {
        if (options[i].innerText === text) {
            return i;
        }
    }
    return -1;
}
