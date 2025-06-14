document.addEventListener('DOMContentLoaded', () => {
    const valorInput = document.getElementById('valor');
    const dataInput = document.getElementById('data');
    const diaHidden = document.getElementById('diaHidden');
    const mesHidden = document.getElementById('mesHidden');
    const anoHidden = document.getElementById('anoHidden');
    const parcelasInput = document.getElementById('parcelas');
    const recorrenteCheckbox = document.getElementById('recorrente');

    // Máscara para o valor
    valorInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        value = (value / 100).toFixed(2) + '';
        value = value.replace('.', ',');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        e.target.value = 'R$ ' + value;
    });

    // Desabilita parcelas se for recorrente
    recorrenteCheckbox.addEventListener('change', () => {
        if (recorrenteCheckbox.checked) {
            parcelasInput.value = 0;
            parcelasInput.setAttribute('disabled', 'disabled');
        } else {
            parcelasInput.removeAttribute('disabled');
        }
    });

    // Ao enviar, extrai dia, mês e ano
    dataInput.form.addEventListener('submit', (e) => {
        const partes = dataInput.value.split('/');
        if (partes.length === 3) {
            diaHidden.value = parseInt(partes[0]);
            mesHidden.value = parseInt(partes[1]);
            anoHidden.value = parseInt(partes[2]);
        } else {
            e.preventDefault();
            alert('Formato de data inválido. Use dd/mm/aaaa.');
        }

        // Remove "R$ " e formata valor corretamente
        valorInput.value = valorInput.value.replace(/[^\d,]/g, '').replace(',', '.');
    });
});
