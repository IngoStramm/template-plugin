// integracao-playfab

document.addEventListener('DOMContentLoaded', function () {
    function toggle_rebind_playfab_account_form() {
        const toggle_ipf_form = document.getElementById('toggle-rebind-playfab-account-form');
        if (typeof (toggle_ipf_form) === 'undefined' && toggle_ipf_form === null) {
            return;
        }
        const ipf_form = document.getElementById('rebind-playfab-account-form');
        if (typeof (ipf_form) === 'undefined' && ipf_form === null) {
            return;
        }
        const show_text = toggle_ipf_form.innerText;
        const hide_text = 'Cancelar.';
        toggle_ipf_form.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('click');
            if (ipf_form.style.display === 'none') {
                console.log('exibe');
                toggle_ipf_form.innerText = hide_text;
                ipf_form.style.display = 'block';
            } else {
                console.log('esconde');
                toggle_ipf_form.innerText = show_text;
                ipf_form.style.display = 'none';
            }
        });
        console.log('carregou 5', ipf_form.style.display);
    }
    toggle_rebind_playfab_account_form();
});