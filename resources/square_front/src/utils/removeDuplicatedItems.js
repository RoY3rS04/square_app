export default function removeDuplicatedItems(array) {
    let shouldNotify = [];

    // Declare an empty object
    let uniqueObject = {};

    // Loop for the array elements
    for (let i in array) {

        // Extract the id
        const objId = array[i]['id'];

        // Use the id as the index
        uniqueObject[objId] = array[i];
    }

    // Loop to push unique object into array
    for (const i in uniqueObject) {
        shouldNotify.push(uniqueObject[i]);
    }

    return shouldNotify;
}
