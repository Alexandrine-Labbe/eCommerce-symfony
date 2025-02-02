/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import 'bootstrap/dist/js/bootstrap.bundle';

const toastElList = document.querySelectorAll('.toast')
const toastList = [...toastElList].map(toastEl => {
    const toast = new bootstrap.Toast(toastEl);
    toast.show()
})


document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}, false)



// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
