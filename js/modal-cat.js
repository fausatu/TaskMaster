console.log('Modal script loaded'); // Debugging statement

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired'); // Debugging statement

    const addCategoryBtn = document.querySelector('.add-category');
    console.log('Add Category Button:', addCategoryBtn); // Debugging statement

    const modal = document.getElementById('add-category-modal');
    
    if (addCategoryBtn && modal) {
        console.log('Button and modal found'); // Debugging statement
        const closeBtn = modal.querySelector('.close');

        addCategoryBtn.addEventListener('click', function() {
            console.log('Add Category Button Clicked'); // Debugging statement
            modal.style.display = "block";
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = "none";
            });
        }

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    } else {
        console.log('Button or modal not found'); // Debugging statement
    }
}); // Closing brace for DOMContentLoaded
