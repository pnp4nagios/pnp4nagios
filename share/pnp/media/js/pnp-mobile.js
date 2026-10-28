/* Live filter for the lists on the mobile pages: <input class="filter"> before a <ul class="menu"> */
document.addEventListener('DOMContentLoaded', function () {
    Array.prototype.forEach.call(document.querySelectorAll('input.filter'), function (input) {
        var items = input.parentNode.querySelectorAll('ul.menu li');
        input.addEventListener('input', function () {
            var term = input.value.toLowerCase();
            Array.prototype.forEach.call(items, function (li) {
                li.style.display = (term === '' || li.textContent.toLowerCase().indexOf(term) !== -1) ? '' : 'none';
            });
        });
    });
});
